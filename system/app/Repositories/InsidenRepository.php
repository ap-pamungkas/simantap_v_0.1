<?php

namespace App\Repositories;

use App\Models\Insiden;
use App\Models\InsidenDetail;
use App\Repositories\Repository;
use App\Traits\QueryHelper;

class InsidenRepository extends Repository
{
    use QueryHelper;
    /**
     * Retrieve paginated and sorted Insiden records with optional search.
     *
     * @param string|null $search
     * @param int $perPage
     * @param string|null $sortField
     * @param string|null $sortDirection
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */


    public function getInsiden($search, $perPage, $sortField = null, $sortDirection = null)
    {
        return Insiden::where(function ($query) use ($search) {
            $query->where('nama_insiden', 'like', "%{$search}%")
                ->orWhere('lokasi', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%");
        })
            ->orderBy($sortField ?? 'id', $sortDirection ?? 'asc')
            ->paginate($perPage);
    }



    public function getInsidenById($id)
    {
        return Insiden::with(['insidenDetails', 'insidenDetails.logPetugas', 'insidenDetails.petugas', 'insidenDetails.perangkat', 'insidenDetails.logPerangkat'])
            ->where('id', $id)
            ->first();
    }


    /**
     * Create a new Insiden record and log the activity.
     * @param array $data
     *
     */


    public function createInsiden($data)
    {
        $insiden = Insiden::create($data);

        if ($insiden) {
            foreach ($data['insidenDetails'] as $insidenDetail) {
                $insidenDetail['insiden_id'] = $insiden->id;
                InsidenDetail::create($insidenDetail);
            }

            return $insiden;
        }

        return null;
    }
}
