<div>
    <div class="card">
        <div class="card-header">
            <h2>Insiden {{ $insiden->nama_insiden }}</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h3><b>KETERANGAN</b></h3>
                    <p>{{ $insiden->keterangan }}</p>
                </div>
                <div class="col-md-6">
                    <h3><b>LOKASI</b></h3>
                    <p>{{ $insiden->lokasi }}</p>
                </div>
                <hr>
                <div class="col-md-12">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3>DATA PETUGAS YANG BERTUGAS DI INSIDEN </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <x-table.table searching>
                                        <thead>
                                            <tr>
                                                <th width='50px'>No</th>
                                                <th>Nama Petugas</th>
                                                <th>No Seri Perangkat</th>
                                                <th>Status</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($insiden->insidenDetails as $index => $detail)
                                                @foreach ($detail->logPetugas as $logPetugas)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            @if ($logPetugas->petugas)
                                                                {{ $logPetugas->petugas->nama }}
                                                            @else
                                                                Data petugas tidak tersedia
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($logPetugas->perangkat)
                                                                {{ $logPetugas->perangkat->no_seri }}
                                                            @else
                                                                Data perangkat tidak tersedia
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($logPetugas->status == 1)
                                                                <button class="btn btn-success btn-sm">Aktif</button>
                                                            @else
                                                                <button class="btn btn-danger btn-sm">Tidak
                                                                    Aktif</button>
                                                            @endif
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            @empty
                                                <tr class="text-center">
                                                    <td colspan="5">Tidak ada data petugas untuk ditampilkan!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </x-table.table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
