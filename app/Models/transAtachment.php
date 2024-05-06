<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transAtachment extends Model
{
    use HasFactory;
    protected $table = 'transaction_attachment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_transaksi',
        'url',
    ];

    public function belongsToTransAtachment(){
        return $this->belongsTo(transaksi::class, 'id_transaksi', 'id');
    }
}
