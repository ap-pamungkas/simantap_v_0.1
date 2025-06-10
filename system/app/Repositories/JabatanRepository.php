<?php

namespace App\Repositories;

use App\Models\Jabatan;
use App\Services\LogActivityService;
use App\Traits\QueryHelper;

class JabatanRepository extends Repository
{
    use QueryHelper;

    protected $logActivityService;


    public function __construct()
    {
        $this->logActivityService = new LogActivityService;
    }

    public function getJabatan(?string $search, int $perPage, ?string $sortField = null, ?string $sortDirection = null)
    {
        $query = Jabatan::query();

        if (!empty($search)) {
            $query->where('nama_jabatan', 'like', "%{$search}%");
        }

        $this->applySorting($query, $sortField ?? $this->sortField, $sortDirection ?? $this->sortDirection);

        return $this->paginateResults($query, $perPage);
    }


    public function createJabatan(array $data): Jabatan
    {

        $jabatan = Jabatan::create($data);

        $this->logActivityService->logActivity(
            $jabatan,
            'create',
            [
                $jabatan['nama_jabatan'] => $jabatan->nama_jabatan,
            ],
            'nama_jabatan'
        );

        return $jabatan;
    }


    public function updateJabatan(int $id, array $data): Jabatan
    {

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($data);

        $this->logActivityService->logActivity(
            $jabatan,
            'update',
            [
                $jabatan['nama_jabatan'] => $jabatan->nama_jabatan,
            ],
            'nama_jabatan'
        );
        return $jabatan;
    }


    public function deleteJabatan(int $id): bool
    {
        $jabatan = Jabatan::findOrFail($id);

        $this->logActivityService->logActivity(
            $jabatan,
            'delete',
            [
                $jabatan['nama_jabatan'] => $jabatan->nama_jabatan,
                $jabatan['id'] => $jabatan->id,
            ],
            'nama_jabatan, id'
        );
        return $jabatan->delete();
    }
}
