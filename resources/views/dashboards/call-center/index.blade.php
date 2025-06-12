@extends('layouts.app')

@push('styles')
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .card-placeholder {
            height: 1.5rem;
            border-radius: 0.5rem;
            background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 37%, #e0e0e0 63%);
            background-size: 400% 100%;
            animation: skeleton-loading 1.4s ease infinite;
        }

        @keyframes skeleton-loading {
            0% {
                background-position: 100% 0;
            }

            100% {
                background-position: -100% 0;
            }
        }
    </style>
    @include('dashboards.call-center.partials.styles.main')
@endpush
@section('content')
    <div class="container">
        <div class="row align-items-center justify-content-between mb-3">
            <div class="col-xl-4 col-md-6">
                <div class="page-title-content">
                    <h3>Dashboard 112</h3>
                    <p class="mb-2">Selamat Datang di Dashboard 112</p>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 text-end">
                <label for="daterange" class="form-label fw-semibold mb-1" style="display: block;">
                    Filter Tanggal
                </label>
                <input type="text" id="daterange" class="form-control rounded-pill"
                    style="cursor: pointer; max-width: 250px; margin-left: auto;" readonly />
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="settings-menu">
                    <a href="settings.html" class="active">Dashboard 112</a>
                    <a href="settings-general.html">Dashboard Omni Channel</a>
                </div>
            </div>
        </div>

        <div class="row align-items-stretch mb-5">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="card shadow-sm p-4 h-100">
                    <div id="speedometer-container">
                        <!-- Skeleton loader sementara -->
                        <div class="mb-4">
                            <div id="gaugeChart" class="w-100" style="height: 320px; border-radius: 8px;"></div>
                        </div>
                        <div class="row text-center g-3">
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light">
                                    <h6>Total</h6>
                                    <h4 id="total" class="placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light">
                                    <h6>Active</h6>
                                    <h4 id="active" class="placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light">
                                    <h6>Handling</h6>
                                    <h4 id="handling" class="placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 border rounded bg-light">
                                    <h6>Closed</h6>
                                    <h4 id="closed" class="placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div id="" style="width: 100%; height: 250px; display: none;"></div> --}}
                </div>
            </div>


            <!-- Kolom Kanan -->
            <div class="col-6">
                <div class="card shadow-sm p-4 h-100">
                    <h5 class="mb-4">Total Tipe Laporan</h5>
                    <div id="total-type-report-container" class="row g-3">

                    </div>
                </div>
            </div>

        </div>



        <div class="row">
            @include('dashboards.call-center.partials.card.card-report-incident')
            @include('dashboards.call-center.partials.card.card-chart-report-incident')
        </div>
        <div class="row">
            @include('dashboards.call-center.partials.card.card-top-categories')
            @include('dashboards.call-center.partials.card.card-top-districts')

            <div class="col-xl-6">
                <div class="card shadow-sm rounded-2xl flex flex-col h-full">
                    <div class="card-header flex justify-between items-center border-b px-4 py-3">
                        <h4 class="card-title text-base font-semibold text-gray-800">Top 5 Incident By OPD</h4>
                    </div>
                    <div class="card-body px-4 py-3 flex-grow">
                        <div class="overflow-x-auto">
                            <table class="table min-w-full text-sm text-left border-collapse">
                                <thead class="text-xs text-gray-500 uppercase border-b">
                                    <tr>
                                        <th class="py-2 pr-4">OPD</th>
                                        <th class="py-2 pr-4 text-center">Total</th>
                                        <th class="py-2 pr-4 text-center">Closed`</th>
                                        <th class="py-2 text-center">Open</th>
                                    </tr>
                                </thead>
                                <tbody id="top5-dinas-list" class="divide-y divide-gray-100">
                                    <!-- JS inject -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card shadow-sm rounded-2xl">
                    <div class="card-header flex justify-between items-center border-b px-4 py-3">
                        <h4 class="card-title text-base font-semibold text-gray-800">Top 5 Most Responsive OPD</h4>
                    </div>
                    <div class="card-body px-4 py-3">
                        <div class="overflow-x-auto">
                            <table class="table min-w-full text-sm text-left border-collapse">
                                <thead class="text-xs text-gray-500 uppercase border-b">
                                    <tr>
                                        <th class="py-2 pr-4 font-medium max-w-[150px]">Dinas</th>
                                        <th class="py-2 pr-4 font-medium text-center">Total Response</th>
                                        <th class="py-2 font-medium text-center">AVG Response (second)</th>
                                    </tr>
                                </thead>
                                <tbody id="top5-responsive-dinas-list" class="divide-y divide-gray-100">
                                    <!-- Data inject by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('dashboards.call-center.partials.card.card-graphic-telphone')
        @include('dashboards.call-center.partials.card.card-list-user')
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
    @include('dashboards.call-center.partials.scripts.data-dispatcher')
    @include('dashboards.call-center.partials.scripts.chart-report-incident')
    @include('dashboards.call-center.partials.scripts.chart-report-daily')
    @include('dashboards.call-center.partials.scripts.chart-graphic-telphone')

    <script>
        $(function() {
            const start = moment().startOf('day');
            const end = moment().endOf('day');

            fetchDataCardIncident(start, end);
            fetchCdrHourlyChart(start, end);
            fetchTop5Categories(start, end);
            fetchChartData(start, end);
            fetchTop5Districts(start, end);
            fetchTop5Dinas(start, end);
            fetchTop5ResponsiveDinas(start, end);
            fetchSpeedmeterData(start, end);
            fetchTotalTypeReport(start, end);

            $('#daterange').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                },
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, function(start, end) {
                fetchTop5Categories(start, end);
                fetchChartData(start, end);
                fetchDataCardIncident(start, end);
                fetchTop5Districts(start, end);
                fetchTop5Dinas(start, end);
                fetchTop5ResponsiveDinas(start, end);
                fetchCdrHourlyChart(start, end);
                fetchSpeedmeterData(start, end);
                fetchTotalTypeReport(start, end);
            });
        });
    </script>


    <script>
        function fetchDataCardIncident(startDate, endDate) {
            const params = {};
            if (startDate && endDate) {
                params.start_date = startDate.format('YYYY-MM-DD');
                params.end_date = endDate.format('YYYY-MM-DD');
            }

            axios.get('/dashboard/call-center/data-card-incident', {
                    params
                })
                .then(function(response) {
                    const data = response.data;

                    function updateValue(id, value) {
                        const el = document.getElementById(id);
                        if (el) el.textContent = value;
                    }

                    updateValue('statusBaru', data.status_baru);
                    updateValue('statusDiproses', data.status_diproses);
                    updateValue('statusSelesai', data.status_selesai);
                    updateValue('totalLaporan', data.total);
                })
                .catch(function(error) {
                    console.error('Error fetching data:', error);
                });
        }
    </script>

    <script>
        function truncateText(text = '', maxLength = 30) {
            if (!text) return '-';
            return text.length > maxLength ? text.slice(0, maxLength - 3) + '...' : text;
        }

        function formatSeconds(seconds) {
            if (seconds === null || seconds === undefined) return '-';
            const num = Number(seconds);
            if (isNaN(num)) return '-';

            if (num < 60) return num.toFixed(0) + 's';

            const mins = Math.floor(num / 60);
            const secs = (num % 60).toFixed(0).padStart(2, '0');

            return `${mins}m ${secs}s`;
        }


        function renderTop5Categories(data) {
            const list = document.getElementById('top5-category-list');
            list.innerHTML = '';

            const totalKeseluruhan = data.reduce((acc, item) => acc + Number(item.total), 0);
            console.log('Total keseluruhan:', totalKeseluruhan);

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.classList.add('hover:bg-gray-50', 'transition-colors');

                const persentase = totalKeseluruhan > 0 ?
                    ((Number(item.total) / totalKeseluruhan) * 100).toFixed(1) + '%' :
                    '0%';

                tr.innerHTML = `
            <td class="py-2 pr-4 max-w-[180px] truncate" title="${item.category}">${truncateText(item.category)}</td>
            <td class="py-2 pr-4 text-center font-semibold text-gray-800" title="Total: ${item.total}">${persentase}</td>
            <td class="py-2 pr-4 text-center text-green-600 font-medium">${item.selesai_count}</td>
            <td class="py-2 text-center text-yellow-500 font-medium">${item.proses_count}</td>
        `;
                list.appendChild(tr);
            });
        }



        function renderTop5Districts(data) {
            const list = document.getElementById('top5-district-list');
            list.innerHTML = '';

            // Pastikan totalnya angka, supaya reduce akurat
            const totalKeseluruhan = data.reduce((acc, item) => acc + Number(item.total), 0);

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.classList.add('hover:bg-gray-50', 'transition');

                const totalItem = Number(item.total);
                const persentase = totalKeseluruhan > 0 ?
                    ((totalItem / totalKeseluruhan) * 100).toFixed(1) + '%' :
                    '0%';

                tr.innerHTML = `
            <td class="py-2 pr-4 max-w-[180px] truncate" title="${item.district}">${truncateText(item.district)}</td>
            <td class="py-2 pr-4 text-center font-semibold text-gray-800">${persentase}</td>
            <td class="py-2 pr-4 text-center text-green-600 font-medium">${item.selesai_count}</td>
            <td class="py-2 text-center text-yellow-500 font-medium">${item.proses_count}</td>
        `;
                list.appendChild(tr);
            });
        }

        function renderTop5Dinas(data) {
            const list = document.getElementById('top5-dinas-list');
            list.innerHTML = '';

            // Pastikan totalnya angka supaya akurat
            const totalKeseluruhan = data.reduce((acc, item) => acc + Number(item.total), 0);

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.classList.add('hover:bg-gray-50');

                const totalItem = Number(item.total);
                const persentase = totalKeseluruhan > 0 ?
                    ((totalItem / totalKeseluruhan) * 100).toFixed(1) + '%' :
                    '0%';

                tr.innerHTML = `
            <td class="py-2 pr-4 max-w-[160px] truncate" title="${item.dinas}">${truncateText(item.dinas, 30)}</td>
            <td class="py-2 pr-4 text-center font-semibold text-gray-800">${persentase}</td>
            <td class="py-2 pr-4 text-center text-gray-600">${item.selesai_count}</td>
            <td class="py-2 text-center text-gray-600">${item.proses_count}</td>
        `;
                list.appendChild(tr);
            });
        }


        function renderTop5ResponsiveDinas(data) {
            const tbody = document.getElementById('top5-responsive-dinas-list');
            tbody.innerHTML = '';

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.classList.add('hover:bg-gray-50');
                tr.innerHTML = `
        <td class="py-2 pr-4 max-w-[150px] truncate" title="${item.dinas}">${truncateText(item.dinas, 25)}</td>
        <td class="py-2 pr-4 text-center font-semibold text-gray-800">${item.total_responses ?? '-'}</td>
        <td class="py-2 text-center text-gray-600">${formatSeconds(item.avg_response_time_seconds)}</td>
      `;
                tbody.appendChild(tr);
            });
        }

        // FETCH FUNCTIONS
        async function fetchTop5Categories(startDate, endDate) {
            const params = {};
            if (startDate && endDate) {
                params.start_date = startDate.format('YYYY-MM-DD');
                params.end_date = endDate.format('YYYY-MM-DD');
            }
            try {
                const response = await axios.get('/dashboard/call-center/top5-incident-by-categories', {
                    params
                });
                renderTop5Categories(response.data);
            } catch (err) {
                console.error('Gagal fetch data top 5 kategori:', err);
            }
        }

        async function fetchTop5Districts(startDate, endDate) {
            const params = {};
            if (startDate && endDate) {
                params.start_date = startDate.format('YYYY-MM-DD');
                params.end_date = endDate.format('YYYY-MM-DD');
            }
            try {
                const response = await axios.get('/dashboard/call-center/top5-incident-by-districts', {
                    params
                });
                renderTop5Districts(response.data);
            } catch (err) {
                console.error('Gagal fetch data top 5 kecamatan:', err);
            }
        }

        async function fetchTop5Dinas(startDate, endDate) {
            const params = {};
            if (startDate && endDate) {
                params.start_date = startDate.format('YYYY-MM-DD');
                params.end_date = endDate.format('YYYY-MM-DD');
            }
            try {
                const response = await axios.get('/dashboard/call-center/top5-incident-by-dinas', {
                    params
                });
                renderTop5Dinas(response.data);
            } catch (err) {
                console.error('Gagal fetch data top 5 dinas:', err);
            }
        }

        async function fetchTop5ResponsiveDinas(startDate, endDate) {
            const params = {};
            if (startDate && endDate) {
                params.start_date = startDate.format('YYYY-MM-DD');
                params.end_date = endDate.format('YYYY-MM-DD');
            }
            try {
                const response = await axios.get('/dashboard/call-center/top5-responsive-dinas', {
                    params
                });
                renderTop5ResponsiveDinas(response.data);
            } catch (err) {
                console.error('Gagal fetch data top 5 dinas responsif:', err);
            }
        }

        const chartDom = document.getElementById('gaugeChart');
        const myChart = echarts.init(chartDom);

        function renderSpeedometer(serviceLevel) {
            chartDom.style.display = 'block';

            const option = {
                title: {
                    text: 'Service Level',
                    left: 'center',
                    top: 10,
                    textStyle: {
                        fontSize: 18,
                        fontWeight: 'bold'
                    }
                },
                series: [{
                    type: 'gauge',
                    startAngle: 180,
                    endAngle: 0,
                    center: ['50%', '75%'],
                    radius: '90%',
                    min: 0,
                    max: 100,
                    splitNumber: 10,
                    axisLine: {
                        lineStyle: {
                            width: 15,
                            color: [
                                [0.4, '#d9534f'],
                                [0.7, '#f0ad4e'],
                                [1, '#5cb85c']
                            ]
                        }
                    },
                    pointer: {
                        length: '60%',
                        width: 6
                    },
                    axisTick: {
                        show: false
                    },
                    splitLine: {
                        show: false
                    },
                    axisLabel: {
                        show: true,
                        distance: -20,
                        fontSize: 10
                    },
                    detail: {
                        formatter: '{value} %',
                        fontSize: 20,
                        offsetCenter: [0, '-20%'],
                        color: '#333'
                    },
                    data: [{
                        value: serviceLevel
                    }]
                }]
            };

            myChart.setOption(option);
        }

        function updateStatCards(data) {
            document.getElementById('total').innerHTML = data.total ?? 0;
            document.getElementById('active').innerHTML = data.active ?? 0;
            document.getElementById('handling').innerHTML = data.handling ?? 0;
            document.getElementById('closed').innerHTML = data.closed ?? 0;
        }

        async function fetchSpeedmeterData(startDate, endDate) {
            // Set placeholder dulu
            renderSkeletonStats();

            const params = {};
            if (startDate && endDate) {
                params.start_date = startDate.format('YYYY-MM-DD');
                params.end_date = endDate.format('YYYY-MM-DD');
            }

            try {
                const response = await axios.get('/dashboard/call-center/speedmeter-data', {
                    params
                });
                const data = response.data.data;

                updateStatCards(data);
                renderSpeedometer(data.service_level ?? 0);
            } catch (err) {
                console.error('Gagal ambil data speedmeter:', err);
                updateStatCards({
                    total: 0,
                    active: 0,
                    handling: 0,
                    closed: 0
                });
                renderSpeedometer(0);
            }
        }

        function renderSkeletonStats() {
            ['total', 'active', 'handling', 'closed'].forEach(id => {
                document.getElementById(id).innerHTML = `<span class="placeholder col-6"></span>`;
            });

            chartDom.style.display = 'none';
        }

        async function fetchTotalTypeReport(startDate, endDate) {
            const container = document.getElementById('total-type-report-container');
            renderLoadingSkeleton(container); // Tampilkan loading dulu

            const params = {};
            if (startDate && endDate) {
                params.start_date = startDate.format('YYYY-MM-DD');
                params.end_date = endDate.format('YYYY-MM-DD');
            }

            try {
                const response = await axios.get('/dashboard/call-center/total-type-report', {
                    params
                });
                renderTotalTypeReport(response.data.data); // Ambil langsung objek `data`
            } catch (err) {
                console.error('Gagal fetch data total type report:', err);
                container.innerHTML = '<div class="text-danger">Gagal mengambil data.</div>';
            }
        }

        function renderTotalTypeReport(data) {
            const container = document.getElementById('total-type-report-container');
            container.innerHTML = ''; // Kosongkan skeleton

            // Card Total (besar, 1 baris penuh)
            const totalCol = document.createElement('div');
            totalCol.className = 'col-12';

            totalCol.innerHTML = `
                <div class="card border-primary shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Total</h5>
                        <h1 class="display-4 fw-bold">${data.total}</h1>
                    </div>
                </div>
            `;
            container.appendChild(totalCol);

            // Cards lain (berdampingan)
            const subCards = [{
                    label: 'Normal',
                    value: data.normal,
                    color: 'success'
                },
                {
                    label: 'Prank',
                    value: data.prank,
                    color: 'danger'
                },
                {
                    label: 'Ghost',
                    value: data.ghost,
                    color: 'warning'
                },
                {
                    label: 'Info',
                    value: data.info,
                    color: 'info'
                },
            ];

            subCards.forEach(card => {
                const col = document.createElement('div');
                col.className = 'col-6 col-md-6';

                col.innerHTML = `
                    <div class="card border-${card.color} shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="card-title text-${card.color}">${card.label}</h6>
                            <h3 class="fw-bold">${card.value}</h3>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            });
        }

        function renderLoadingSkeleton(container) {
            container.innerHTML = '';
            for (let i = 0; i < 5; i++) {
                const col = document.createElement('div');
                col.className = 'col-12 col-sm-6 col-md-6 col-lg-6';

                col.innerHTML = `
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-placeholder w-50 mb-2"></div>
                            <div class="card-placeholder w-100"></div>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            }
        }
    </script>
@endpush
