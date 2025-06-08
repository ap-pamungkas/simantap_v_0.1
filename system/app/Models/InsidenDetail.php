<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsidenDetail extends Model
{
    protected $table = "insiden_detail";

    public function logPetugas()
    {
        return $this->belongsTo(LogPetugas::class, 'LogPetugas_id', 'id');
    }
}
