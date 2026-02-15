    <?php

    use App\Http\Controllers\Admin\DosenController;
    use App\Http\Controllers\Admin\FakultasController;
    use App\Http\Controllers\Admin\JadwalController;
    use App\Http\Controllers\Admin\KaryawanController;
    use App\Http\Controllers\Admin\KrsController;
    use App\Http\Controllers\Admin\LoginSessionController;
    use App\Http\Controllers\Admin\MahasiswaController;
    use App\Http\Controllers\Admin\MatkulController;
    use App\Http\Controllers\Admin\PenggunaController;
    use App\Http\Controllers\Admin\ProdiController;
    use App\Http\Controllers\Admin\RoleController;
    use App\Http\Controllers\Admin\RombelController;
    use App\Http\Controllers\Admin\RuanganController;
    use App\Http\Controllers\Admin\TahunAkademikController;
    use App\Http\Controllers\Admin\PendidikanDosenController;
    use App\Http\Controllers\Admin\DokumenDosenController;
    use App\Http\Controllers\DashboardController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\PendidikanMahasiswaController;
    use App\Http\Controllers\Admin\InventarisController;
    use App\Http\Controllers\Admin\ArsipController;
    use App\Http\Controllers\Admin\NilaiController;
    use Illuminate\Support\Facades\Auth;

    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/lupa-password', function () {
        return view('lupa-password');
    });

    Route::middleware(['auth', 'prevent-back-history'])->group(function () {

        //Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        //Master-Role
        Route::get('/master-role', [RoleController::class, 'index'])->name('master-role.index');
        Route::post('/master-role', [RoleController::class, 'store']);
        Route::delete('/master-role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
        Route::get('/master-role/{id}/edit', [RoleController::class, 'edit'])->name('master-role.edit');
        Route::put('/master-role/{id}', [RoleController::class, 'update'])->name('master-role.update');
        Route::put('/master-role/{id}', [RoleController::class, 'update'])->name('master-role.update');

        //Master-Pengguna
        Route::get('/master-pengguna', [PenggunaController::class, 'index'])->name('master-pengguna.index');
        Route::post('/master-pengguna', [PenggunaController::class, 'store']);
        Route::resource('user', PenggunaController::class);
        Route::post('/master-pengguna/store', [PenggunaController::class, 'store'])->name('master-pengguna.store');
        Route::put('/master-pengguna/{user}', [PenggunaController::class, 'update'])->name('master-pengguna.update');
        Route::patch('master-pengguna/{id}', [PenggunaController::class, 'updateStatus'])->name('master-pengguna.updateStatus');
        Route::delete('/master-pengguna/{user}', [PenggunaController::class, 'destroy'])->name('master-pengguna.destroy');

        //Master-Session
        Route::get('/master-session', [LoginSessionController::class, 'index'])->name('master-session.index');
        Route::delete('/master-session/{user_id}', [LoginSessionController::class, 'destroy'])->name('master-session.destroy');

        // ========== DATA DOSEN ===========
        Route::prefix('dosen')->name('dosen.')->group(function () {

            // List & Modal Pilih User
            Route::get('/', [DosenController::class, 'index'])->name('index');

            // Create Flow (2 Steps)
            Route::post('/pilih-user', [DosenController::class, 'store'])->name('store');

            // Detail Tabs (gunakan route model binding)
            Route::get('/{dosen}/biodata', [DosenController::class, 'biodata'])->name('biodata');
            Route::get('/{dosen}/pendidikan', [DosenController::class, 'pendidikan'])->name('pendidikan');
            Route::get('/{dosen}/dokumen', [DosenController::class, 'dokumen'])->name('dokumen');
            Route::get('/{dosen}/jadwal', [DosenController::class, 'jadwal'])->name('jadwal');

            // Edit & Update
            Route::get('/{dosen}/edit', [DosenController::class, 'edit'])->name('edit');
            Route::put('/{dosen}', [DosenController::class, 'update'])->name('update');

            // Delete
            Route::delete('/{dosen}', [DosenController::class, 'destroy'])->name('destroy');

            // Pendidikan CRUD
            Route::post('/{dosen}/pendidikan', [PendidikanDosenController::class, 'store'])->name('pendidikan.store');
            Route::put('/{dosen}/pendidikan/{pendidikan}', [PendidikanDosenController::class, 'update'])->name('pendidikan.update');
            Route::delete('/{dosen}/pendidikan/{pendidikan}', [PendidikanDosenController::class, 'destroy'])->name('pendidikan.destroy');

            // Dokumen CRUD
            Route::post('/{dosen}/dokumen', [DokumenDosenController::class, 'store'])->name('dokumen.store');
            Route::delete('/{dosen}/dokumen/{dokumen}', [DokumenDosenController::class, 'destroy'])->name('dokumen.destroy');
        });

        // Data Fakultas
        Route::get('/fakultas', [FakultasController::class, 'index'])->name('fakultas.index');
        Route::post('/fakultas/store', [FakultasController::class, 'store'])->name('fakultas.store');
        Route::put('/fakultas/{fakultas}', [FakultasController::class, 'update'])->name('fakultas.update');
        Route::delete('/fakultas/{fakultas}', [FakultasController::class, 'destroy'])->name('fakultas.destroy');

        // Data Prodi
        Route::get('/prodi', [ProdiController::class, 'index'])->name('data-prodi.index');
        Route::post('/prodi/store', [ProdiController::class, 'store'])->name('data-prodi.store');
        Route::get('/prodi/{prodi}', [ProdiController::class, 'show'])->name('data-prodi.show');
        Route::put('/prodi/{prodi}', [ProdiController::class, 'update'])->name('data-prodi.update');
        Route::delete('/prodi/{prodi}', [ProdiController::class, 'destroy'])->name('data-prodi.destroy');

        Route::get('/profil', function () {
            $nim = Auth::user()->mahasiswa->nim ?? null;
            if (!$nim) {
                return redirect('/dashboard')->with('error', 'Data Mahasiswa tidak ditemukan');
            }
            return redirect()->route('mahasiswa.biodata', ['nim' => $nim]);
        })->name('profil.biodata');

        // 1. Data Utama & CRUD Dasar
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::post('/mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::get('/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
        Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
        Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

        // 2. Tab Biodata
        Route::get('/mahasiswa/{nim}/biodata', [MahasiswaController::class, 'biodata'])->name('mahasiswa.biodata');
        Route::put('/mahasiswa/{nim}/biodata', [MahasiswaController::class, 'updateBiodata'])->name('mahasiswa.biodata.update');

        // 3. Tab Pendidikan
        Route::get('/mahasiswa/{nim}/pendidikan', [MahasiswaController::class, 'pendidikan'])->name('mahasiswa.pendidikan');
        Route::post('/mahasiswa/{nim}/pendidikan', [MahasiswaController::class, 'storePendidikan'])->name('mahasiswa.pendidikan.store');
        Route::put('/mahasiswa/{nim}/pendidikan/{id}', [MahasiswaController::class, 'updatePendidikan'])->name('mahasiswa.pendidikan.update');
        Route::delete('/mahasiswa/{nim}/pendidikan/{id}', [MahasiswaController::class, 'destroyPendidikan'])->name('mahasiswa.pendidikan.destroy');
        Route::get('/mahasiswa/{nim}/pendidikan/{id}/edit', [MahasiswaController::class, 'editPendidikan'])->name('mahasiswa.pendidikan.edit');

        // 4. Tab Orang Tua
        Route::get('/mahasiswa/{nim}/orangtua', [MahasiswaController::class, 'dataortu'])->name('mahasiswa.dataortu');
        Route::put('/mahasiswa/{nim}/orangtua', [MahasiswaController::class, 'updateOrangtua'])->name('mahasiswa.orangtua.update');
        Route::post('/mahasiswa/{nim}/reset-ortu', [MahasiswaController::class, 'resetDataOrtu'])->name('mahasiswa.reset-ortu');

        // 5. Tab Dokumen
        Route::get('/mahasiswa/{nim}/dokumen', [MahasiswaController::class, 'dokumen'])->name('mahasiswa.dokumen');
        Route::post('/mahasiswa/{nim}/dokumen', [MahasiswaController::class, 'storeDokumen'])->name('mahasiswa.dokumen.store');
        Route::delete('/mahasiswa/{nim}/dokumen/{id}', [MahasiswaController::class, 'destroyDokumen'])->name('mahasiswa.dokumen.destroy');

        // 6. Tab Akademik
        Route::get('/mahasiswa/{nim}/akademik', [MahasiswaController::class, 'akademik'])->name('mahasiswa.akademik');
        Route::put('/mahasiswa/{nim}/akademik', [MahasiswaController::class, 'updateAkademik'])
            ->name('mahasiswa.akademik.update')
            ->middleware('role:admin');

        // 7. Tab Riwayat Kuliah
        Route::get('/mahasiswa/{nim}/riwayat-kuliah', [MahasiswaController::class, 'riwayatKuliah'])->name('mahasiswa.riwayat-kuliah');
        Route::post('/mahasiswa/{nim}/riwayat-kuliah', [MahasiswaController::class, 'storeRiwayatKuliah'])->name('mahasiswa.riwayat-kuliah.store');
        Route::delete('/mahasiswa/{nim}/riwayat-kuliah/{id}', [MahasiswaController::class, 'destroyRiwayatKuliah'])->name('mahasiswa.riwayat-kuliah.destroy');

        // 8. Tab Pembayaran
        Route::get('/mahasiswa/{nim}/pembayaran', [MahasiswaController::class, 'pembayaran'])->name('mahasiswa.pembayaran');
        Route::post('/mahasiswa/{nim}/pembayaran', [MahasiswaController::class, 'storePembayaran'])
            ->name('mahasiswa.pembayaran.store')
            ->middleware('role:admin,keuangan');
        Route::put('/mahasiswa/{nim}/pembayaran/{id}', [MahasiswaController::class, 'updatePembayaran'])
            ->name('mahasiswa.pembayaran.update')
            ->middleware('role:admin,keuangan');
        Route::delete('/mahasiswa/{nim}/pembayaran/{id}', [MahasiswaController::class, 'destroyPembayaran'])
            ->name('mahasiswa.pembayaran.destroy')
            ->middleware('role:admin,keuangan');

        // 9. Kelengkapan Dokumen (Hard/Soft File)
        Route::get('/mahasiswa/{nim}/kelengkapan-dokumen', [MahasiswaController::class, 'kelengkapanDokumen'])
            ->name('mahasiswa.kelengkapan-dokumen');
        Route::post('/mahasiswa/{nim}/kelengkapan-dokumen/upload', [MahasiswaController::class, 'uploadKelengkapanDokumen'])
            ->name('mahasiswa.kelengkapan-dokumen.upload');
        Route::post('/mahasiswa/{nim}/kelengkapan-dokumen/toggle', [MahasiswaController::class, 'toggleHardfileStatus'])
            ->name('mahasiswa.kelengkapan-dokumen.toggle');
        Route::delete('/mahasiswa/{nim}/kelengkapan-dokumen/{jenis}', [MahasiswaController::class, 'deleteKelengkapanDokumen'])
            ->name('mahasiswa.kelengkapan-dokumen.delete');

        // Data-Karyawan
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::post('/karyawan/tambah', [KaryawanController::class, 'redirectToForm'])->name('karyawan.redirect');
        Route::get('/karyawan/tambah/{id_users}', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('/karyawan/{karyawan}/detail', [KaryawanController::class, 'show'])->name('karyawan.show');
        Route::get('karyawan/{karyawan}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('karyawan/{karyawan}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('karyawan/{karyawan}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

        Route::middleware(['auth'])->group(function () {

            // Inventaris - Hanya bisa diakses oleh Role dengan permission 'inventaris'
            Route::middleware(['permission:inventaris,read'])->group(function () {
                Route::get('/inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
                Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
            });

            Route::middleware(['permission:inventaris,create'])->group(function () {
                Route::post('/inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
            });

            Route::middleware(['permission:inventaris,update'])->group(function () {
                Route::put('/inventaris/{id}', [InventarisController::class, 'update'])->name('inventaris.update');
            });

            Route::middleware(['permission:inventaris,delete'])->group(function () {
                Route::delete('/inventaris/{id}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');
            });
        });

        // Data Mata Kuliah
Route::get('/matakuliah', [MatkulController::class, 'index'])->name('matakuliah.index');
Route::get('/matakuliah/all-data', [MatkulController::class, 'allData'])->name('matakuliah.all-data'); // ✅ NEW
Route::post('/matakuliah/store', [MatkulController::class, 'store'])->name('matakuliah.store');
Route::get('/matakuliah/{matakuliah}', [MatkulController::class, 'show'])->name('matakuliah.show');
Route::put('/matakuliah/{matakuliah}', [MatkulController::class, 'update'])->name('matakuliah.update');
Route::delete('/matakuliah/{matakuliah}', [MatkulController::class, 'destroy'])->name('matakuliah.destroy');

        // Data Tahun Akademik
        Route::get('/tahun-akademik', [TahunAkademikController::class, 'index'])->name('tahun-akademik.index');
        Route::post('/tahun-akademik/store', [TahunAkademikController::class, 'store'])->name('tahun-akademik.store');
        Route::put('/tahun-akademik/{tahun_akademik}', [TahunAkademikController::class, 'update'])->name('tahun-akademik.update');
        Route::delete('/tahun-akademik/{tahun_akademik}', [TahunAkademikController::class, 'destroy'])->name('tahun-akademik.destroy');

        // Data Rombel
        Route::get('/rombel', [RombelController::class, 'index'])->name('rombel.index');
        Route::post('/rombel', [RombelController::class, 'store'])->name('rombel.store');
        Route::put('/rombel/{id}', [RombelController::class, 'update'])->name('rombel.update');
        Route::delete('/rombel/{id}', [RombelController::class, 'destroy'])->name('rombel.destroy');
        Route::get('rombel/{id}/detail', [RombelController::class, 'detail'])->name('rombel.detail');
        Route::get('rombel/{id}/tambah-mahasiswa', [RombelController::class, 'tambahMahasiswa'])->name('rombel.tambahMahasiswa');
        Route::post('rombel/{id}/tambah-mahasiswa', [RombelController::class, 'storeMahasiswa'])->name('rombel.storeMahasiswa');

        // Data Ruangan
        Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
        Route::get('/ruangan/create', [RuanganController::class, 'create'])->name('ruangan.create');
        Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
        Route::get('/ruangan/{ruangan}/edit', [RuanganController::class, 'edit'])->name('ruangan.edit');
        Route::put('/ruangan/{ruangan}', [RuanganController::class, 'update'])->name('ruangan.update');
        Route::delete('/ruangan/{ruangan}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');
        Route::resource('ruangan', RuanganController::class);

        // Data Jadwal
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
        Route::post('/jadwal/store', [JadwalController::class, 'store'])->name('jadwal.store');
        Route::get('/jadwal/{jadwal}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
        Route::put('/jadwal/{jadwal}', [JadwalController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

        // ========== MODUL NILAI ==========
        Route::prefix('nilai')->name('nilai.')->group(function () {
            Route::get('/', [NilaiController::class, 'index'])->name('index');
            Route::get('/input-massal', [NilaiController::class, 'inputMassal'])->name('input-massal');
            Route::post('/store-massal', [NilaiController::class, 'storeMassal'])->name('store-massal');

            // Fitur Publish
            Route::post('/publish-massal', [NilaiController::class, 'publishMassal'])->name('publish-massal');
            Route::post('/{id}/publish', [NilaiController::class, 'publish'])->name('publish');

            // Sisanya (Edit, Update, Destroy) dihandle resource
            Route::resource('', NilaiController::class)->except(['index'])->parameters(['' => 'nilai']);
        });

        // Data-KRS
        Route::get('/krs', [KrsController::class, 'index'])->name('krs.index');
        Route::post('/krs/tambah', [KrsController::class, 'redirectToForm'])->name('krs.redirect');
        Route::get('/krs/tambah/{id_rombel}', [KrsController::class, 'create'])->name('krs.create');
        Route::post('/krs/store', [KrsController::class, 'store'])->name('krs.store');
        Route::get('/krs/{krs}/edit', [KrsController::class, 'edit'])->name('krs.edit');
        Route::put('/krs/{krs}', [KrsController::class, 'update'])->name('krs.update');
        Route::delete('/krs/{krs}', [KrsController::class, 'destroy'])->name('krs.destroy');

        // ========== PLACEHOLDER ROUTES (Untuk menu yang belum diimplementasi) ==========
        Route::middleware(['auth'])->group(function () {

            // Keuangan (Role 3)
            Route::get('/komponen-biaya', function () {
                return view('placeholder', ['title' => 'Komponen Biaya']);
            })->name('komponen-biaya.index');

            Route::get('/tagihan', function () {
                return view('placeholder', ['title' => 'Tagihan Mahasiswa']);
            })->name('tagihan.index');

            Route::get('/pembayaran', function () {
                return view('placeholder', ['title' => 'Validasi Pembayaran']);
            })->name('pembayaran.index');

            // Inventaris (Role 9)
            Route::get('/inventaris', function () {
                return view('placeholder', ['title' => 'Inventaris']);
            })->name('inventaris.index');

            Route::get('/arsip', function () {
                return view('placeholder', ['title' => 'Arsip Dokumen']);
            })->name('arsip.index');

            // Laporan (Role 4, 5, 6)
            Route::get('/laporan/eksekutif', function () {
                return view('placeholder', ['title' => 'Dashboard Eksekutif']);
            })->name('laporan.eksekutif');

            Route::get('/laporan/fakultas', function () {
                return view('placeholder', ['title' => 'Laporan Fakultas']);
            })->name('laporan.fakultas');

            Route::get('/laporan/prodi', function () {
                return view('placeholder', ['title' => 'Laporan Prodi']);
            })->name('laporan.prodi');
        });
    });

    require __DIR__ . '/auth.php';
