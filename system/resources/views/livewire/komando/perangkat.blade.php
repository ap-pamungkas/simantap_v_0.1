<div>

    <div class="card card-outline card-secondary">
        <div class="card-header">
            {{-- <button class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#tambah">
            <i class="bi bi-plus"></i>
            <span>Tambah Data</span>
        </button> --}}
            <div class="card-title">
                <h2 class="text-theme"> DATA PERANGKAT</h2>
            </div>

        </div>
        <div class="card-body">
            <div wire:poll.1s class="row">
                <div class="table-responsive">
                    <x-table.table searching>
                        <thead>
                            <tr>
                                <th width='50px'>No</th>
                                <th wire:click="sortBy('no_seri')" style="cursor: pointer;">
                                    No Seri
                                    @if ($sortField === 'no_seri')
                                        <i class="bi bi-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>
                                    Qr Code
                                </th>
                                <th>Status</th>
                                <th>
                                    Kondisi
                                </th>


                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list_devices as $index => $devices)
                                <tr>
                                    <td>{{ $index + 1 + ($list_devices->currentPage() - 1) * $list_devices->perPage() }}
                                    </td>
                                    <td>{{ $devices->no_seri }}</td>
                                    <td><img width="100px"
                                            src="{{ url('system/storage/app/public/' . $devices->qr_code) }}"
                                            alt=""></td>
                                    <td>
                                        @if ($devices->status == 'Aktif')
                                            <button class="btn btn-success btn-sm">Aktif</button>
                                        @else
                                            <button class="btn btn-danger btn-sm">Tidak Aktif</button>
                                        @endif
                                    </td>

                                    <td>{{ $devices->kondisi }}</td>
                                    <td>
                                        <button class="btn btn-secondary btn-sm"
                                            wire:click="updateConditions({{ $devices->id }})" data-bs-toggle="modal"
                                            data-bs-target="#tambah">
                                            <i class="bi bi-pencil-square"></i> Ubah Kondisi
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#hapus{{ $devices->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>


                                <!-- Modal Hapus Data -->
                                <x-modals.modalhapus id="hapus{{ $devices->id }}"
                                    click="deleteData({{ $devices->id }})" />
                            @empty
                                <tr class="text-center">
                                    <td colspan="6">Tidak ada data untuk ditampilkan!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </x-table.table>
                    {{ $list_devices->links() }}
                </div>
            </div>
        </div>
    </div>

    

</div>
