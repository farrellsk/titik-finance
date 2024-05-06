<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaction';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tipe',
        'created_by',
        'payment_via',
        'status',
        'notes',
        'contact_id',
        'do_date',
        'tanggal'
    ];

    protected $appends = ['total'];

    public function getTotalAttribute()
    {
        $data = transItem::where('id_transaksi',$this->id)->sum('total');
        return 'Rp ' . number_format($data, 0, ',', '.');
    }

    public function hasManyTransaksi(){
        return $this->hasMany(transItem::class, 'id_transaksi', 'id');
    }

    public function triggerSaldo(){
        return $this->hasMany(triggerSaldo::class, 'id_kasbank', 'id');
    }

    public function hasManyTrans_Attachment(){
        return $this->hasMany(transAtachment::class, 'id_transaksi', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contact(){
        return $this->belongsTo(kontak::class, 'contact_id', 'id_kontak');
    }

    public function kasbank(){
        return $this->belongsTo(kasbank::class, 'payment_via' ,'id_kas'  );
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $model->tanggal = \Illuminate\Support\Carbon::parse($model->tanggal)->setTimeFrom(\Illuminate\Support\Carbon::now());
    });
}

    
    public function akunBiaya()
    {
        return $this->belongsTo(akunBiaya::class, 'nama_kontak', 'nama_penerima');
    }



}
