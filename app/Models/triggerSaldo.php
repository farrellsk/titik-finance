<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class triggerSaldo extends Model
{
    use HasFactory;
    protected $table = 'update_saldo_kasbank_histories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_item',
        'id_transaksi',
        'saldo_sebelumnya',
        'saldo_setelahnya',
        'jenis_transaksi',
        'id_mutasi'
    ];

}
