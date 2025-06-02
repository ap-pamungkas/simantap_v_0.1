<div>
    <div class="row g-2"> <!-- BEGIN col-8 -->
        <!-- BEGIN col-4 -->
        <div class="col-xl-6 col-lg-6">
            <!-- BEGIN card -->
            <div class="card h-100">
                <!-- BEGIN card-header -->
                <div class="card-header with-btn">
                    Data Petugas
                    <div class="card-header-btn">
                        <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon
                                icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                        <a href="#" data-toggle="card-expand" class="btn"><iconify-icon
                                icon="material-symbols-light:fullscreen"></iconify-icon></a>
                        <a href="#" data-toggle="card-remove" class="btn"><iconify-icon
                                icon="material-symbols-light:close-rounded"></iconify-icon></a>
                    </div>
                </div>
                <!-- END card-header -->
                <!-- BEGIN card-body -->
                <div class="card-body">


                    <hr class="my-4" />
                    <!-- BEGIN Device Status Section -->
                    <div class="position-relative">
                        @if (!empty($confirmedDevices))
                            <div class="row" wire:poll.3s>
                                @foreach ($confirmedDevices as $device)
                                    @php
                                        $statusText = $device['status'] ?? '-';
                                        $statusColor =
                                            strtolower($statusText) === 'aktif' ? 'text-success' : 'text-danger';
                                    @endphp

                                    <div class="col-12 mb-4">
                                        <div class="card bg-black text-white border border-secondary shadow-lg px-4 py-3"
                                            style="border-radius: 12px;">
                                            <div class="row align-items-center g-4">
                                                {{-- Kolom 1: Foto dan Nama Petugas --}}
                                                <div
                                                    class="col-md-4 col-12 text-center d-flex flex-column align-items-center">
                                                    <img width="100" height="100" src="{{ $device['foto'] }}"
                                                        alt="Foto {{ $device['nama_petugas'] ?? 'Petugas' }}"
                                                        class="rounded-circle border border-light shadow"
                                                        style="object-fit: cover; aspect-ratio: 1/1;"
                                                        onerror="this.src='https://via.placeholder.com/100x100';">
                                                    <div class="fw-bold fs-5 mt-3">{{ $device['nama_petugas'] ?? '-' }}
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Status dan Nomor Seri --}}
                                                <div class="col-md-4 col-12 text-center text-md-start">
                                                    <div class="text-uppercase text-secondary small fw-semibold">Status
                                                    </div>
                                                    <div class="mb-3 fw-bold {{ $statusColor }}">{{ $statusText }}
                                                    </div>

                                                    <div class="text-uppercase text-secondary small fw-semibold">Nomor
                                                        Seri</div>
                                                    <div class="fs-4 fw-bold text-warning">{{ $device['no_seri'] }}
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Log Data --}}
                                                <div class="col-md-4 col-12 text-center text-md-start">
                                                    <div class="text-uppercase text-secondary small fw-semibold mb-1">
                                                        Kualitas Udara</div>
                                                    <div class="mb-2">
                                                        <span
                                                            class="fs-4 fw-bold text-warning">{{ $device['kualitas_udara'] ?? '-' }}</span>
                                                    </div>

                                                    <div class="text-uppercase text-secondary small fw-semibold mb-1">
                                                        Suhu</div>
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
                                        <p class="mb-2 fs-5 fw-semibold text-warning">Belum ada perangkat yang
                                            dikonfirmasi</p>
                                        <p class="text-white-50 mb-0">Scan perangkat untuk memulai pemantauan.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- END Device Status Section -->
                    <!-- END Device Status Section -->


                </div>
                <!-- END card-body -->
            </div>
            <!-- END card -->
        </div>
        <!-- END col-4 -->
        <!-- BEGIN col-4 -->
        <div class="col-xl-6 col-lg-6">
            <!-- BEGIN card -->
            <div class="card h-100">
                <!-- BEGIN card-header -->
                <div class="card-header with-btn">
                    Registrasi Petugas

                    <div class="card-header-btn">
                        <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon
                                icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                        <a href="#" data-toggle="card-expand" class="btn"><iconify-icon
                                icon="material-symbols-light:fullscreen"></iconify-icon></a>
                        <a href="#" data-toggle="card-remove" class="btn"><iconify-icon
                                icon="material-symbols-light:close-rounded"></iconify-icon></a>
                    </div>

                </div>
                <!-- END card-header -->
                <div class="card-body " wire:ignore.self>
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

                    <form wire:submit.prevent="confirmDevice">
                        <div class="mb-3">
                            <label for="barcodeInput" class="form-label">No Seri</label>
                            <input type="text" id="barcodeInput" wire:model="qrcode" class="form-control"
                                placeholder="Scan barcode di sini..." readonly>
                        </div>

                        @if ($selectedDevice)
                            <div class="mb-3" wire:poll.2s>
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control" value="{{ $selectedDevice->status }}"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pilih Petugas</label>
                                <select wire:model.live="selectedPetugas" class="form-select" required>
                                    <option value="">-- Pilih Petugas --</option>
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
            <!-- END card -->
        </div>
        <!-- END col-4 -->


    </div>

    @livewireScripts
    {{-- <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script> --}}
    <script src="{{ url('public/scan/instascan.min.js') }}"></script>
    @include('livewire.komando.scanner-script')

</div>
