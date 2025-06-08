<div >
    

    <div class="card card-outline card-secondary">
        <div class="card-header">
         
           <div class="card-title">
             <h2 class="text-theme"> DATA insiden</h2>
           </div>
  
        </div>
        <div class="card-body">
              <div class="row">
                  <div class="table-responsive">
                      <x-table.table searching>
                          <thead>
                              <tr>
                                  <th width='50px'>No</th>
                                  <th wire:click="sortBy('nama')" style="cursor: pointer;">
                                      Nama Insiden
                                      @if($sortField === 'nama')
                                          <i class="bi bi-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                      @endif
                                  </th>
                                  <th >
                                      Keterengan
                                  </th>
                                  <th >Lokasi</th>
                                  <th >Aksi</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse ($list_insidens as $index => $insiden)
                                  <tr>
                                      <td>{{ $index + 1 + (($list_insidens->currentPage() - 1) * $list_insidens->perPage()) }}</td>
                                      <td>{{ $insiden->nama_insiden}}</td>
                                      <td>{{ $insiden->keterangan }}</td>
                                      <td>{{ $insiden->lokasi }}</td>
                                      <td>
                                         
                                      <a href="{{ route('komando.insiden.show', $insiden->id) }}" class="btn btn-secondary"><i class="fa fa-info"></i></a>
                             
                                  </tr>
  
  
                                  <!-- Modal Hapus Data -->
                                 
                              @empty
                                  <tr class="text-center">
                                      <td colspan="6">Tidak ada data untuk ditampilkan!</td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </x-table.table>
                      {{ $list_insidens->links() }}
                  </div>
              </div>
        </div>
      </div>
  
 </div>
