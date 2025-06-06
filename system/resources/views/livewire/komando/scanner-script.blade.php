{{-- @push('scripts') --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const videoElement = document.getElementById('scanner-preview');
        const scanButton = document.querySelector('button[onclick="startScanner()"]');
        let scanner = null;
        let isScannerActive = false;

        if (!videoElement || !scanButton) {
            console.error('Required elements not found');
            return;
        }

        setupLivewireListeners();
        let lastQrCodeValue = '';
        setInterval(checkAutoReset, 500);

        function initializeScanner() {
            if (!scanner) {
                scanner = new Instascan.Scanner({
                    video: videoElement,
                    scanPeriod: 5,
                    mirror: false
                });
                scanner.addListener('scan', handleScan);
            }
        }

        window.startScanner = function() {
            if (isScannerActive) {
                stopScanner();
                return;
            }
            resetScannerIfNeeded();
            if (!checkSecureContext()) return;
            initializeScanner();
            startVideoStream();
        };

        function setupLivewireListeners() {
            Livewire.on('resetScanner', () => {
                console.log('Reset scanner triggered');
                stopScanner();
                resetForm();
            });
        }

        function checkAutoReset() {
            const barcodeInput = document.getElementById('barcodeInput');
            if (barcodeInput) {
                const currentValue = barcodeInput.value;
                if (!currentValue && lastQrCodeValue && isScannerActive) {
                    console.log('Auto-reset detected');
                    stopScanner();
                }
                lastQrCodeValue = currentValue;
            }
        }

        function handleScan(content) {
            document.getElementById('barcodeInput').value = content;
            if (typeof @this !== 'undefined') {
                @this.call('barcodeScanned', content);
            }
            console.log('Scanned:', content);
            stopScanner();
        }

        function resetScannerIfNeeded() {
            if (scanner) {
                try {
                    scanner.stop();
                } catch (e) {}
                scanner = null;
            }
        }

        function checkSecureContext() {
            const isSecureContext = location.protocol === 'https:' ||
                location.hostname === 'localhost' ||
                location.hostname === '127.0.0.1' ||
                location.hostname.endsWith('.local') ||
                location.hostname.includes('192.168.') ||
                location.hostname.includes('10.0.') ||
                location.hostname.includes('172.');

            if (!isSecureContext) {
                return handleInsecureContext();
            }
            return true;
        }

        function handleInsecureContext() {
            if (confirm('Kamera memerlukan HTTPS untuk berfungsi.\n\nGunakan upload file gambar sebagai alternatif?')) {
                showFileUploadAlternative();
                return false;
            } else {
                alert('Gunakan HTTPS: ' + location.href.replace('http://', 'https://'));
                return false;
            }
        }

        function startVideoStream() {
            videoElement.classList.remove('d-none');
            scanButton.textContent = 'Stop Scanning';
            scanButton.classList.replace('btn-success', 'btn-danger');
            requestCameraAccess();
        }

        function requestCameraAccess() {
            let retryCount = 0;
            const maxRetries = 3;

            function tryGetCamera() {
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment' }
                })
                .then(stream => {
                    stream.getTracks().forEach(track => track.stop());
                    return Instascan.Camera.getCameras();
                })
                .then(cameras => {
                    if (cameras.length > 0) {
                        const selectedCamera = cameras.find(camera =>
                            camera.name.toLowerCase().includes('back') ||
                            camera.name.toLowerCase().includes('rear')) || cameras[0];
                        return scanner.start(selectedCamera);
                    } else {
                        throw new Error('Tidak ada kamera yang terdeteksi');
                    }
                })
                .then(() => {
                    isScannerActive = true;
                    console.log('Scanner started successfully');
                })
                .catch(error => handleCameraError(error, retryCount, maxRetries, tryGetCamera));
            }

            tryGetCamera();
        }

        function handleCameraError(error, retryCount, maxRetries, tryGetCamera) {
            console.error('Scanner error (attempt ' + (retryCount + 1) + '):', error);
            if (retryCount < maxRetries - 1) {
                retryCount++;
                console.log('Retrying camera access...');
                setTimeout(tryGetCamera, 1000);
            } else {
                alert(getCameraErrorMessage(error));
                resetScannerButton();
            }
        }

        function getCameraErrorMessage(error) {
            switch (error.name) {
                case 'NotAllowedError':
                    return 'Akses kamera ditolak. Klik ikon kamera di address bar dan pilih "Always allow".';
                case 'NotFoundError':
                    return 'Kamera tidak ditemukan. Pastikan kamera terhubung dan tidak digunakan aplikasi lain.';
                case 'NotSupportedError':
                    return 'Browser tidak mendukung akses kamera. Coba gunakan Chrome terbaru.';
                case 'NotReadableError':
                    return 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi video call atau restart browser.';
                default:
                    return 'Gagal mengakses kamera!';
            }
        }

        function showFileUploadAlternative() {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';
            fileInput.capture = 'environment';

            fileInput.onchange = function(event) {
                const file = event.target.files[0];
                if (file) {
                    const manualInput = prompt('Upload gambar tidak tersedia. Silakan masukkan kode barcode secara manual:');
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

        function resetForm() {
            const barcodeInput = document.getElementById('barcodeInput');
            if (barcodeInput) {
                barcodeInput.value = '';
            }
        }

        window.addEventListener('beforeunload', () => {
            if (scanner && isScannerActive) {
                scanner.stop();
            }
        });

        function initializeEchoListener(retryCount = 0, maxRetries = 10) {
            if (window.Echo) {
                console.log('Echo is available, setting up listener');
                window.Echo.channel('devices').listen('DeviceUpdated', (e) => {
                    console.log('DeviceUpdated received:', e.confirmedDevices);
                    @this.call('updateDevices', e);
                });
                window.addEventListener('beforeunload', () => {
                    window.Echo.leave('devices');
                });
            } else if (retryCount < maxRetries) {
                console.warn(`Echo not available, retrying (${retryCount + 1}/${maxRetries})...`);
                setTimeout(() => initializeEchoListener(retryCount + 1, maxRetries), 1000);
            } else {
                console.error('Failed to initialize Echo listener after max retries');
            }
        }

        initializeEchoListener();
    });
</script>
{{-- @endpush --}}
