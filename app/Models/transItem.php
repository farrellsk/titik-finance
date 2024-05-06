<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transItem extends Model
{
    use HasFactory;
    protected $table = 'transaction_item';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_transaksi',
        'id_produk',
        'id_kontak',
        'qty',
        'amount',
        'total',
        'notes'
    ];

    public function belongsToTransItem(){
        return $this->belongsTo(transaksi::class, 'id_transaksi','id');
    }

    public function produk(){
        return $this->belongsTo(produk::class, 'id_produk', 'id');
    }

    public function kontak(){
        return $this->belongsTo(kontak::class, 'id_kontak', 'id_kontak');
    }


}
