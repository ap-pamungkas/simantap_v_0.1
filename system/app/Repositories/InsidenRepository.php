<?php

namespace App\Repositories;

use App\Models\Insiden;
use App\Repositories\Repository;
use App\Traits\QueryHelper;

class InsidenRepository extends Repository
{
    use QueryHelper;

    public function getInsiden($search, $perPage, $sortField = null, $sortDirection = null){
        return Insiden::where(function($query) use ($search) {
        $query->where('nama_insiden', 'like', "%{$search}%")
              ->orWhere('lokasi', 'like', "%{$search}%")
              ->orWhere('keterangan', 'like', "%{$search}%");
    })
    ->orderBy($sortField ?? 'id', $sortDirection ?? 'asc')
    ->paginate($perPage);
    }   
    
public function getInsidenById($id,$perPage, $sortField = null, $sortDirection = null ){
    return Insiden::with(['insidenDetails', 'insidenDetails.logPetugas', 'insidenDetails.petugas', 'insidenDetails.perangkat', 'insidenDetails.logPerangkat'])
        ->where('id', $id)
        ->first();
}
    

}
