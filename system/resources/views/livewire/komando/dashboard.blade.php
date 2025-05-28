<div>
    <div class="row g-2"> <!-- BEGIN col-8 -->

        <!-- END col-8 -->

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
                    <!-- BEGIN Device Status Section -->
                  <!-- BEGIN Device Status Section -->
<div class="position-relative">
    @if (!empty($confirmedDevices))
        <div class="row" wire:poll.3s>
            @foreach ($confirmedDevices as $device)
                @php
                    $statusText = $device['status'] ?? '-';
                    $statusColor = strtolower($statusText) === 'aktif' ? 'text-success' : 'text-danger';
                @endphp

                <div class="col-12 mb-4">
                    <div class="card bg-black text-white border border-secondary shadow-lg px-4 py-3" style="border-radius: 12px;">
                        <div class="row align-items-center g-4">
                            {{-- Kolom 1: Foto dan Nama Petugas --}}
                            <div class="col-md-4 col-12 text-center d-flex flex-column align-items-center">
                                <img
                                    width="100" height="100"
                                    src="{{ url('system/storage/app/public/' . $device['foto']) }}"
                                    alt="Device Photo"
                                    class="rounded-circle border border-light shadow"
                                    style="object-fit: cover; aspect-ratio: 1/1;">
                                <div class="fw-bold fs-5 mt-3">{{ $device['nama_petugas'] ?? '-' }}</div>
                            </div>

                            {{-- Kolom 2: Status dan Nomor Seri --}}
                            <div class="col-md-4 col-12 text-center text-md-start">
                                <div class="text-uppercase text-secondary small fw-semibold">Status</div>
                                <div class="mb-3 fw-bold {{ $statusColor }}">{{ $statusText }}</div>

                                <div class="text-uppercase text-secondary small fw-semibold">Nomor Seri</div>
                                <div class="fs-4 fw-bold text-warning">{{ $device['no_seri'] }}</div>
                            </div>

                            {{-- Kolom 3: Log Data --}}
                            <div class="col-md-4 col-12 text-center text-md-start">
                                <div class="text-uppercase text-secondary small fw-semibold mb-1">Kualitas Udara</div>
                                <div class="mb-2">
                                    <span class="fs-4 fw-bold text-warning">{{ $device['kualitas_udara'] ?? '-' }}</span>
                                </div>

                                <div class="text-uppercase text-secondary small fw-semibold mb-1">Suhu</div>
                                <div class="text-danger fs-5 fw-bold">{{ $device['suhu'] ? $device['suhu'] . '°C' : '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card bg-black text-white border border-secondary shadow-lg p-5 text-center" style="border-radius: 12px;">
                    <p class="mb-2 fs-5 fw-semibold text-warning">Belum ada perangkat yang dikonfirmasi</p>
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

                            @if (count($deviceLogs) > 0)
                                <div class="mt-4">
                                    <h5>Riwayat Log Perangkat Terbaru</h5>
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Waktu</th>
                                                <th>Suhu</th>
                                                <th>Kualitas Udara</th>
                                                <th>Status</th>
                                                <th>Lokasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($deviceLogs as $log)
                                                <tr>
                                                    <td>{{ $log->created_at }}</td>
                                                    <td>{{ $log->suhu }} °C</td>
                                                    <td>{{ $log->kualitas_udara }}</td>
                                                    <td>{{ $log->status }}</td>
                                                    <td>
                                                        @if ($log->latitude && $log->longitude)
                                                            <a href="https://maps.google.com/?q={{ $log->latitude }},{{ $log->longitude }}"
                                                                target="_blank">
                                                                Lihat di Peta
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const videoElement = document.getElementById('scanner-preview');
            const scanButton = document.querySelector('button[onclick="startScanner()"]');
            let scanner = null;
            let isScannerActive = false;

            console.log(videoElement, scanButton);

            // Check if elements exist
            if (!videoElement || !scanButton) {
                console.error('Required elements not found');
                return;
            }

            // Listen untuk reset dari Livewire 3 (multiple approaches)
            document.addEventListener('livewire:initialized', function() {
                Livewire.on('resetScanner', function() {
                    console.log('Reset scanner triggered from Livewire 3');
                    stopScanner();
                    resetForm();
                });
            });

            // Alternative listener untuk Livewire 3
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Livewire !== 'undefined') {
                    Livewire.on('resetScanner', function() {
                        console.log('Reset scanner triggered (alternative)');
                        stopScanner();
                        resetForm();
                    });
                }
            });

            // Reset form dan scanner
            function resetForm() {
                // Reset input
                const barcodeInput = document.getElementById('barcodeInput');
                if (barcodeInput) {
                    barcodeInput.value = '';
                }

                // Hide video element
                videoElement.classList.add('d-none');

                // Reset button
                resetScannerButton();

                console.log('Form and scanner reset completed');
            }

            // Auto-reset checker (fallback jika event tidak berfungsi)
            let lastQrCodeValue = '';
            setInterval(function() {
                const barcodeInput = document.getElementById('barcodeInput');
                if (barcodeInput) {
                    const currentValue = barcodeInput.value;

                    // Jika input kosong dan sebelumnya ada value, berarti form di-reset
                    if (!currentValue && lastQrCodeValue && isScannerActive) {
                        console.log('Auto-reset detected');
                        stopScanner();
                    }

                    lastQrCodeValue = currentValue;
                }
            }, 1000);

            // Initialize scanner only when needed
            function initializeScanner() {
                if (!scanner) {
                    scanner = new Instascan.Scanner({
                        video: videoElement,
                        scanPeriod: 5,
                        mirror: false
                    });

                    scanner.addListener('scan', function(content) {
                        document.getElementById('barcodeInput').value = content;

                        // Call Livewire method
                        if (typeof @this !== 'undefined') {
                            @this.call('barcodeScanned', content);
                        }

                        console.log('Scanned:', content);
                        stopScanner();
                    });
                }
            }

            window.startScanner = function() {
                if (isScannerActive) {
                    stopScanner();
                    return;
                }

                // Reset scanner jika ada masalah sebelumnya
                if (scanner) {
                    try {
                        scanner.stop();
                    } catch (e) {}
                    scanner = null;
                }

                // Check if HTTPS or localhost (dengan lebih banyak exception untuk development)
                const isSecureContext = location.protocol === 'https:' ||
                    location.hostname === 'localhost' ||
                    location.hostname === '127.0.0.1' ||
                    location.hostname.endsWith('.local') ||
                    location.hostname.includes('192.168.') ||
                    location.hostname.includes('10.0.') ||
                    location.hostname.includes('172.');

                if (!isSecureContext) {
                    // Tawarkan alternatif upload file
                    if (confirm(
                             'Kamera memerlukan HTTPS untuk berfungsi.\n\nGunakan upload file gambar sebagai alternatif?'
                            )) {
                        showFileUploadAlternative();
                        return;
                    } else {
                        const currentUrl = location.href;
                        const httpsUrl = currentUrl.replace('http://', 'https://');
                        alert('Gunakan HTTPS: ' + httpsUrl);
                        return;
                    }
                }

                initializeScanner();

                videoElement.classList.remove('d-none');
                scanButton.textContent = 'Stop Scanning';
                scanButton.classList.replace('btn-success', 'btn-danger');
                videoElement.classList.remove('d-none');
                videoElement.style.display = 'block';

                // Request camera permission dengan retry
                let retryCount = 0;
                const maxRetries = 3;

                function tryGetCamera() {
                    navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: 'environment' // Prefer back camera
                            }
                        })
                        .then(function(stream) {
                            // Stop the test stream
                            stream.getTracks().forEach(track => track.stop());

                            // Now start the scanner
                            return Instascan.Camera.getCameras();
                        })
                        .then(function(cameras) {
                            if (cameras.length > 0) {
                                // Try to use back camera if available
                                let selectedCamera = cameras[0];
                                for (let camera of cameras) {
                                    if (camera.name.toLowerCase().includes('back') ||
                                        camera.name.toLowerCase().includes('rear')) {
                                        selectedCamera = camera;
                                        break;
                                    }
                                }

                                return scanner.start(selectedCamera);
                            } else {
                                throw new Error('Tidak ada kamera yang terdeteksi');
                            }
                        })
                        .then(function() {
                            isScannerActive = true;
                            console.log('Scanner started successfully');
                        })
                        .catch(function(error) {
                            console.error('Scanner error (attempt ' + (retryCount + 1) + '):', error);

                            if (retryCount < maxRetries - 1) {
                                retryCount++;
                                console.log('Retrying camera access...');
                                setTimeout(tryGetCamera, 1000);
                                return;
                            }

                            let errorMessage = 'Gagal mengakses kamera!';
                            if (error.name === 'NotAllowedError') {
                                errorMessage =
                                    'Akses kamera ditolak. Klik icon kamera di address bar dan pilih "Always allow".';
                            } else if (error.name === 'NotFoundError') {
                                errorMessage =
                                    'Kamera tidak ditemukan. Pastikan kamera terhubung dan tidak digunakan aplikasi lain.';
                            } else if (error.name === 'NotSupportedError') {
                                errorMessage =
                                    'Browser tidak mendukung akses kamera. Coba gunakan Chrome terbaru.';
                            } else if (error.name === 'NotReadableError') {
                                errorMessage =
                                    'Kamera sedang digunakan aplikasi lain. Tutup aplikasi video call atau restart browser.';
                            }

                            alert(errorMessage);
                            resetScannerButton();
                        });
                }

                tryGetCamera();
            }

            // Alternative file upload untuk development
            function showFileUploadAlternative() {
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = 'image/*';
                fileInput.capture = 'environment'; // Use back camera on mobile

                fileInput.onchange = function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        // You can implement QR code reading from image here
                        // Or just show a message to manually enter the code
                        const manualInput = prompt(
                            'Upload gambar tidak tersedia. Silakan masukkan kode barcode secara manual:');
                        if (manualInput) {
                            document.getElementById('barcodeInput').value = manualInput;
                            if (typeof @this !== 'undefined') {
                                @this.call('barcodeScanned', manualInput);
                            }
                        }
                    }
                };

                fileInput.click();
            }

            function stopScanner() {
                if (scanner && isScannerActive) {
                    scanner.stop();
                }
                resetScannerButton();
                videoElement.classList.add('d-none');
            }

            function resetScannerButton() {
                scanButton.textContent = 'Buka Kamera & Scan';
                scanButton.classList.replace('btn-danger', 'btn-success');
                isScannerActive = false;
            }

            // Cleanup when page unloads
            window.addEventListener('beforeunload', function() {
                if (scanner && isScannerActive) {
                    scanner.stop();
                }
            });
        });
    </script>
</div>
