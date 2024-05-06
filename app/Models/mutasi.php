<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mutasi extends Model
{
    use HasFactory;

    protected $table = 'mutasi';
    protected $primaryKey = 'id';
    protected $fillable = [
        'akun_asal',
        'akun_tujuan',
        'jumlah_mutasi',
        'biaya_layanan',
        'keterangan',
        'lampiran',
        'waktu',
    ];

    public function akunAsal()
{
    return $this->belongsTo(kasbank::class, 'akun_asal', 'id_kas');
}

public function akunTujuan()
{
    return $this->belongsTo(kasbank::class, 'akun_tujuan', 'id_kas');
}

}
