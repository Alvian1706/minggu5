<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel: kamars
     * Digunakan oleh: KamarController (Query Builder)
     */
    public function up(): void
    {
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kamar', 10)->unique()->comment('Nomor unik kamar, contoh: A-101');
            $table->enum('tipe_kamar', ['standar', 'deluxe', 'vip'])->comment('Tipe/kelas kamar');
            $table->decimal('harga_bulan', 12, 2)->comment('Harga sewa per bulan dalam Rupiah');
            $table->decimal('luas_kamar', 6, 2)->comment('Luas kamar dalam meter persegi');
            $table->text('fasilitas')->nullable()->comment('Daftar fasilitas kamar');
            $table->enum('status', ['tersedia', 'terisi', 'perbaikan'])
                  ->default('tersedia')
                  ->comment('Status ketersediaan kamar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};