<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kontak extends Model
{
    use HasFactory;

    protected $table = 'kontak';
    protected $primaryKey = 'id_kontak';
    protected $fillable = [
        'kode_kontak',
        'id_user',
        'nama_kontak',
        'tipe_kontak',
        'no_telp',
        'alamat',
    ];
}
