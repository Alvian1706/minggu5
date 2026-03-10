<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Penyewa extends Model
{
    use HasFactory;

    protected $table    = 'penyewas';
    protected $fillable = [
        'nama_penyewa','no_ktp','no_telepon','email',
        'kamar_id','karyawan_id','tanggal_masuk','tanggal_keluar',
        'lama_sewa','total_harga','uang_deposit','status','catatan',
    ];
    protected $casts = [
        'tanggal_masuk'  => 'date',
        'tanggal_keluar' => 'date',
        'total_harga'    => 'decimal:2',
        'uang_deposit'   => 'decimal:2',
    ];

    // Relasi belongsTo
    public function kamar()    { return $this->belongsTo(Kamar::class,    'kamar_id'); }
    public function karyawan() { return $this->belongsTo(Karyawan::class, 'karyawan_id'); }

    // Scope
    public function scopeAktif($q)            { return $q->where('status','aktif'); }
    public function scopeSegeraHabis($q, $hr=7) {
        return $q->where('status','aktif')
            ->whereDate('tanggal_keluar','<=',now()->addDays($hr))
            ->whereDate('tanggal_keluar','>=',now());
    }

    // Accessor sisa hari
    public function getSisaHariAttribute() {
        if ($this->status !== 'aktif') return 0;
        return max(0, now()->diffInDays(Carbon::parse($this->tanggal_keluar), false));
    }

    // Accessor format harga
    public function getTotalHargaFormatAttribute() {
        return 'Rp '.number_format($this->total_harga,0,',','.');
    }
}

