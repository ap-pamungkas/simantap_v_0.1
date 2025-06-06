<div>
    <div>

        @livewireStyles
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        
        <style>
            #map {
                height: 600px;
                width: 100%;
                border-radius: 12px;
                box-shadow: 0 8px 32px rgba(0,0,0,0.1);
                border: 1px solid rgba(255,255,255,0.2);
            }
            
            .officer-marker {
                border: 4px solid #fff;
                border-radius: 50%;
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                transition: transform 0.2s ease;
            }
            
            .officer-marker:hover {
                transform: scale(1.1);
            }
            
            /* Enhanced popup styles */
            .leaflet-popup-content-wrapper {
                background: linear-gradient(135deg, #34363d 0%, #4b6ea2 100%);
                color: white;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.3);
                border: 1px solid rgba(255,255,255,0.2);
                backdrop-filter: blur(10px);
            }
            
            .leaflet-popup-content {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            
            .leaflet-popup-tip {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: 1px solid rgba(255,255,255,0.2);
            }
            
            .officer-popup {
                padding: 20px;
                text-align: center;
                min-width: 250px;
            }
            
            .officer-avatar {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                margin: 0 auto 15px;
                border: 4px solid rgba(255,255,255,0.3);
                box-shadow: 0 4px 20px rgba(0,0,0,0.2);
                display: block;
            }
            
            .officer-name {
                font-size: 22px;
                font-weight: bold;
                margin: 0 0 5px 0;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }
            
            .officer-id {
                font-size: 14px;
                opacity: 0.8;
                background: rgba(255,255,255,0.2);
                padding: 4px 12px;
                border-radius: 20px;
                display: inline-block;
                margin-bottom: 15px;
            }
            
            .info-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                margin-top: 15px;
            }
            
            .info-item {
                background: rgba(255,255,255,0.15);
                padding: 10px;
                border-radius: 8px;
                backdrop-filter: blur(5px);
            }
            
            .info-label {
                font-size: 12px;
                opacity: 0.8;
                margin-bottom: 3px;
            }
            
            .info-value {
                font-size: 14px;
                font-weight: bold;
            }
            
            .coordinates {
                margin-top: 15px;
                padding: 10px;
                background: rgba(0,0,0,0.2);
                border-radius: 8px;
                font-size: 12px;
                font-family: monospace;
            }
            
            /* Layer control styling */
            .leaflet-control-layers {
                border-radius: 10px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                border: 1px solid rgba(255,255,255,0.2);
            }
        </style>
    
        <!-- Map container -->
        <div id="map" class="my-4"></div>
    
        @livewireScripts
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Map initialization function
    function initMap(position) {
        const map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 20);
        
        // Map layer configuration
        const mapLayers = {
            baseLayerConfig: {
                "OpenStreetMap": {
                    url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                },
                "Satellite": {
                    url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                },
                "Dark": {
                    url: 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
                },
                "Light": {
                    url: 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
                },
                "Terrain": {
                    url: 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
                    attribution: 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
                }
            }
        };

        // Initialize base layers
        const baseLayers = {};
        Object.entries(mapLayers.baseLayerConfig).forEach(([name, config]) => {
            baseLayers[name] = L.tileLayer(config.url, { attribution: config.attribution });
        });

        // Add default layer and controls
        baseLayers["OpenStreetMap"].addTo(map);
        L.control.layers(baseLayers).addTo(map);
        L.control.scale().addTo(map);

        return map;
    }

    // Current location marker configuration
    function addCurrentLocationMarker(map, position) {
        const currentLocationIcon = L.divIcon({
            html: `<div style="
                background-color: #2196F3;
                border: 3px solid white;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                box-shadow: 0 0 10px rgba(33,150,243,0.5);
            "></div>`,
            className: '',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        const currentLocation = L.marker([position.coords.latitude, position.coords.longitude], {
            icon: currentLocationIcon
        }).addTo(map);

        // Add radius circle
        L.circle([position.coords.latitude, position.coords.longitude], {
            color: '#2196F3',
            fillColor: '#2196F3',
            fillOpacity: 0.1,
            radius: 6000
        }).addTo(map);

        // Add popup
        currentLocation.bindPopup(`
            <div class="officer-popup">
                <h3 class="officer-name">Lokasi Anda</h3>
                <div class="coordinates">
                    📍 ${position.coords.latitude.toFixed(4)}, ${position.coords.longitude.toFixed(4)}
                </div>
            </div>
        `);
    }

    // Officer marker configuration
    function addOfficerMarkers(map, position) {
        const officers = [
            {
                name: 'Officer Ahmad Rizki',
                no_seri: 'PTG001',
                position: [position.coords.latitude + 0.001, position.coords.longitude + 0.001],
                image: 'https://randomuser.me/api/portraits/men/1.jpg',
                temperature: '32°C',
                airQuality: 'Baik',
                status: 'Online',
                lastUpdate: '2 menit yang lalu'
            },
            {
                name: 'Officer Sari Dewi',
                no_seri: 'PTG002',
                position: [position.coords.latitude - 0.001, position.coords.longitude + 0.001],
                image: 'https://randomuser.me/api/portraits/women/2.jpg',
                temperature: '30°C',
                airQuality: 'Sedang',
                status: 'Online',
                lastUpdate: '5 menit yang lalu'
            },
            {
                name: 'Officer Budi Santoso',
                no_seri: 'PTG003',
                position: [position.coords.latitude, position.coords.longitude - 0.001],
                image: 'https://randomuser.me/api/portraits/men/3.jpg',
                temperature: '33°C',
                airQuality: 'Baik',
                status: 'Online',
                lastUpdate: '1 menit yang lalu'
            }
        ];

        officers.forEach(officer => createOfficerMarker(map, officer));
    }

    // Create individual officer marker
    function createOfficerMarker(map, officer) {
        const officerIcon = L.divIcon({
            html: `<img src="${officer.image}" width="50" height="50" class="officer-marker">`,
            className: '',
            iconSize: [50, 50],
            iconAnchor: [25, 25]
        });

        L.marker(officer.position, {icon: officerIcon})
            .bindPopup(createOfficerPopup(officer), {
                maxWidth: 300,
                className: 'custom-popup'
            })
            .addTo(map);
    }

    // Create officer popup content
    function createOfficerPopup(officer) {
        const getAirQualityColor = quality => {
            const colors = {
                'baik': '#4CAF50',
                'sedang': '#FF9800',
                'buruk': '#F44336'
            };
            return colors[quality.toLowerCase()] || '#9E9E9E';
        };

        return `
            <div class="officer-popup">
                <img src="${officer.image}" class="officer-avatar" alt="${officer.name}">
                <h3 class="officer-name">${officer.name}</h3>
                <div class="officer-id">${officer.no_seri}</div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Suhu</div>
                        <div class="info-value">${officer.temperature}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Kualitas Udara</div>
                        <div class="info-value" style="color: ${getAirQualityColor(officer.airQuality)}">
                            ${officer.airQuality}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value" style="color: #4CAF50">
                            ● ${officer.status}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Update Terakhir</div>
                        <div class="info-value">${officer.lastUpdate}</div>
                    </div>
                </div>
                
                <div class="coordinates">
                    📍 ${officer.position[0].toFixed(4)}, ${officer.position[1].toFixed(4)}
                </div>
            </div>
        `;
    }

    // Initialize application
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const map = initMap(position);
            addCurrentLocationMarker(map, position);
            addOfficerMarkers(map, position);
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
</script>
    </div>
</div>