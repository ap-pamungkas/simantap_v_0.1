<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insiden extends Model
{
    protected $table = "insiden";

    protected $fillable = [
        'nama_insiden',
        'lokasi',
        'keterangan',
    ];
    public function insidenDetails():HasMany
    {
        return $this->hasMany(InsidenDetail::class, 'insiden_id', 'id');
    }
}
