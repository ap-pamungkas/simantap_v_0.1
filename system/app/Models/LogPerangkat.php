<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPerangkat extends Model
{
    protected $table = 'log_perangkat';
    protected $fillable = [
        'perangkat_id',
        'latitude',
        'longitude',
        'kualitas_udara',
        'suhu',
        'status',
    ];


    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'perangkat_id', 'id');
    }

}
