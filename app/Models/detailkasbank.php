<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailkasbank extends Model
{
    use HasFactory;
    protected $table = 'detail_kasbank';
    protected $primaryKey = 'id';
    protected $fillable = [
        'payment',
        'id_transaksi',
        'tanggal',
        'kategori',
        'pelanggan',
        'tanggal_tempo',
        'total'
    ];

    public function kasbank(){
        return $this->belongsTo(kasbank::class, 'payment', 'id_kas');
    }

    public function pelanggan(){
        return $this->belongsTo(kontak::class, 'pelanggan', 'id_kontak');
    }
}
