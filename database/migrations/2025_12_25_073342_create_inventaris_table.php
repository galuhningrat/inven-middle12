<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inventaris')) {
            Schema::create('inventaris', function (Blueprint $table) {
                $table->id();
                $table->string('kode_barang', 50)->unique();
                $table->string('nama_barang', 150);
                $table->enum('kategori', ['Elektronik', 'Furniture', 'Kendaraan', 'Alat Tulis', 'Lainnya']);
                $table->integer('jumlah')->default(1);
                $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat']);
                $table->date('tanggal_perolehan');
                $table->decimal('harga_perolehan', 15, 2)->nullable();
                $table->string('lokasi', 100);
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
