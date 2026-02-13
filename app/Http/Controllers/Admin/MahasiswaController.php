<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Rombel;
use App\Models\Prodi;
use App\Models\TahunAkademik;
use App\Models\PendidikanMahasiswa;
use App\Models\RiwayatKuliah;
use App\Models\PembayaranMahasiswa;
use App\Models\Krs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class MahasiswaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | BUG FIX #2 — method index()
    |--------------------------------------------------------------------------
    | MASALAH SEBELUMNYA:
    |   Method index() hanya mengirim $tahunAkademikAktif (satu record atau null)
    |   ke view. Di view, field Tahun Akademik dirender sebagai readonly input
    |   yang hanya bisa menampilkan satu nilai. Jika $tahunAkademikAktif = null,
    |   field menampilkan error dan tidak bisa diklik sama sekali.
    |
    | PERBAIKAN:
    |   Tambahkan $tahunAkademiks (semua record, diurutkan terbaru) ke compact().
    |   View sudah diupdate menjadi <select> dropdown yang menggunakan variabel ini.
    |--------------------------------------------------------------------------
    */

    // ========== 1. INDEX (LIST DATA) ==========
    public function index(Request $request)
    {
        $query = Mahasiswa::with(['user', 'prodi', 'rombel']);

        if ($request->filled('prodi')) {
            $query->where('id_prodi', $request->prodi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('nama', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        $mahasiswa = $query->get();

        $users = User::where('id_role', 8)
            ->whereNotIn('id', function ($query) {
                $query->select('id_users')->from('mahasiswa');
            })
            ->get(['id', 'nama', 'email']);

        $prodis = Prodi::all();
        $tahunAkademikAktif = TahunAkademik::where('status_aktif', true)->first();

        // FIX: Tambahkan semua tahun akademik untuk dropdown di modal Tambah Mahasiswa
        // Diurutkan tahun terbaru dulu agar pilihan paling relevan ada di atas
        $tahunAkademiks = TahunAkademik::orderByDesc('tahun_awal')
            ->orderByRaw("CASE WHEN semester = 'Ganjil' THEN 1 WHEN semester = 'Genap' THEN 2 ELSE 3 END")->get();

        return view('mahasiswa.index', compact(
            'mahasiswa',
            'users',
            'prodis',
            'tahunAkademikAktif',
            'tahunAkademiks'  // FIX: ditambahkan
        ));
    }

    // ========== 2. STORE (CREATE) ==========
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_users' => 'required|exists:users,id|unique:mahasiswa,id_users',
            'id_prodi' => 'required|exists:prodi,id',
            'nim' => 'required|unique:mahasiswa,nim|max:20',
            'tahun_masuk' => 'required|exists:tahun_akademik,id',
            'nik' => 'nullable|digits:16|unique:mahasiswa,nik',
            'no_kk' => 'nullable|digits:16|unique:mahasiswa,no_kk',
            'nisn' => 'nullable|digits:10|unique:mahasiswa,nisn',
        ]);

        DB::beginTransaction();
        try {
            $rombel = Rombel::where('id_prodi', $validated['id_prodi'])
                ->where('tahun_masuk', $validated['tahun_masuk'])
                ->first();

            if (!$rombel) {
                $prodi = Prodi::findOrFail($validated['id_prodi']);
                $tahun = TahunAkademik::findOrFail($validated['tahun_masuk']);

                $rombel = Rombel::create([
                    'kode_rombel' => strtoupper(substr($prodi->kode_prodi, 0, 3)) . substr($tahun->tahun_awal, -2),
                    'nama_rombel' => $prodi->nama_prodi . ' - Angkatan ' . $tahun->tahun_awal,
                    'tahun_masuk' => $validated['tahun_masuk'],
                    'id_prodi' => $validated['id_prodi'],
                    'id_dosen' => $prodi->id_dosen,
                ]);

                Log::info("Auto-created Rombel: {$rombel->nama_rombel}");
            }

            $validated['id_rombel'] = $rombel->id;

            Mahasiswa::create($validated);

            DB::commit();
            return redirect()->route('mahasiswa.index')
                ->with('success', 'Mahasiswa berhasil ditambahkan ke Rombel: ' . $rombel->nama_rombel);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error create mahasiswa: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // ========== 3. EDIT (SHOW FORM) ==========
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        return view('mahasiswa.edit-mahasiswa', compact('mahasiswa'));
    }

    // ========== 4. UPDATE ==========
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->id_users,
            'nim' => 'required|unique:mahasiswa,nim,' . $id,
            'nik' => 'required|digits:16|unique:mahasiswa,nik,' . $id,
            'no_kk' => 'required|digits:16|unique:mahasiswa,no_kk,' . $id,
            'nisn' => 'required|digits:10|unique:mahasiswa,nisn,' . $id,
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Katolik,Protestan,Budha,Hindu,Konghucu',
            'prov' => 'required|string|max:50',
            'kab' => 'required|string|max:50',
            'kec' => 'required|string|max:50',
            'ds_kel' => 'required|string|max:50',
            'dusun' => 'required|string|max:100',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'kode_pos' => 'required|digits:5',
            'hp' => 'required|string|max:15',
            'marital_status' => 'required|in:Lajang,Menikah,Cerai Hidup,Cerai Mati',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'gol_darah' => 'nullable|in:A,B,AB,O',
        ]);

        DB::beginTransaction();
        try {
            $mahasiswa->user->update([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
            ]);

            $mahasiswa->update(collect($validated)->except(['nama', 'email'])->toArray());

            DB::commit();
            return redirect()->route('mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update mahasiswa: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal update data: ' . $e->getMessage());
        }
    }

    public function resetDataOrtu($nim)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

            $dataOrtu = [
                'nama_ayah'          => null,
                'tempat_lahir_ayah'  => null,
                'tanggal_lahir_ayah' => null,
                'pendidikan_ayah'    => null,
                'pekerjaan_ayah'     => null,
                'penghasilan_ayah'   => null,
                'hp_ayah'            => null,
                'alamat_ayah'        => null,
                'nama_ibu'           => null,
                'tempat_lahir_ibu'   => null,
                'tanggal_lahir_ibu'  => null,
                'pendidikan_ibu'     => null,
                'pekerjaan_ibu'      => null,
                'penghasilan_ibu'    => null,
                'hp_ibu'             => null,
                'alamat_ibu'         => null,
                'nama_wali'          => null,
                'pekerjaan_wali'     => null,
                'penghasilan_wali'   => null,
                'hp_wali'            => null,
            ];

            $mahasiswa->update($dataOrtu);

            return back()->with('success', 'Data orang tua berhasil dibersihkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal reset: ' . $e->getMessage());
        }
    }

    // ========== 5. DELETE ==========
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);

            $softfiles = [
                'softfile_surat_pernyataan',
                'softfile_pas_foto',
                'softfile_ktp_mhs',
                'softfile_kk',
                'softfile_akte',
                'softfile_ktp_ayah',
                'softfile_ktp_ibu',
                'softfile_skl',
                'softfile_transkrip',
                'softfile_ijazah'
            ];

            foreach ($softfiles as $field) {
                if ($mahasiswa->$field && Storage::disk('public')->exists($mahasiswa->$field)) {
                    Storage::disk('public')->delete($mahasiswa->$field);
                }
            }

            $mahasiswa->delete();

            DB::commit();
            return redirect()->route('mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error delete mahasiswa: ' . $e->getMessage());
            return back()->with('error', 'Gagal hapus data: ' . $e->getMessage());
        }
    }

    // ========== TAB BIODATA ==========
    public function biodata($nim)
    {
        $mahasiswa = Mahasiswa::with('user')->where('nim', $nim)->firstOrFail();
        return view('mahasiswa.biodata-mhs', compact('mahasiswa'));
    }

    public function updateBiodata(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $mahasiswa->id_users,
            'nik' => 'required|digits:16|unique:mahasiswa,nik,' . $mahasiswa->id,
            'no_kk' => 'required|digits:16|unique:mahasiswa,no_kk,' . $mahasiswa->id,
            'nisn' => 'required|digits:10|unique:mahasiswa,nisn,' . $mahasiswa->id,
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|in:Islam,Katolik,Protestan,Budha,Hindu,Konghucu',
            'prov' => 'required|string|max:50',
            'kab' => 'required|string|max:50',
            'kec' => 'required|string|max:50',
            'ds_kel' => 'required|string|max:50',
            'dusun' => 'required|string|max:100',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'kode_pos' => 'required|digits:5',
            'hp' => 'required|string|max:15',
            'marital_status' => 'required|in:Lajang,Menikah,Cerai Hidup,Cerai Mati',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'gol_darah' => 'nullable|in:A,B,AB,O',
        ]);

        DB::beginTransaction();
        try {
            $mahasiswa->user->update([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
            ]);

            $mahasiswa->update(collect($validated)->except(['nama', 'email'])->toArray());

            DB::commit();
            return redirect()->route('mahasiswa.biodata', $nim)
                ->with('success', 'Biodata berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    // ========== TAB PENDIDIKAN ==========
    public function pendidikan($nim)
    {
        $mahasiswa = Mahasiswa::with('pendidikan')->where('nim', $nim)->firstOrFail();
        return view('mahasiswa.datapendidikan-mhs', compact('mahasiswa'));
    }

    public function storePendidikan(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'jenjang' => 'required|string|max:50',
            'nama_sekolah' => 'required|string|max:150',
            'jurusan' => 'nullable|string|max:100',
            'alamat_sekolah' => 'nullable|string',
            'tahun_lulus' => 'required|digits:4|integer',
            'no_ijazah' => 'nullable|string|max:50',
            'file_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $validated['id_mahasiswa'] = $mahasiswa->id;

            if ($request->hasFile('file_ijazah')) {
                $file = $request->file('file_ijazah');
                $filename = 'ijazah_' . $mahasiswa->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
                $validated['file_ijazah'] = $file->storeAs('ijazah_mahasiswa', $filename, 'public');
            }

            PendidikanMahasiswa::create($validated);

            DB::commit();
            return redirect()->route('mahasiswa.pendidikan', $nim)
                ->with('success', 'Riwayat pendidikan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function editPendidikan($nim, $id)
    {
        $pendidikan = PendidikanMahasiswa::findOrFail($id);
        return response()->json($pendidikan);
    }

    public function updatePendidikan(Request $request, $nim, $id)
    {
        $pendidikan = PendidikanMahasiswa::findOrFail($id);
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'jenjang'        => 'required|string|max:50',
            'nama_sekolah'   => 'required|string|max:150',
            'jurusan'        => 'nullable|string|max:100',
            'alamat_sekolah' => 'nullable|string',
            'tahun_lulus'    => 'required|digits:4',
            'no_ijazah'      => 'nullable|string|max:50',
            'file_ijazah'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('file_ijazah')) {
                $file = $request->file('file_ijazah');
                $ext = $file->getClientOriginalExtension();
                $filename = 'ijazah_' . $mahasiswa->nim . '_' . $validated['jenjang'] . '_' . time() . '.' . $ext;
                $validated['file_ijazah'] = $file->storeAs('ijazah_mahasiswa', $filename, 'public');
            }

            $pendidikan->update($validated);

            DB::commit();
            return redirect()->back()->with('success', 'Riwayat pendidikan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroyPendidikan($nim, $id)
    {
        $pendidikan = PendidikanMahasiswa::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($pendidikan->file_ijazah && Storage::disk('public')->exists($pendidikan->file_ijazah)) {
                Storage::disk('public')->delete($pendidikan->file_ijazah);
            }

            $pendidikan->delete();

            DB::commit();
            return redirect()->route('mahasiswa.pendidikan', $nim)
                ->with('success', 'Riwayat pendidikan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    // ========== TAB ORANG TUA ==========
    public function dataortu($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();
        return view('mahasiswa.dataortu-mhs', compact('mahasiswa'));
    }

    public function updateOrangtua(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'nama_ayah'          => 'required|string|max:100',
            'tempat_lahir_ayah'  => 'nullable|string|max:50',
            'tanggal_lahir_ayah' => 'nullable|date',
            'pendidikan_ayah'    => 'nullable|string|max:50',
            'pekerjaan_ayah'     => 'nullable|string|max:100',
            'penghasilan_ayah'   => 'nullable|string|max:100',
            'hp_ayah'            => 'nullable|string|max:14',
            'alamat_ayah'        => 'nullable|string',
            'nama_ibu'           => 'required|string|max:100',
            'tempat_lahir_ibu'   => 'nullable|string|max:50',
            'tanggal_lahir_ibu'  => 'nullable|date',
            'pendidikan_ibu'     => 'nullable|string|max:50',
            'pekerjaan_ibu'      => 'nullable|string|max:100',
            'penghasilan_ibu'    => 'nullable|string|max:100',
            'hp_ibu'             => 'nullable|string|max:14',
            'alamat_ibu'         => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $mahasiswa->update($validated);

            DB::commit();
            return redirect()->route('mahasiswa.dataortu', $nim)
                ->with('success', 'Data orang tua berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    // ========== TAB AKADEMIK ==========
    public function akademik($nim)
    {
        $mahasiswa = Mahasiswa::with([
            'prodi.fakultas',
            'rombel.dosen.user',
            'tahunAkademik',
            'krs.matkul'
        ])
            ->where('nim', $nim)
            ->firstOrFail();

        $semesterIPS      = $mahasiswa->getAllSemesterIPS();
        $ipk              = $mahasiswa->hitungIPK();
        $semesterTerakhir = $mahasiswa->semester_terakhir;
        $bebanSKS         = $mahasiswa->tentukanBebanSKS($semesterTerakhir);
        $totalSks         = $mahasiswa->total_sks;
        $sksKurang        = max(0, 144 - $totalSks);
        $predikatKelulusan = $this->getPredikatKelulusan($ipk);

        return view('mahasiswa.infoakad-mhs', compact(
            'mahasiswa',
            'semesterIPS',
            'ipk',
            'bebanSKS',
            'semesterTerakhir',
            'totalSks',
            'sksKurang',
            'predikatKelulusan'
        ));
    }

    public function updateAkademik(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'krs.*.id'          => 'required|exists:krs,id',
            'krs.*.nilai_huruf' => 'required|in:A,B,C,D,E',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['krs'] as $krsData) {
                $krs = Krs::findOrFail($krsData['id']);
                $krs->update([
                    'nilai_huruf' => $krsData['nilai_huruf'],
                    'nilai_angka' => $this->konversiNilaiKeAngka($krsData['nilai_huruf']),
                ]);
            }

            DB::commit();
            return redirect()->route('mahasiswa.akademik', $nim)
                ->with('success', 'Nilai akademik berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update nilai: ' . $e->getMessage());
        }
    }

    private function konversiNilaiKeAngka($nilaiHuruf)
    {
        return match (strtoupper($nilaiHuruf)) {
            'A' => 4.0,
            'B' => 3.0,
            'C' => 2.0,
            'D' => 1.0,
            default => 0.0,
        };
    }

    private function getPredikatKelulusan($ipk)
    {
        if ($ipk >= 3.51) return ['label' => 'Cum Laude',          'class' => 'success'];
        if ($ipk >= 3.01) return ['label' => 'Sangat Memuaskan',   'class' => 'primary'];
        if ($ipk >= 2.76) return ['label' => 'Memuaskan',          'class' => 'info'];
        if ($ipk >= 2.00) return ['label' => 'Cukup',              'class' => 'warning'];
        return                    ['label' => 'Kurang',             'class' => 'danger'];
    }

    // ========== TAB RIWAYAT KULIAH ==========
    public function riwayatKuliah($nim)
    {
        $mahasiswa = Mahasiswa::with('riwayatKuliah')->where('nim', $nim)->firstOrFail();
        return view('mahasiswa.riwayat-kuliah', compact('mahasiswa'));
    }

    public function storeRiwayatKuliah(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'kampus_asal'    => 'required|string|max:150',
            'prodi_asal'     => 'required|string|max:100',
            'tahun_masuk'    => 'required|digits:4|integer',
            'tahun_keluar'   => 'required|digits:4|integer|gte:tahun_masuk',
            'jenis'          => 'required|in:Transfer,Pindahan,Lanjutan',
            'alasan'         => 'nullable|string',
            'file_transkrip' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $data = $validated;
            $data['id_mahasiswa'] = $mahasiswa->id;
            $data['kategori']     = 'Formal';

            if ($request->hasFile('file_transkrip')) {
                $file = $request->file('file_transkrip');
                $filename = 'transkrip_' . $mahasiswa->nim . '_' . time() . '.pdf';
                $data['file_transkrip'] = $file->storeAs('transkrip_mahasiswa', $filename, 'public');
            }

            RiwayatKuliah::create($data);

            DB::commit();
            return redirect()->route('mahasiswa.riwayat-kuliah', $nim)
                ->with('success', 'Riwayat kuliah berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal simpan: ' . $e->getMessage());
        }
    }

    public function destroyRiwayatKuliah($nim, $id)
    {
        $riwayat = RiwayatKuliah::findOrFail($id);

        if ($riwayat->file_transkrip && Storage::disk('public')->exists($riwayat->file_transkrip)) {
            Storage::disk('public')->delete($riwayat->file_transkrip);
        }

        $riwayat->delete();

        return redirect()->route('mahasiswa.riwayat-kuliah', $nim)->with('success', 'Riwayat kuliah dihapus');
    }

    // ========== TAB PEMBAYARAN ==========
    public function pembayaran($nim)
    {
        $mahasiswa    = Mahasiswa::with('pembayaran.tahunAkademik')->where('nim', $nim)->firstOrFail();
        $tahunAkademik = TahunAkademik::orderBy('tahun_awal', 'desc')->get();
        return view('mahasiswa.pembayaran', compact('mahasiswa', 'tahunAkademik'));
    }

    public function storePembayaran(Request $request, $nim)
    {
        if (!in_array(Auth::user()->id_role, [1, 3])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menambah pembayaran.');
        }

        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'id_tahun_akademik'  => 'required|exists:tahun_akademik,id',
            'jenis_pembayaran'   => 'required|string|max:50',
            'jumlah_tagihan'     => 'required|numeric|min:0',
            'jumlah_dibayar'     => 'nullable|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date',
            'tanggal_bayar'      => 'nullable|date',
            'bukti_bayar'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan'         => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $validated['id_mahasiswa']  = $mahasiswa->id;
            $validated['jumlah_dibayar'] = $validated['jumlah_dibayar'] ?? 0;
            $validated['sisa_tagihan']   = $validated['jumlah_tagihan'] - $validated['jumlah_dibayar'];

            if ($validated['jumlah_dibayar'] >= $validated['jumlah_tagihan']) {
                $validated['status'] = 'Lunas';
            } elseif ($validated['jumlah_dibayar'] > 0) {
                $validated['status'] = 'Cicilan';
            } else {
                $validated['status'] = 'Belum Bayar';
            }

            if ($request->hasFile('bukti_bayar')) {
                $file = $request->file('bukti_bayar');
                $filename = 'bukti_' . $mahasiswa->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
                $validated['bukti_bayar'] = $file->storeAs('bukti_bayar', $filename, 'public');
            }

            PembayaranMahasiswa::create($validated);

            DB::commit();
            return redirect()->route('mahasiswa.pembayaran', $nim)->with('success', 'Data pembayaran berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function updatePembayaran(Request $request, $nim, $id)
    {
        $mahasiswa  = Mahasiswa::where('nim', $nim)->firstOrFail();
        $pembayaran = PembayaranMahasiswa::where('id_mahasiswa', $mahasiswa->id)->findOrFail($id);

        $validated = $request->validate([
            'jumlah_dibayar'     => 'required|numeric|min:0',
            'tanggal_bayar'      => 'nullable|date',
            'bukti_bayar'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan'         => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti_bayar')) {
                if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
                    Storage::disk('public')->delete($pembayaran->bukti_bayar);
                }
                $file = $request->file('bukti_bayar');
                $filename = 'bukti_' . $mahasiswa->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
                $validated['bukti_bayar'] = $file->storeAs('bukti_bayar', $filename, 'public');
            }

            $pembayaran->update($validated);

            DB::commit();
            return redirect()->route('mahasiswa.pembayaran', $nim)->with('success', 'Data pembayaran berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroyPembayaran($nim, $id)
    {
        $pembayaran = PembayaranMahasiswa::findOrFail($id);

        if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $pembayaran->delete();

        return redirect()->route('mahasiswa.pembayaran', $nim)->with('success', 'Data pembayaran dihapus');
    }

    // ========== TAB DOKUMEN ==========
    public function dokumen($nim)
    {
        $mahasiswa = Mahasiswa::with('dokumen')->where('nim', $nim)->firstOrFail();
        return view('mahasiswa.dokumen-mhs', compact('mahasiswa'));
    }

    public function storeDokumen(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'nama_dokumen'  => 'required|string|max:150',
            'jenis_dokumen' => 'required|in:Ijazah,Transkrip,KTP,KK,Akta Lahir,Foto,Sertifikat,Surat Keterangan,Lainnya',
            'file'          => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'keterangan'    => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $file     = $request->file('file');
            $filename = 'dok_' . $mahasiswa->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs('dokumen_mahasiswa', $filename, 'public');

            $mahasiswa->dokumen()->create([
                'nama_dokumen'  => $validated['nama_dokumen'],
                'jenis_dokumen' => $validated['jenis_dokumen'],
                'file_path'     => $path,
                'ukuran_file'   => $file->getSize(),
                'ekstensi'      => $file->getClientOriginalExtension(),
                'keterangan'    => $validated['keterangan'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('mahasiswa.dokumen', $nim)->with('success', 'Dokumen berhasil diupload');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    public function destroyDokumen($nim, $id)
    {
        $dokumen = \App\Models\DokumenMahasiswa::findOrFail($id);

        if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return redirect()->route('mahasiswa.dokumen', $nim)->with('success', 'Dokumen berhasil dihapus');
    }

    // ========== TAB KELENGKAPAN DOKUMEN ==========
    public function kelengkapanDokumen($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $jenisDokumen = [
            'surat_pernyataan' => 'Surat Pernyataan',
            'pas_foto'         => 'Pas Foto 3x4',
            'ktp_mhs'          => 'KTP Mahasiswa',
            'kk'               => 'Kartu Keluarga',
            'akte'             => 'Akte Kelahiran',
            'ktp_ayah'         => 'KTP Ayah',
            'ktp_ibu'          => 'KTP Ibu',
            'skl'              => 'Surat Keterangan Lulus',
            'transkrip'        => 'Transkrip Nilai',
            'ijazah'           => 'Ijazah',
        ];

        return view('mahasiswa.kelengkapan-dokumen', compact('mahasiswa', 'jenisDokumen'));
    }

    public function uploadKelengkapanDokumen(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        $validated = $request->validate([
            'jenis_dokumen'  => 'required|string',
            'file'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'hardfile_status' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $file         = $request->file('file');
            $jenis        = $validated['jenis_dokumen'];
            $filename     = $jenis . '_' . $mahasiswa->nim . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path         = $file->storeAs('dokumen_mahasiswa', $filename, 'public');
            $softfileField = "softfile_$jenis";
            $hardfileField = "hardfile_$jenis";

            if ($mahasiswa->$softfileField && Storage::disk('public')->exists($mahasiswa->$softfileField)) {
                Storage::disk('public')->delete($mahasiswa->$softfileField);
            }

            $updateData = [$softfileField => $path];

            if ($request->filled('hardfile_status') && $request->hardfile_status) {
                $updateData[$hardfileField] = true;
            }

            $mahasiswa->update($updateData);

            DB::commit();
            return redirect()->route('mahasiswa.kelengkapan-dokumen', $nim)
                ->with('success', 'Dokumen berhasil diupload.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }

    public function toggleHardfileStatus(Request $request, $nim)
    {
        try {
            $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

            $validated = $request->validate([
                'jenis_dokumen' => 'required|string',
                'status'        => 'required|boolean',
            ]);

            $fieldName = "hardfile_" . $validated['jenis_dokumen'];

            DB::table('mahasiswa')
                ->where('nim', $nim)
                ->update([$fieldName => $validated['status'], 'updated_at' => now()]);

            $kelengkapanHardfile = $mahasiswa->fresh()->kelengkapan_hardfile;

            return response()->json([
                'success'              => true,
                'message'              => 'Status hardfile berhasil diupdate',
                'kelengkapan_hardfile' => $kelengkapanHardfile,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteKelengkapanDokumen($nim, $jenis)
    {
        try {
            $mahasiswa     = Mahasiswa::where('nim', $nim)->firstOrFail();
            $softfileField = "softfile_$jenis";
            $hardfileField = "hardfile_$jenis";

            if ($mahasiswa->$softfileField && Storage::disk('public')->exists($mahasiswa->$softfileField)) {
                Storage::disk('public')->delete($mahasiswa->$softfileField);
            }

            $mahasiswa->update([$softfileField => null, $hardfileField => false]);

            return back()->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}
