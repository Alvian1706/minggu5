<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: karyawans
     * Digunakan oleh: KaryawanController (Eloquent ORM)
     * Dibuat SEBELUM penyewas karena penyewas butuh foreign key ke karyawans
     */
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->comment('Nama lengkap karyawan');
            $table->enum('jabatan', ['admin', 'teknisi', 'kebersihan', 'keamanan'])
                  ->comment('Posisi/jabatan karyawan');
            $table->string('no_telepon', 15)->comment('Nomor telepon aktif');
            $table->string('email', 100)->nullable()->unique()->comment('Email (opsional, unik)');
            $table->text('alamat')->nullable()->comment('Alamat lengkap karyawan');
            $table->decimal('gaji', 12, 2)->comment('Gaji pokok per bulan');
            $table->date('tanggal_masuk')->comment('Tanggal mulai bekerja');
            $table->enum('status_kerja', ['aktif', 'nonaktif'])
                  ->default('aktif')
                  ->comment('Status kerja karyawan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};