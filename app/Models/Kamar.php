<?php
// ════════════════════════════════════════════
// app/Models/Kamar.php
// ════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table    = 'kamars';
    protected $fillable = ['nomor_kamar','tipe_kamar','harga_bulan','luas_kamar','fasilitas','status'];
    protected $casts    = ['harga_bulan'=>'decimal:2','luas_kamar'=>'decimal:2'];

    // Relasi 1-N ke Penyewa
    public function penyewas() { return $this->hasMany(Penyewa::class, 'kamar_id'); }
    public function penyewaAktif() { return $this->hasOne(Penyewa::class,'kamar_id')->where('status','aktif'); }

    // Scope
    public function scopeTersedia($q) { return $q->where('status','tersedia'); }

    // Accessor
    public function getHargaFormatAttribute() {
        return 'Rp '.number_format($this->harga_bulan,0,',','.');
    }
}

