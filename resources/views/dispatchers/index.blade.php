@extends('layouts.app')
@push('styles')
    <style>
        .info-panel {
            position: absolute;
            bottom: 20px;
            left: 20px;
            max-width: 260px;
            z-index: 500;
            transform: translateX(-110%);
            transition: transform 0.4s ease-in-out;
        }

        .info-panel.show {
            transform: translateX(0);
        }

        .info-card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 16px;
            backdrop-filter: blur(4px);
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 20px;
            float: right;
            cursor: pointer;
            color: #333;
        }

        .info-card h4 {
            margin-top: 0;
        }

        .info-card p {
            font-size: 13px;
            margin: 4px 0;
        }
    </style>

    <style>
        #statusCard {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.85);
            border-radius: 10px;
            padding: 8px 16px;
            display: flex;
            gap: 16px;
            z-index: 1000;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            font-size: 14px;
        }

        .status-item {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .status-item.online span {
            color: green;
            font-weight: bold;
        }

        .status-item.offline span {
            color: red;
            font-weight: bold;
        }

        :fullscreen #infoPanel,
        :-webkit-full-screen #infoPanel {
            top: 60px;
            left: 10px;
        }

        :fullscreen #statusCard,
        :-webkit-full-screen #statusCard {
            top: 10px;
        }
    </style>

    <style>
        html,
        body,
        #map {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #map:fullscreen,
        #map:-webkit-full-screen,
        #map:-moz-full-screen,
        #map:-ms-fullscreen {
            height: 100% !important;
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-xl-4">
                            <div class="page-title-content">
                                <h3>Data Lokasi Lapangan OPD</h3>
                                <p class="mb-2">Data List Lokasi Anggota Lapangan OPD</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12">
                <div id="statusCard">
                    <div class="status-item online">
                        <strong>Online:</strong> <span id="onlineCount">0</span>
                    </div>
                    <div class="status-item offline">
                        <strong>Offline:</strong> <span id="offlineCount">0</span>
                    </div>
                </div>
                <div id="map" style="height: 600px; width: 100%;">
                    <button id="toggleFullscreen"
                        style="
                            position: absolute;
                            top: 10px;
                            right: 10px;
                            z-index: 1000;
                            padding: 8px 12px;
                            background: rgba(0,0,0,0.6);
                            color: white;
                            border: none;
                            border-radius: 6px;
                            cursor: pointer;
                        ">
                        ⛶ Fullscreen
                    </button>

                    <div id="infoPanel" class="info-panel">
                        <div class="info-card">
                            <button onclick="closePanel()" class="close-btn">&times;</button>
                            <div id="panelContent">
                                <h4>Detail Anggota</h4>
                                <p>Silakan klik marker untuk melihat detail anggota OPD.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.css" />
    <script src="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.getElementById('toggleFullscreen').addEventListener('click', function() {
            const mapElement = document.getElementById('map');

            if (!document.fullscreenElement) {
                if (mapElement.requestFullscreen) {
                    mapElement.requestFullscreen();
                } else if (mapElement.webkitRequestFullscreen) {
                    mapElement.webkitRequestFullscreen();
                } else if (mapElement.msRequestFullscreen) {
                    mapElement.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        });
    </script>

    <script>
        let map;
        let markers = {};

        document.addEventListener("DOMContentLoaded", function() {
            map = L.map('map', {
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: 'topright'
                }
            }).setView([-6.25, 106.99], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            fetchAndDisplayLocations();
            setInterval(fetchAndDisplayLocations, 30000);
        });

        function isOutsideBekasi(lat, lng) {
            return lat < -6.45 || lat > -6.2 || lng < 106.85 || lng > 107.05;
        }

        function groupByArea(data) {
            const groups = {
                'Dalam Kota Bekasi': [],
                'Luar Kota Bekasi': []
            };

            data.forEach(item => {
                if (!item.location || item.location === "0,0") return;
                const [lat, lng] = item.location.split(',').map(Number);
                const groupKey = isOutsideBekasi(lat, lng) ? 'Luar Kota Bekasi' : 'Dalam Kota Bekasi';
                groups[groupKey].push({
                    ...item,
                    lat,
                    lng
                });
            });

            return groups;
        }

        function fetchAndDisplayLocations() {
            axios.get('/dispatchers/list')
                .then(response => {
                    if (response.data.success) {
                        const grouped = groupByArea(response.data.data);
                        Object.values(markers).forEach(marker => map.removeLayer(marker));
                        markers = {};

                        let onlineCount = 0;
                        let offlineCount = 0;

                        Object.entries(grouped).forEach(([area, items]) => {
                            items.forEach(item => {
                                const key = item.id;
                                const iconUrl = item.online === 'online' ?
                                    'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png' :
                                    'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png';

                                const marker = L.marker([item.lat, item.lng], {
                                    icon: L.icon({
                                        iconUrl,
                                        shadowUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-shadow.png',
                                        iconSize: [25, 41],
                                        iconAnchor: [12, 41],
                                        popupAnchor: [1, -34],
                                        shadowSize: [41, 41]
                                    })
                                }).addTo(map);

                                marker.bindTooltip(item.name, {
                                    permanent: true,
                                    direction: 'top',
                                    offset: [0, -10],
                                    className: 'marker-label'
                                });

                                marker.on('click', () => showInfoPanel(item, area));

                                markers[key] = marker;

                                if (item.online === 'online') onlineCount++;
                                else offlineCount++;
                            });
                        });

                        document.getElementById('onlineCount').textContent = onlineCount;
                        document.getElementById('offlineCount').textContent = offlineCount;
                    }
                })
                .catch(err => {
                    console.error('Gagal mengambil data lokasi:', err);
                });
        }

        function showInfoPanel(item, area) {
            const panel = document.getElementById('infoPanel');
            const content = document.getElementById('panelContent');

            content.innerHTML = `
            <h4>${item.name}</h4>
            <p><strong>Email:</strong> ${item.email}</p>
            <p><strong>Dinas:</strong> ${item.dinas_name}</p>
            <p><strong>Status:</strong> <span style="color:${item.online === 'online' ? 'green' : 'red'}">${item.online}</span></p>
            <p><strong>Area:</strong> ${area}</p>
            <p><strong>Koordinat:</strong> ${item.lat}, ${item.lng}</p>
            <p><strong>Update terakhir:</strong> ${item.updated_at || 'Belum tersedia'}</p>
        `;

            panel.classList.add('show');
        }

        function closePanel() {
            document.getElementById('infoPanel').classList.remove('show');
        }
    </script>
@endsection
