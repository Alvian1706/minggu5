<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: penyewas
     * Digunakan oleh: PenyewaController (Eloquent ORM)
     * Bergantung pada: kamars (FK), karyawans (FK)
     * HARUS dijalankan SETELAH migration kamars dan karyawans
     */
    public function up(): void
    {
        Schema::create('penyewas', function (Blueprint $table) {
            $table->id();

            // Data Pribadi Penyewa
            $table->string('nama_penyewa', 100)->comment('Nama lengkap penyewa');
            $table->string('no_ktp', 16)->unique()->comment('Nomor KTP/NIK (16 digit)');
            $table->string('no_telepon', 15)->comment('Nomor telepon aktif');
            $table->string('email', 100)->nullable()->comment('Email penyewa (opsional)');

            // Relasi ke Kamar
            $table->foreignId('kamar_id')
                  ->constrained('kamars')
                  ->onUpdate('cascade')
                  ->onDelete('restrict')
                  ->comment('FK ke tabel kamars');

            // Relasi ke Karyawan PIC
            $table->foreignId('karyawan_id')
                  ->constrained('karyawans')
                  ->onUpdate('cascade')
                  ->onDelete('restrict')
                  ->comment('FK ke tabel karyawans, karyawan penanggung jawab');

            // Data Sewa
            $table->date('tanggal_masuk')->comment('Tanggal mulai menempati kamar');
            $table->date('tanggal_keluar')->comment('Tanggal kontrak berakhir');
            $table->integer('lama_sewa')->comment('Durasi sewa dalam bulan');
            $table->decimal('total_harga', 14, 2)->comment('Total biaya sewa seluruh periode');
            $table->decimal('uang_deposit', 12, 2)->default(0)->comment('Uang deposit/jaminan');
            $table->enum('status', ['aktif', 'selesai'])
                  ->default('aktif')
                  ->comment('Status kontrak penyewa');
            $table->text('catatan')->nullable()->comment('Catatan tambahan');

            $table->timestamps();

            // Index untuk performa query
            $table->index('status');
            $table->index('tanggal_keluar');
            $table->index(['kamar_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewas');
    }
};