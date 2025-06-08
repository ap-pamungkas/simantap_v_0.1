<div>
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title text-theme">Petugas Bertugas</h5>
                </div>
                <div class="card-body d-flex align-items-center">
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
                    <h3 class="card-text">{{ $devicesActive }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Temperature Chart -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-theme card-title">SUHU (°C)</h5>
                        </div>
                        <div class="col-6">
                            <div class="float-end">
                                <a href="#" data-toggle="card-collapse" class="btn">
                                    <i class="fas fa-minus"></i>
                                </a>
                                <a href="#" class="btn expand-btn">
                                    <i class="fas fa-expand"></i>
                                </a>
                                <a href="#" data-toggle="card-remove" class="btn">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="temperatureChart" class="chart-container"></div>
                </div>
            </div>
        </div>
        <!-- CO2 Chart -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-theme card-title">KUALITAS UDARA (CO2, PPM)</h5>
                        </div>
                        <div class="col-6">
                            <div class="float-end">
                                <a href="#" data-toggle="card-collapse" class="btn">
                                    <i class="fas fa-minus"></i>
                                </a>
                                <a href="#" class="btn expand-btn">
                                    <i class="fas fa-expand"></i>
                                </a>
                                <a href="#" data-toggle="card-remove" class="btn">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="co2Chart" class="chart-container"></div>
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
                            <div class="float-end">
                                <a href="#" data-toggle="card-collapse" class="btn">
                                    <i class="fas fa-minus"></i>
                                </a>
                                <a href="#" class="btn expand-btn">
                                    <i class="fas fa-expand"></i>
                                </a>
                                <a href="#" data-toggle="card-remove" class="btn">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative">
                        <div id="temperatureMap" class="map-container" style="height: 350px;"></div>
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
                            <h5 class="text-theme card-title">PETA KUALITAS UDARA (CO2, PPM)</h5>
                        </div>
                        <div class="col-6">
                            <div class="float-end">
                                <a href="#" data-toggle="card-collapse" class="btn">
                                    <i class="fas fa-minus"></i>
                                </a>
                                <a href="#" class="btn expand-btn">
                                    <i class="fas fa-expand"></i>
                                </a>
                                <a href="#" data-toggle="card-remove" class="btn">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="co2Map" class="map-container" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <!-- ApexCharts CDN (for existing charts) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="{{ url('public/leaflet/leaflet.css') }}" />
<script src="{{ url('public/leaflet/leaflet.js') }}"></script>
<script src="{{ url('public/leaflet/leaflet-heat.js') }}"></script>

<style>
.card.expanded {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    background-color: black;
    margin: 0;
    border-radius: 0;
}

.card.expanded .chart-container {
    height: calc(100vh - 100px) !important;
}

.card.expanded .map-container {
    height: calc(100vh - 100px) !important;
}
</style>

<script>
    // Add expand functionality
    document.querySelectorAll('.expand-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.card');
            card.classList.toggle('expanded');
            
            // Update charts and maps when expanded
            if (card.classList.contains('expanded')) {
                if (temperatureChart) temperatureChart.updateOptions({ chart: { height: '100%' }});
                if (co2Chart) co2Chart.updateOptions({ chart: { height: '100%' }});
                if (temperatureMap) temperatureMap.invalidateSize();
                if (co2Map) co2Map.invalidateSize();
            } else {
                if (temperatureChart) temperatureChart.updateOptions({ chart: { height: 350 }});
                if (co2Chart) co2Chart.updateOptions({ chart: { height: 350 }});
                if (temperatureMap) temperatureMap.invalidateSize();
                if (co2Map) co2Map.invalidateSize();
            }
        });
    });

    // Temperature Chart config
    var temperatureOptions = {
        chart: {
            type: 'line',
            height: 350,
            zoom: { enabled: false },
            animations: { enabled: true }
        },
        series: [
            { name: 'Data 1', data: @json($temperatureData['data1']) },
            { name: 'Data 2', data: @json($temperatureData['data2']) },
            { name: 'Data 3', data: @json($temperatureData['data3']) },
            { name: 'Data 4', data: @json($temperatureData['data4']) },
            { name: 'Average', data: @json($temperatureData['average']) }
        ],
        xaxis: {
            categories: @json($temperatureData['labels']),
            title: { 
                text: 'Tahun',
                style: {
                    fontSize: '14px',
                    color: '#f2f2f2',
                    fontWeight: 'bold',
                    fontFamily: 'Arial, sans-serif',
                }
            }
        },
        yaxis: {
            title: { 
                text: 'Suhu (°C)',
                style: {
                    fontSize: '14px',
                    color: '#f2f2f2',
                    fontWeight: 'bold',
                    fontFamily: 'Arial, sans-serif',
                }
            }
        },
        stroke: {
            curve: 'straight',
            width: 2
        },
        colors: ['#FF5733', '#C70039', '#FFC107', '#2196F3', '#FFD700'],
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right',
            markers: {
                fillColors: ['#FF5733', '#C70039', '#FFC107', '#2196F3', '#FFD700']
            }
        },
        grid: {
            borderColor: '#f1f1f1'
        }
    };

    var temperatureChart = new ApexCharts(document.querySelector("#temperatureChart"), temperatureOptions);
    temperatureChart.render();

    // CO2 Chart config
    var co2Options = {
        chart: {
            type: 'line',
            height: 350,
            zoom: { enabled: false },
            animations: { enabled: true }
        },
        series: [
            { name: 'Data 1', data: @json($co2Data['data1']) },
            { name: 'Data 2', data: @json($co2Data['data2']) },
            { name: 'Data 3', data: @json($co2Data['data3']) },
            { name: 'Data 4', data: @json($co2Data['data4']) },
            { name: 'Average', data: @json($co2Data['average']) }
        ],
        xaxis: {
            categories: @json($co2Data['labels']),
            title: { text: 'Tahun' }
        },
        yaxis: {
            title: { text: 'CO2 (ppm)' }
        },
        stroke: {
            curve: 'straight',
            width: 2
        },
        colors: ['#FF5733', '#C70039', '#FFC107', '#2196F3', '#FFD700'],
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right',
            markers: {
                fillColors: ['#FF5733', '#C70039', '#FFC107', '#2196F3', '#FFD700']
            }
        },
        grid: {
            borderColor: '#f1f1f1'
        }
    };

    var co2Chart = new ApexCharts(document.querySelector("#co2Chart"), co2Options);
    co2Chart.render();

    // Temperature Heatmap Map
    var temperatureMap = L.map('temperatureMap').setView([-1.8500, 109.9667], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(temperatureMap);

    var temperaturePoints = [];
    var tempSeries = [
        @json($temperatureData['data1']),
        @json($temperatureData['data2']),
        @json($temperatureData['data3']),
        @json($temperatureData['data4']),
        @json($temperatureData['average'])
    ];
    var coords = @json($temperatureData['coords']);
    tempSeries.forEach(function(series) {
        series.forEach(function(value, index) {
            if (coords[index]) {
                temperaturePoints.push([coords[index].lat, coords[index].lng, value / 40]);
            }
        });
    });

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

    // CO2 Heatmap Map
    var co2Map = L.map('co2Map').setView([-1.8500, 109.9667], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(co2Map);

    var co2Points = [];
    var co2Series = [
        @json($co2Data['data1']),
        @json($co2Data['data2']),
        @json($co2Data['data3']),
        @json($co2Data['data4']),
        @json($co2Data['average'])
    ];
    var co2Coords = @json($co2Data['coords']);
    co2Series.forEach(function(series) {
        series.forEach(function(value, index) {
            if (co2Coords[index]) {
                co2Points.push([co2Coords[index].lat, co2Coords[index].lng, value / 500]);
            }
        });
    });

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
</script>
@endpush
</div>
