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
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id');
    }

    public  function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'id_perangkat', 'id');
    }


    public function logPerangkat()
{
    return $this->hasManyThrough(LogPerangkat::class, Perangkat::class, 'id', 'perangkat_id', 'id_perangkat', 'id');
}
}
