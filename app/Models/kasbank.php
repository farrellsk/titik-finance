<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kasbank extends Model
{
    use HasFactory;
    protected $table = 'akun_kas';
    protected $primaryKey = 'id_kas';
    protected $fillable = [
        'kd_kas',
        'payment',
        'nama_akun',
        'saldo_awal',
        'saldo_akhir',
        'kategori',
        'nama_rekening',
        'no_rekening'
    ];
}
