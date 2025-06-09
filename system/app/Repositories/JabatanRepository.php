<?php

namespace App\Repositories;

use App\Models\Jabatan;
use App\Traits\QueryHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Activitylog\Facades\Activity;

class JabatanRepository extends Repository
{
    use QueryHelper;

    /**
     * Retrieve paginated and sorted Jabatan records with optional search.
     *
     * @param string|null $search
     * @param int $perPage
     * @param string|null $sortField
     * @param string|null $sortDirection
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getJabatan(?string $search, int $perPage, ?string $sortField = null, ?string $sortDirection = null)
    {
        $query = Jabatan::query();

        if (!empty($search)) {
            $query->where('nama_jabatan', 'like', "%{$search}%");
        }

        $this->applySorting($query, $sortField ?? $this->sortField, $sortDirection ?? $this->sortDirection);

        return $this->paginateResults($query, $perPage);
    }

    /**
     * Create a new Jabatan record and log the activity.
     *
     * @param array $data
     * @return Jabatan
     * @throws \InvalidArgumentException
     */
    public function createJabatan(array $data): Jabatan
    {
        $this->validateData($data);
        $jabatan = Jabatan::create($data);

        $this->logActivity($jabatan, 'created', [
            'nama_jabatan_baru' => $jabatan->nama_jabatan,
        ]);

        return $jabatan;
    }

    /**
     * Update an existing Jabatan record and log the activity.
     *
     * @param int $id
     * @param array $data
     * @return Jabatan
     * @throws ModelNotFoundException
     * @throws \InvalidArgumentException
     */
    public function updateJabatan(int $id, array $data): Jabatan
    {
        $this->validateData($data);
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($data);

        $this->logActivity($jabatan, 'updated menjadi ', [

            'nama_jabatan_baru' => $jabatan->nama_jabatan,
        ]);

        return $jabatan;
    }

    /**
     * Delete a Jabatan record and log the activity.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function deleteJabatan(int $id): bool
    {
        $jabatan = Jabatan::findOrFail($id);

        $this->logActivity($jabatan, 'deleted', [
            'nama_jabatan' => $jabatan->nama_jabatan,
        ]);

        return $jabatan->delete();
    }

    /**
     * Log activity for a Jabatan record.
     *
     * @param Jabatan $jabatan
     * @param string $action
     * @param array $additionalProperties
     * @return void
     */
    private function logActivity(Jabatan $jabatan, string $action, array $additionalProperties = []): void
    {
        $properties = array_merge([
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ], $additionalProperties);

        Activity::performedOn($jabatan)
            ->withProperties($properties)
            ->log("Jabatan \"{$jabatan->nama_jabatan}\" telah berhasil di{$action}.");
    }

    /**
     * Validate input data for Jabatan creation/update.
     *
     * @param array $data
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validateData(array $data): void
    {
        if (empty($data['nama_jabatan'])) {
            throw new \InvalidArgumentException('Nama jabatan is required.');
        }
    }
}
