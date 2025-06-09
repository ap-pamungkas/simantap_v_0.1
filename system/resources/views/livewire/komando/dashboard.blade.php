<div>
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title text-theme">Petugas Bertugas</h5>
                </div>
                <div class="card-body d-flex align-items-center">
 aspect-ratio: 16/9;
                    <i class="fas fa-users fa-2x me-3 text-theme"></i>
                    <h3 class="card-text">{{ $staffOnDuty }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title text-theme">Perangkat Digunakan</h5>
                </div>
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-laptop fa-2x me-3 text-theme"></i>
                    <h3 class="card-text">{{ $devicesUsed }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title text-theme">Perangkat Aktif</h5>
                </div>
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3 text-theme"></i>
                    <h3 class="card-text">{{ $perangkat }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Incident Chart (Petugas, Suhu, Kualitas Udara) -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-theme card-title">INSIDEN</h5>
                        </div>
                        <div class="col-6"></div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="incidentChart"></div>
                </div>
            </div>
        </div>
        <!-- Sensor Data Chart -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-theme card-title">DATA SENSOR</h5>
                        </div>
                        <div class="col-6"></div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="sensorChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Heatmap Map Row -->
    <div class="row">
        <!-- Temperature Heatmap Map -->
        <div class="col-md-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-theme card-title">PETA SUHU (°C)</h5>
                        </div>
                        <div class="col-6">
                            <select wire:change="updateMapTheme($event.target.value)" class="form-select float-end w-auto">
                                <option value="light" @if($mapTheme == 'light') selected @endif>Light</option>
                                <option value="dark" @if($mapTheme == 'dark') selected @endif>Dark</option>
                                <option value="satellite" @if($mapTheme == 'satellite') selected @endif>Satellite</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative">
                        <div id="temperatureMap" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CO2 Heatmap Map -->
        <div class="col-md-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-theme card-title">PETA KUALITAS UDARA (PPM)</h5>
                        </div>
                        <div class="col-6">
                            <select wire:change="updateMapTheme($event.target.value)" class="form-select float-end w-auto">
                                <option value="light" @if($mapTheme == 'light') selected @endif>Light</option>
                                <option value="dark" @if($mapTheme == 'dark') selected @endif>Dark</option>
                                <option value="satellite" @if($mapTheme == 'satellite') selected @endif>Satellite</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="co2Map" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="{{ url('public/leaflet/leaflet.css') }}" />
    <script src="{{ url('public/leaflet/leaflet.js') }}"></script>
    <script src="{{ url('public/leaflet/leaflet-heat.js') }}"></script>

    <script>
        // Incident Chart (Petugas, Suhu, Kualitas Udara)
        var incidentOptions = {
            chart: {
                type: 'line',
                height: 350,
                zoom: { enabled: false },
                animations: { enabled: true }
            },
            series: [
                { name: 'Petugas Bertugas', data: @json($incidentData['petugas']) },
                { name: 'Suhu (°C)', data: @json($incidentData['suhu']) },
                { name: 'Kualitas Udara (ppm)', data: @json($incidentData['kualitas_udara']) }
            ],
            xaxis: {
                categories: @json($incidentData['labels']),
                title: {
                    text: 'Tanggal',
                    style: {
                        fontSize: '14px',
                        color: '#333',
                        fontWeight: 'bold',
                        fontFamily: 'Arial, sans-serif'
                    }
                }
            },
            yaxis: [
                {
                    title: {
                        text: 'Petugas Bertugas',
                        style: {
                            fontSize: '14px',
                            color: '#333',
                            fontWeight: 'bold',
                            fontFamily: 'Arial, sans-serif'
                        }
                    },
                    min: 0,
                    forceNiceScale: true
                },
                {
                    opposite: true,
                    title: {
                        text: 'Suhu (°C)',
                        style: {
                            fontSize: '14px',
                            color: '#333',
                            fontWeight: 'bold',
                            fontFamily: 'Arial, sans-serif'
                        }
                    },
                    min: 0
                },
                {
                    opposite: true,
                    title: {
                        text: 'Kualitas Udara (ppm)',
                        style: {
                            fontSize: '14px',
                            color: '#333',
                            fontWeight: 'bold',
                            fontFamily: 'Arial, sans-serif'
                        }
                    },
                    min: 0,
                    show: false // Sembunyikan sumbu kedua agar tidak tumpang tindih
                }
            ],
            stroke: {
                curve: 'smooth',
                width: 2
            },
            colors: ['#FF5733', '#2196F3', '#FFD700'],
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right'
            },
            grid: {
                borderColor: '#e7e7e7'
            }
        };

        var incidentChart = new ApexCharts(document.querySelector("#incidentChart"), incidentOptions);
        incidentChart.render();

        // Sensor Data Chart
        var sensorChartOptions = {
            chart: {
                type: 'line',
                height: 350,
                zoom: { enabled: false },
                animations: { enabled: true }
            },
            series: [
                { name: 'Suhu (°C)', data: @json($temperatureData['data']) },
                { name: 'Kualitas Udara (ppm)', data: @json($co2Data['data']) }
            ],
            xaxis: {
                categories: @json($temperatureData['labels']),
                title: {
                    text: 'Tanggal',
                    style: {
                        fontSize: '14px',
                        color: '#333',
                        fontWeight: 'bold',
                        fontFamily: 'Arial, sans-serif'
                    }
                }
            },
            yaxis: [
                {
                    title: {
                        text: 'Suhu (°C)',
                        style: {
                            fontSize: '14px',
                            color: '#333',
                            fontWeight: 'bold',
                            fontFamily: 'Arial, sans-serif'
                        }
                    },
                    min: 0
                },
                {
                    opposite: true,
                    title: {
                        text: 'Kualitas Udara (ppm)',
                        style: {
                            fontSize: '14px',
                            color: '#333',
                            fontWeight: 'bold',
                            fontFamily: 'Arial, sans-serif'
                        }
                    },
                    min: 0
                }
            ],
            stroke: {
                curve: 'smooth',
                width: 2
            },
            colors: ['#2196F3', '#FF5733'],
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right'
            },
            grid: {
                borderColor: '#e7e7e7'
            }
        };

        var sensorChart = new ApexCharts(document.querySelector("#sensorChart"), sensorChartOptions);
        sensorChart.render();

        // Custom Canvas Renderer with willReadFrequently
        var CustomCanvasRenderer = L.Canvas.extend({
            _initContainer: function () {
                L.Canvas.prototype._initContainer.call(this);
                this._ctx = this._container.getContext('2d', { willReadFrequently: true });
            }
        });

        // Temperature Heatmap
        var temperatureMap = L.map('temperatureMap', {
            center: [-1.8467, 109.9719],
            zoom: 13,
            renderer: new CustomCanvasRenderer()
        });

        var tileLayerUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        if ('{{ $mapTheme }}' === 'dark') {
            tileLayerUrl = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';
        } else if ('{{ $mapTheme }}' === 'satellite') {
            tileLayerUrl = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';
        }

        L.tileLayer(tileLayerUrl, {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(temperatureMap);

        var temperaturePoints = @json($temperatureData['coords']).filter(coord =>
            coord.lat && coord.lng && coord.intensity !== undefined
        ).map(coord => [coord.lat, coord.lng, Math.max(0, coord.intensity)]);

        if (temperaturePoints.length > 0) {
            L.heatLayer(temperaturePoints, {
                radius: 25,
                blur: 15,
                maxZoom: 17,
                gradient: {
                    0.0: '#00A1D6', // Low (blue)
                    0.5: '#FFD700', // Moderate (yellow)
                    0.75: '#FF5733', // High (orange)
                    1.0: '#C70039'  // Extreme (red)
                }
            }).addTo(temperatureMap);
        } else {
            console.warn('No valid temperature points available for heatmap');
        }

        // CO2 Heatmap
        var co2Map = L.map('co2Map', {
            center: [-1.8467, 109.9719],
            zoom: 13,
            renderer: new CustomCanvasRenderer()
        });


        
        L.tileLayer(tileLayerUrl, {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(co2Map);

        var co2Points = @json($co2Data['coords']).filter(coord =>
            coord.lat && coord.lng && coord.intensity !== undefined
        ).map(coord => [coord.lat, coord.lng, Math.max(0, coord.intensity)]);

        console.log(co2Points);
        
        if (co2Points.length > 0) {
            L.heatLayer(co2Points, {
                radius: 25,
                blur: 15,
                maxZoom: 17,
                gradient: {
                    0.0: '#00A1D6', // Low (blue)
                    0.5: '#FFD700', // Moderate (yellow)
                    0.75: '#FF5733', // High (orange)
                    1.0: '#C70039'  // Extreme (red)
                }
            }).addTo(co2Map);
        } else {
            console.warn('No valid CO2 points available for heatmap');
        }
    </script>
</div>