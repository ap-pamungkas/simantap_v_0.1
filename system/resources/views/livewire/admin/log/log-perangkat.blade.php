<div>
    <x-alerts.dispatch-message />
    <div class="card card-outline card-secondary">
      <div class="card-header">

         <div class="card-title">
           <h2>Log Perangkat</h2>
         </div>

      </div>
      <div class="card-body">
            <div  wire:poll.3s class="row">
                <div class="table-responsive">
                    <x-table.table >
                        <thead>
                            <tr>
                                <th width='50px'>No</th>

                                <th >
                                  No Seri
                                </th>
                                <th >
                                  status
                                </th>
                                <th>Kualitas Udara</th>



                                <th>latitude</th>
                                <th>longitude</th>
                                 <th >
                                    Waktu
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                          @forelse ($logs as $log)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $log->perangkat->no_seri }}</td>
                                <td>{{ $log->status }}</td>
                                @php
                                    $ambang_batas = 800; // kamu bisa ganti sesuai ambang MQ-135
                                @endphp

                                <td style="color: {{ $log->kualitas_udara > $ambang_batas ? 'red' : 'black' }}">
                                    {{ $log->kualitas_udara ?? '-' }}
                                </td>

                                <td>{{ $log->latitude ?? '-' }}</td>
                                <td>{{ $log->longitude ?? '-' }}</td>
                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">Belum ada data log perangkat.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </x-table.table>
                    {{ $logs->links() }}
                </div>
            </div>
      </div>
    </div>





 </div>






