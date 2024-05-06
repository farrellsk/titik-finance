<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class log extends Model
{
    use HasFactory;

    protected $table = 'log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'notes',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
