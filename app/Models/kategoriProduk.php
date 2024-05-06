<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'produk_kategori';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_produk_kategori',
    ];
}
