<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_produk',
        'harga_jual',
        'harga_beli',
        'kode_produk',
        'kategori_produk',
        'total_stok',
        'minimun_stok',
        'unit'
    ];

    public function kategori(){
        return $this->belongsTo(kategoriProduk::class, 'kategori_produk', 'id');
    }
}
