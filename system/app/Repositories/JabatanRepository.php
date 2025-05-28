<?php

namespace  App\Repositories;

use App\Models\Jabatan;
use App\Traits\QueryHelper;

class JabatanRepository extends Repository{


    use QueryHelper;

    public function getJabatan($search, $perPage, $sortField = null, $sortDirection = null)
    {
        $query = Jabatan::query();

        // Apply search filter if provided
        if (!empty($search)) {
            $query->where('nama_jabatan', 'like', "%{$search}%");
        }

        // Use the trait methods for sorting and pagination
        $this->applySorting($query, $sortField ?? $this->sortField, $sortDirection ?? $this->sortDirection);

        return $this->paginateResults($query, $perPage);
    }
    public function createJabatan($data){
        return  Jabatan::create($data);
    }

    public function  updatejabatan($id=null , $data){
        return Jabatan::where('id', $id)->update($data);

    }

    public function deleteJabatan($id)
    {
        return Jabatan::destroy($id);
    }
}
