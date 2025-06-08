<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsidenDetail extends Model
{
    protected $table = "insiden_detail";

    protected $fillable = [
        'insiden_id',
        'LogPetugas_id',    
    ];

    // Get related LogPetugas records
    public function logPetugas()
    {
        return $this->hasMany(LogPetugas::class, 'id', 'LogPetugas_id');
    }

    // Get Petugas through LogPetugas relationship
    public function petugas()
    {
        return $this->hasManyThrough(
            Petugas::class,
            LogPetugas::class,
            'id', // Foreign key on LogPetugas table
            'id', // Foreign key on Petugas table
            'LogPetugas_id', // Local key on InsidenDetail table
            'petugas_id' // Local key on LogPetugas table
        );
    }

    // Get Perangkat through LogPetugas relationship
    public function perangkat()
    {
        return $this->hasManyThrough(
            Perangkat::class,
            LogPetugas::class,
            'id',
            'id',
            'LogPetugas_id',
            'perangkat_id'
        );
    }

    // Get LogPerangkat records associated with the Perangkat
    public function logPerangkat()
    {
        return $this->hasManyThrough(
            LogPerangkat::class,
            LogPetugas::class,
            'id',
            'perangkat_id',
            'LogPetugas_id',
            'perangkat_id'
        );
    }
}
