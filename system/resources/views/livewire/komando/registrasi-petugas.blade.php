<div>
    <div class="row g-2">
        <!-- BEGIN col-8 -->
        <div class="col-xl-6 col-lg-6">
            <div id="cardPetugas" class="card h-100 ">
                <div class="card-header mb-3">
                   <span class="text-theme fw-500 fs-14px"> DATA PETUGAS</span>
                    <div class="float-end">
                        <a href="#" data-toggle="card-collapse" class="btn">
                            <i class="fas fa-minus"></i> <!-- Collapse icon -->
                        </a>

                        <a href="#" data-toggle="card-expand" class="btn">
                            <i class="fas fa-expand"></i> <!-- Fullscreen icon -->
                        </a>

                        <a href="#" data-toggle="card-remove" class="btn">
                            <i class="fas fa-times"></i> <!-- Close icon -->
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="position-relative">
                        @if (!empty($confirmedDevices))
                            <div class="row devices-container">
                                @foreach ($confirmedDevices as $device)
                                    @php
                                        $statusText = $device['status'] ?? '-';
                                        $statusColor =
                                            strtolower($statusText) === 'aktif' ? 'text-success' : 'text-danger';
                                    @endphp
                                    <div id="deviceItem" class="col-12 mb-4 ">
                                        <div class="card bg-black text-white border border-secondary shadow-lg px-4 py-3"
                                            style="border-radius: 12px;">
                                            <div class="row align-items-center g-4">
                                                <div
                                                    class="col-md-4 col-12 text-center d-flex flex-column align-items-center">
                                                   @if($device['foto'])
                                                   <img width="100" height="100" src="{{ $device['foto'] }}"
                                                   alt="Foto {{ $device['nama_petugas'] ?? 'Petugas' }}"
                                                   class="rounded-circle border border-light shadow"
                                                   style="object-fit: cover; aspect-ratio: 1/1;"
                                                 >
                                                   @else
                                                    <img width="100" height="100" src="{{ url('public/komando/assets/img/user/petugas.jpg') }}"
                                                        alt="Foto {{ $device['nama_petugas'] ?? 'Petugas' }}"
                                                        class="rounded-circle border border-light shadow"
                                                        style="object-fit: cover; aspect-ratio: 1/1;"
                                                    >
                                                   @endif
                                                    <div class="fw-bold fs-5 mt-3">{{ $device['nama_petugas'] ?? '-' }}</div>
                                                </div>
                                                <div class="col-md-4 col-12 text-center text-md-start">
                                                    <div class="text-uppercase text-secondary small fw-semibold">Status</div>
                                                    <div class="mb-3 fw-bold {{ $statusColor }}">{{ $statusText }}</div>
                                                    <div class="text-uppercase text-secondary small fw-semibold">Nomor Seri</div>
                                                    <div class="fs-4 fw-bold text-warning">{{ $device['no_seri'] }}</div>
                                                </div>
                                                <div class="col-md-4 col-12 text-center text-md-start">
                                                    <div class="text-uppercase text-secondary small fw-semibold mb-1">Kualitas Udara</div>
                                                    <div class="mb-2">
                                                        <span class="fs-4 fw-bold text-warning">{{ $device['kualitas_udara'] ?? '-' }}</span>
                                                    </div>
                                                    <div class="text-uppercase text-secondary small fw-semibold mb-1">Suhu</div>
                                                    <div class="text-danger fs-5 fw-bold">
                                                        {{ $device['suhu'] ? $device['suhu'] . '°C' : '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  
                                @endforeach
                            </div>
                        @else
                            <div class="row">
                                <div class="col-12">
                                    <div class="card bg-black text-white border border-secondary shadow-lg p-5 text-center"
                                        style="border-radius: 12px;">
                                        <p class="mb-2 fs-5 fw-semibold text-warning">Belum ada perangkat yang dikonfirmasi</p>
                                        <p class="text-white-50 mb-0">Scan perangkat untuk memulai pemantauan.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

         
        </div>
        <!-- END col-8 -->
        <!-- BEGIN col-4 -->
        <div class="col-xl-6 col-lg-6">
            <div class="card h-100">
                <div class="card-header mb-3">
                      <span class="text-theme fw-500 fs-14px"> REGISTRASI PETUGAS</span>
                    <div class="float-end">
                        <a href="#" data-toggle="card-collapse" class="btn">
                            <i class="fas fa-minus"></i> <!-- Collapse icon -->
                        </a>

                        <a href="#" data-toggle="card-expand" class="btn">
                            <i class="fas fa-expand"></i> <!-- Fullscreen icon -->
                        </a>

                        <a href="#" data-toggle="card-remove" class="btn">
                            <i class="fas fa-times"></i> <!-- Close icon -->
                        </a>
                    </div>
                </div>

                <div class="card-body" wire:ignore.self>
                    <button class="btn btn-success mb-3" onclick="startScanner()">Buka Kamera & Scan</button>
                    <video id="scanner-preview" class="w-100 d-none" autoplay playsinline muted wire:ignore></video>

                    @if (session()->has('message'))
                        <div class="alert alert-success mb-3">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger mb-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errorMessage)
                        <div class="alert alert-danger mt-3 d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>{{ $errorMessage }}</div>
                        </div>
                    @endif
                    <hr>

                    <form wire:submit.prevent="confirmDevice">
                        <div class="mb-3">
                            <label for="barcodeInput" class="form-label">No Seri</label>
                            <input type="text" id="barcodeInput" wire:model="qrcode" class="form-control stats-input"
                                placeholder="Scan barcode di sini..." readonly>
                        </div>

                        @if ($selectedDevice)
                            <div class="mb-3">
                                <label class="form-label text-theme">Status</label>
                                <input type="text" class="form-control" value="{{ $selectedDevice->status }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-theme">Pilih Petugas</label>
                                <select wire:model.live="selectedPetugas" class="form-select" required>
                                    <option class="text-theme" value="">-- Pilih Petugas --</option>
                                    @foreach ($petugasList as $id => $petugas)
                                        <option value="{{ $id }}">{{ $petugas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <!-- END col-4 -->
    </div>

    @livewireScripts
    <script src="{{ url('public/scan/instascan.min.js') }}"></script>
    @include('livewire.komando.scanner-script')
    
   <script>
    document.addEventListener('DOMContentLoaded', function () {
    const cardPetugas = document.getElementById('cardPetugas');
    const deviceItems = document.querySelectorAll('#deviceItem');
    const expandButton = cardPetugas.querySelector('[data-toggle="card-expand"]');

    expandButton.addEventListener('click', function (e) {
        e.preventDefault();
        cardPetugas.classList.toggle('card-expand');

        deviceItems.forEach(item => {

            console.log(cardPetugas.classList.contains('card-expand'));
            
            if (cardPetugas.classList.contains('card-expand')) {
                item.classList.remove('col-4');
                item.classList.add('col-12');
            } else {
                item.classList.remove('col-12');
                item.classList.add('col-4');
              
            }
        });
    });
});
   </script>
   
</div>


