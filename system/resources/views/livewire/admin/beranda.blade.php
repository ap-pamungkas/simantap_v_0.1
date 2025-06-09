<div>
    <style>
        .hover-card {
            transition: transform 0.3s ease-in-out;
        }
        .hover-card:hover {
            transform: translateY(-5px);
        }
        .bg-gradient-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
        }
        .bg-gradient-success {
            background: linear-gradient(45deg, #1cc88a, #13855c);
        }
        .bg-gradient-danger {
            background: linear-gradient(45deg, #e74a3b, #be392d);
        }
    
        .hover-card-map {
            transition: all 0.3s ease;
            border: none;
        }
        .hover-card-map:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
        }
        .map-container {
            height: 350px;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .bg-gradient-temperature {
            background: linear-gradient(45deg, #FF5733, #C70039);
        }
        .bg-gradient-air {
            background: linear-gradient(45deg, #00A1D6, #0056b3);
        }
        .hover-card-chart {
            transition: all 0.3s ease;
        }
        .hover-card-chart:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
        }
        .chart-container {
            min-height: 350px;
        }
        .bg-gradient-info {
            background: linear-gradient(45deg, #36b9cc, #1a8997);
        }
        .bg-gradient-warning {
            background: linear-gradient(45deg, #f6c23e, #dda20a);
        }
        </style>
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-4">
            <div class="card mb-4 shadow-lg hover-card">
                <div class="card-header bg-gradient-primary">
                    <h5 class="card-title text-white mb-0">
                        <i class="fas fa-users me-2"></i>PETUGAS
                    </h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-light p-3">
                            <i class="fas fa-user-tie fa-2x text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="card-text mb-0 fw-bold">{{ $staffActive }}</h3>
                            <small class="text-muted">Total Petugas Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-lg hover-card">
                <div class="card-header bg-gradient-success">
                    <h5 class="card-title text-white mb-0">
                        <i class="fas fa-laptop me-2"></i>PERANGKAT
                    </h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-light p-3">
                            <i class="fas fa-microchip fa-2x text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="card-text mb-0 fw-bold">{{ $devices }}</h3>
                            <small class="text-muted">Total Perangkat Terpasang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-lg hover-card">
                <div class="card-header bg-gradient-danger">
                    <h5 class="card-title text-white mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>PERANGKAT RUSAK
                    </h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-light p-3">
                            <i class="fas fa-tools fa-2x text-danger"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="card-text mb-0 fw-bold">{{ $damagedDevices }}</h3>
                            <small class="text-muted">Perangkat Memerlukan Perbaikan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

    <div class="row">
        <!-- Incident Chart (Petugas, Suhu, Kualitas Udara) -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-lg hover-card-chart">
                <div class="card-header bg-gradient-info">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title text-white mb-0">
                                <i class="fas fa-chart-line me-2"></i>INSIDEN
                            </h5>
                        </div>
                        <div class="col-6 text-end">
                            <span class="badge bg-light text-dark">Real-time</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div id="incidentChart" class="chart-container"></div>
                </div>
            </div>
        </div>
        <!-- Sensor Data Chart -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-lg hover-card-chart">
                <div class="card-header bg-gradient-warning">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title text-white mb-0">
                                <i class="fas fa-microchip me-2"></i>DATA SENSOR
                            </h5>
                        </div>
                        <div class="col-6 text-end">
                            <span class="badge bg-light text-dark">Live Data</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div id="sensorChart" class="chart-container"></div>
                </div>
            </div>
        </div>
    </div>


    <!-- Heatmap Map Row -->
    <div class="row">
        <!-- Temperature Heatmap Map -->
        <div class="col-md-12">
            <div class="card mb-4 shadow-lg hover-card-map">
                <div class="card-header bg-gradient-temperature">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title text-white mb-0">
                                <i class="fas fa-temperature-high me-2"></i>PETA SUHU (°C)
                            </h5>
                        </div>
                        <div class="col-6 text-end">
                            <span class="badge bg-light text-dark">Live Map</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="position-relative">
                        <div id="temperatureMap" class="map-container"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CO2 Heatmap Map -->
        <div class="col-md-12">
            <div class="card mb-4 shadow-lg hover-card-map">
                <div class="card-header bg-gradient-air">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title text-white mb-0">
                                <i class="fas fa-wind me-2"></i>PETA KUALITAS UDARA (PPM)
                            </h5>
                        </div>
                        <div class="col-6 text-end">
                            <span class="badge bg-light text-dark">Live Map</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div id="co2Map" class="map-container"></div>
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
                    show: false
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

        // Base map layers
        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        });

        var darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '© CartoDB Dark Matter'
        });

        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '© Esri World Imagery'
        });

        var terrainLayer = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}{r}.png', {
            attribution: '© Stamen Design'
        });

        // Temperature Heatmap
        var temperatureMap = L.map('temperatureMap', {
            center: [-1.8467, 109.9719],
            zoom: 13,
            renderer: new CustomCanvasRenderer(),
            layers: [osmLayer]
        });

        // Layer control for temperature map
        var temperatureBaseMaps = {
            "OpenStreetMap": osmLayer,
            "Dark Mode": darkLayer,
            "Satellite": satelliteLayer,
            "Terrain": terrainLayer
        };

        L.control.layers(temperatureBaseMaps).addTo(temperatureMap);

        var temperaturePoints = @json($temperatureData['coords']).filter(coord =>
            coord.lat && coord.lng && coord.intensity !== undefined
        ).map(coord => [coord.lat, coord.lng, Math.max(0, coord.intensity)]);

        if (temperaturePoints.length > 0) {
            L.heatLayer(temperaturePoints, {
                radius: 25,
                blur: 15,
                maxZoom: 17,
                gradient: {
                    0.0: '#00A1D6',
                    0.5: '#FFD700',
                    0.75: '#FF5733',
                    1.0: '#C70039'
                }
            }).addTo(temperatureMap);
        } else {
            console.warn('No valid temperature points available for heatmap');
        }

        // CO2 Heatmap
        var co2Map = L.map('co2Map', {
            center: [-1.8467, 109.9719],
            zoom: 13,
            renderer: new CustomCanvasRenderer(),
            layers: [osmLayer]
        });

        // Layer control for CO2 map
        var co2BaseMaps = {
            "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }),
            "Dark Mode": L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '© CartoDB Dark Matter'
            }),
            "Satellite": L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '© Esri World Imagery'
            }),
            "Terrain": L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}{r}.png', {
                attribution: '© Stamen Design'
            })
        };

        L.control.layers(co2BaseMaps).addTo(co2Map);

        var co2Points = @json($co2Data['coords']).filter(coord =>
            coord.lat && coord.lng && coord.intensity !== undefined
        ).map(coord => [coord.lat, coord.lng, Math.max(0, coord.intensity)]);
        
        if (co2Points.length > 0) {
            L.heatLayer(co2Points, {
                radius: 25,
                blur: 15,
                maxZoom: 17,
                gradient: {
                    0.0: '#00A1D6',
                    0.5: '#FFD700',
                    0.75: '#FF5733',
                    1.0: '#C70039'
                }
            }).addTo(co2Map);
        } else {
            console.warn('No valid CO2 points available for heatmap');
        }
    </script>
</div>