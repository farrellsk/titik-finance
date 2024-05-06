<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class akunBiaya extends Model
{
    use HasFactory;

    protected $table = 'akun_biaya';
    protected $primaryKey = 'id_akun_biaya';
    protected $fillable = [
        'kd_akun_biaya',
        'id_kategori_biaya',
        'nama_akun_biaya',
        'nama_penerima',
        'metode_pembayaran',
        'kategori_akun_biaya',
        'jumlah',
        'created_at'
    ];

    public function kategori()
    {
        return $this->belongsTo(kategoribiaya::class, 'id_kategori_biaya', 'id_kategori_biaya');
    }
    
    public function kasbank()
    {
        return $this->belongsTo(kasbank::class, 'metode_pembayaran', 'nama_akun');
    }
}
