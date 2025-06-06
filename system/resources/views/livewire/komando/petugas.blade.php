<div >
    

    <div class="card card-outline card-secondary">
        <div class="card-header">
         
           <div class="card-title">
             <h2 class="text-theme"> DATA PETUGAS</h2>
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
                                      Nama Petugas
                                      @if($sortField === 'nama')
                                          <i class="bi bi-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                      @endif
                                  </th>
                                  <th >
                                      Jabatan
                                  </th>
                                  <th >Jenis Kelamin</th>
                                  <th  wire:click="sortBy('jenis_kelamin')" style="cursor: pointer;">Tanggal Lahir
  
                                  </th>
                                  <th>Status</th>
                               
                              </tr>
                          </thead>
                          <tbody>
                              @forelse ($list_petugas as $index => $petugas)
                                  <tr>
                                      <td>{{ $index + 1 + (($list_petugas->currentPage() - 1) * $list_petugas->perPage()) }}</td>
                                      <td>{{ $petugas->nama}}</td>
                                      <td>{{ $petugas->jabatan->nama_jabatan }}</td>
                                      <td>{{ $petugas->jenis_kelamin }}</td>
                                      <td>{{ $petugas->tgl_lahir }}</td>
                                      <td>
                                          @if ($petugas->status == 'Aktif')
                                              <button class="btn btn-success btn-sm">Aktif</button>
                                          @else
                                              <button class="btn btn-danger btn-sm">Tidak Aktif</button>
                                          @endif
                                      </td>
                                     
                                  </tr>
  
  
                                  <!-- Modal Hapus Data -->
                                 
                              @empty
                                  <tr class="text-center">
                                      <td colspan="6">Tidak ada data untuk ditampilkan!</td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </x-table.table>
                      {{ $list_petugas->links() }}
                  </div>
              </div>
        </div>
      </div>
  
 </div>
