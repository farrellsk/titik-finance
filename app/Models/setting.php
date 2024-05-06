<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class setting extends Model
{
    use HasFactory;
    protected $table = 'setting';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'email',
        'no_hp',
        'logo',
        'image_ttd',
        'nama_ttd',
        'kecamatan',
        'kota'
    ];
}
