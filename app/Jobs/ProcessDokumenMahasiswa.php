<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\DokumenMahasiswa;

class ProcessDokumenMahasiswa implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $mahasiswaId;

    public function __construct($filePath, $mahasiswaId)
    {
        $this->filePath = $filePath;
        $this->mahasiswaId = $mahasiswaId;
    }

    public function handle()
    {
        // Logika pengolahan dokumen, misalnya mencatat ke database dokumen_mahasiswa
        DokumenMahasiswa::create([
            'id_mahasiswa' => $this->mahasiswaId,
            'file_path' => $this->filePath,
            'nama_dokumen' => 'Upload Sistem',
            'kategori' => 'Umum'
        ]);
    }
}
