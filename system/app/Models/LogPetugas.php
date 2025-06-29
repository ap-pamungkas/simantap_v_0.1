<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPetugas extends Model
{
    protected $table = 'log_petugas';

   protected $casts = [
        'id_perangkat' => 'string',
    ];
    protected $fillable = [
        'id_perangkat',
        'id_petugas',
    ];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id', 'id');
    }

    public  function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'perangkat_id', 'id');
    }


    public function logPerangkat()
{
    return $this->hasManyThrough(LogPerangkat::class, Perangkat::class, 'id', 'perangkat_id', 'perangkat_id', 'id');
}

public function insidenDetail()
{
    return $this->hasMany(InsidenDetail::class, 'LogPetugas_id', 'id');
}

}