<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class triggerBiaya extends Model
{
    use HasFactory;
    protected $table = 'view_update_biaya_history';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_tbiaya',
        'saldo_sebelumnya',
        'saldo_setelahnya',
        'waktu',
    ];
}
