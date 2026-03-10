<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;

    protected $table    = 'karyawans';
    protected $fillable = ['nama','jabatan','no_telepon','email','alamat','gaji','tanggal_masuk','status_kerja'];
    protected $casts    = ['tanggal_masuk'=>'date','gaji'=>'decimal:2'];

    // Relasi 1-N ke Penyewa
    public function penyewas() { return $this->hasMany(Penyewa::class, 'karyawan_id'); }

    // Scope
    public function scopeAktif($q) { return $q->where('status_kerja','aktif'); }

    // Accessor
    public function getGajiFormatAttribute() {
        return 'Rp '.number_format($this->gaji,0,',','.');
    }
    public function getJabatanBadgeColorAttribute() {
        return match($this->jabatan) {
            'admin'      => '#1d4ed8',
            'teknisi'    => '#0891b2',
            'kebersihan' => '#16a34a',
            'keamanan'   => '#b45309',
            default      => '#6b7280',
        };
    }
}
