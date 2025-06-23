@extends('layouts.app')

@push('styles')
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
    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 400px;
            max-width: 700px;
            margin: 2em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 700px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-description {
            margin: 0.3rem 10px;
        }


        .summary-card {
            background: linear-gradient(135deg, #065f46 0%, #34d399 100%);

            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
            width: 100%;
            /* max-width: 1000px; */
            /* margin: 0 auto; */
        }

        .summary-card-body {
            padding: 20px;
            color: white;
            display: flex;
            /* gap: 20px; */
            align-items: center;
        }

        .summary-data-section {
            /* flex: 1; */
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
        }

        .summary-data-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-data-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .summary-data-text {
            flex: 1;
        }

        .summary-data-label {
            font-size: clamp(1rem, 1.5vw, 1.2rem);
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2px;
            font-weight: 500;
        }

        .summary-data-value {
            font-size: clamp(1.5rem, 2vw, 1.75rem);
            font-weight: 700;
            color: #ffffff;
        }

        .summary-speedometer-section {
            /* flex: 0 0 400px; */
            width: 100%;
            marign-left: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #summary-speedometer-chart {
            max-height: 450px;
            max-width: 400px;
        }

        @media (max-width: 991.98px) {
            .summary-card {
                max-width: 800px;
            }

            .summary-card-body {
                flex-direction: column;
                padding: 15px;
            }

            .summary-data-section {
                width: 100%;
                padding: 10px;
                flex-direction: row;
                flex-wrap: nowrap;
                gap: 10px;
                justify-content: space-between;
            }

            .summary-data-item {
                flex: 1;
                flex-direction: column;
                align-items: center;
                text-align: center;
                min-width: 100px;
            }

            .summary-data-text {
                flex: none;
            }

            .summary-data-label {
                font-size: 1rem;
            }

            .summary-data-value {
                font-size: 1.5rem;
            }

            .summary-speedometer-section {
                flex: 0 0 auto;
                width: 100%;
                padding-right: 0;
            }

            #summary-speedometer-chart {
                max-height: 300px;
                max-width: 350px;
            }
        }

        @media (max-width: 575.98px) {
            .summary-card {
                max-width: 100%;
            }

            .summary-data-section {
                flex-wrap: wrap;
                gap: 8px;
            }

            .summary-data-item {
                min-width: 45%;
            }

            .summary-data-label {
                font-size: 0.9rem;
            }

            .summary-data-value {
                font-size: 1.2rem;
            }

            .summary-data-icon {
                width: 35px;
                height: 35px;
                font-size: 1.2rem;
            }

            #summary-speedometer-chart {
                max-height: 250px;
                max-width: 300px;
            }
        }




        .incident-summary-card {
            background: #ffffff;
            border: none;
            border-radius: 10px;
            width: 100%;
            /* max-width: 1400px; */
            margin: 0 auto;
            padding: 15px;
        }

        .incident-summary-card h3 {
            font-weight: 700;
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            margin-bottom: 15px;
            color: #1e40af;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .incident-summary-grid {
            display: flex;
            flex-wrap: nowrap;
            gap: 10px;
            justify-content: space-between;
        }

        .incident-item {
            flex: 1;
            background: #f9fafb;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            padding: 10px;
            min-width: 120px;
        }

        .incident-item-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .incident-item i {
            font-size: clamp(1.5rem, 3vw, 2rem);
        }

        .incident-item h3 {
            font-size: clamp(1.2rem, 2vw, 1.5rem);
            font-weight: 700;
            margin: 0;
            color: #1f2937;
        }

        .incident-item p {
            font-size: clamp(0.8rem, 1.5vw, 1rem);
            color: #6b7280;
            margin: 0;
        }

        @media (max-width: 1200px) {
            .incident-summary-card {
                max-width: 100%;
            }

            .incident-summary-grid {
                flex-wrap: wrap;
                gap: 8px;
            }

            .incident-item {
                min-width: 48%;
            }

            .incident-item h3 {
                font-size: 1.2rem;
            }

            .incident-item p {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 767.98px) {
            .incident-item {
                min-width: 100%;
            }

            .incident-item h3 {
                font-size: 1.1rem;
            }

            .incident-item p {
                font-size: 0.8rem;
            }
        }
    </style>
    @include('dashboards.call-center.partials.styles.main')
@endpush
@section('content')
    <div class="container">

        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-xl-4 col-md-6 mb-3 mb-md-0">
                <div class="page-title-content">
                    <h3 class="mb-1 fw-bold">Dashboard 112</h3>
                    <p class="text-muted mb-0">Selamat Datang di Dashboard 112</p>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 text-md-end text-start">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-end gap-2">
                    <div>
                        <label for="daterange" class="form-label fw-semibold mb-1">Filter Tanggal</label>
                        <input type="text" id="daterange"
                            class="form-control form-control-sm rounded-pill border-secondary"
                            style="cursor: pointer; max-width: 250px;" readonly />
                    </div>
                    <div class="pt-2 pt-md-4">
                        <a href="{{ route('dispatchers.index') }}" class="btn btn-outline-primary btn-sm rounded-pill ">
                            <i class="bi bi-geo-alt me-1"></i> Lihat Map
                        </a>
                    </div>
                </div>
            </div>
        </div>


       @include('dashboards.partials.menu')

        <div class="row mb-4">
            <div class="col-md-6 mt-4">
                <div class="summary-card text-white">
                    <div class="summary-card-body">
                        <div class="summary-data-section">
                            <div class="summary-data-item">
                                <div class="summary-data-icon bg-white text-primary">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="summary-data-text">
                                    <div class="summary-data-label">Total Panggilan</div>
                                    <div class="summary-data-value" id="total-calls">0</div>
                                </div>
                            </div>
                            <div class="summary-data-item">
                                <div class="summary-data-icon bg-success">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div class="summary-data-text">
                                    <div class="summary-data-label">Terjawab</div>
                                    <div class="summary-data-value" id="answered">0</div>
                                </div>
                            </div>
                            <div class="summary-data-item">
                                <div class="summary-data-icon bg-danger">
                                    <i class="bi bi-telephone-x-fill"></i>
                                </div>
                                <div class="summary-data-text">
                                    <div class="summary-data-label">Terlewat</div>
                                    <div class="summary-data-value" id="abandoned">0</div>
                                </div>
                            </div>
                            <div class="summary-data-item">
                                <div class="summary-data-icon bg-white text-primary">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="summary-data-text">
                                    <div class="summary-data-label">Avg Durasi</div>
                                    <div class="summary-data-value" id="avg-call-duration">00:00:00</div>
                                </div>
                            </div>
                            <div class="summary-data-item">
                                <div class="summary-data-icon bg-white text-primary">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="summary-data-text">
                                    <div class="summary-data-label">Total Durasi</div>
                                    <div class="summary-data-value" id="total-call-duration">00:00:00</div>
                                </div>
                            </div>
                        </div>

                        <div class="summary-speedometer-section">
                            <div id="summary-speedometer-chart" style="width: 360px; height: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6 d-flex flex-column mt-4">
                <div class="card h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span id="total-calls-service" class="fs-2 fw-bold text-dark"></span>
                            </div>
                            <small class="text-muted fw-semibold">Ringkasan Insiden</small>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between pt-3">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://cdn-icons-png.flaticon.com/512/7235/7235497.png" alt="" class="me-3"
                                style="width: 30px; height: 30px; border-radius: 4px;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="#" class="text-dark fw-bold text-decoration-none">Baru</a>
                                        <div class="text-muted small">Data Insiden Baru</div>
                                    </div>
                                    <span id="statusBaruTop" class="fw-bold fs-5 text-dark">0</span>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="d-flex align-items-center mb-3">
                            <img src="https://cdn-icons-png.flaticon.com/512/9789/9789278.png" alt="" class="me-3"
                                style="width: 30px; height: 30px; border-radius: 4px;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="#" class="text-dark fw-bold text-decoration-none">Proses</a>
                                        <div class="text-muted small">Data Insiden Proses</div>
                                    </div>
                                    <span id="statusDiprosesTop" class="fw-bold fs-5 text-dark">0</span>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="d-flex align-items-center">
                            <img src="https://cdn-icons-png.flaticon.com/512/12901/12901779.png" alt=""
                                class="me-3" style="width: 30px; height: 30px; border-radius: 4px;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="#" class="text-dark fw-bold text-decoration-none">Selesai</a>
                                        <div class="text-muted small">Data Insiden Selesai</div>
                                    </div>
                                    <span id="statusSelesaiTop" class="fw-bold fs-5 text-dark">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="card incident-summary-card text-dark mt-5" id="total-type-report-container">
                            <!-- JS will render here -->
                        </div>
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
                                        <th class="py-2 font-medium text-center">AVG Response (minute)</th>
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

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>


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
            fetchTotalTypeReport(start, end);
            fetchSummaryData(start, end);

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
                fetchTotalTypeReport(start, end);
                fetchSummaryData(start, end);
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

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.classList.add('hover:bg-gray-50', 'transition-colors');

                const totalPerCategory = Number(item.total);

                tr.innerHTML = `
            <td class="py-2 pr-4 max-w-[180px] truncate" title="${item.category}">${truncateText(item.category)}</td>
            <td class="py-2 pr-4 text-center font-semibold text-gray-800" title="Total: ${totalPerCategory}">${totalPerCategory}</td>
            <td class="py-2 pr-4 text-center text-green-600 font-medium">${item.selesai_count}</td>
            <td class="py-2 text-center text-yellow-500 font-medium">${item.proses_count}</td>
        `;
                list.appendChild(tr);
            });
        }




        function renderTop5Districts(data) {
            const list = document.getElementById('top5-district-list');
            list.innerHTML = '';

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.classList.add('hover:bg-gray-50', 'transition');

                const totalItem = Number(item.total);

                tr.innerHTML = `
            <td class="py-2 pr-4 max-w-[180px] truncate" title="${item.district}">${truncateText(item.district)}</td>
            <td class="py-2 pr-4 text-center font-semibold text-gray-800" title="Total: ${totalItem}">${totalItem}</td>
            <td class="py-2 pr-4 text-center text-green-600 font-medium">${item.selesai_count}</td>
            <td class="py-2 text-center text-yellow-500 font-medium">${item.proses_count}</td>
        `;
                list.appendChild(tr);
            });
        }


        function renderTop5Dinas(data) {
            const list = document.getElementById('top5-dinas-list');
            list.innerHTML = '';

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.classList.add('hover:bg-gray-50');

                const totalData = Number(item.total);

                tr.innerHTML = `
                    <td class="py-2 pr-4 max-w-[160px] truncate" title="${item.dinas}">
                        <a href="/incident/by-dinas/${item.dinas}" target="_blank"> ${truncateText(item.dinas, 30)} </a>
                    </td>
                    <td class="py-2 pr-4 text-center font-semibold text-gray-800" title="Total: ${totalData}">${totalData}</td>
                    <td class="py-2 pr-4 text-center text-gray-600">${item.selesai_count}</td>
                    <td class="py-2 text-center text-gray-600">${item.proses_count}</td>
                `;
                list.appendChild(tr);
            });
        }


        function formatMinutes(minutes) {
            if (!minutes || isNaN(minutes)) return '-';
            return `${parseFloat(minutes).toFixed(2)} menit`;
        }
        function renderTop5ResponsiveDinas(data) {
            const tbody = document.getElementById('top5-responsive-dinas-list');
            tbody.innerHTML = '';

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.classList.add('hover:bg-gray-50');
                tr.innerHTML = `
                    <td class="py-2 pr-4 max-w-[150px] truncate" title="${item.dinas}"><a href="/incident/by-dinas/${item.dinas}" target="_blank"> ${truncateText(item.dinas, 25)} </a></td>
                    <td class="py-2 pr-4 text-center font-semibold text-gray-800">${item.total_responses ?? '-'}</td>
                    <td class="py-2 text-center text-gray-600">${formatMinutes(item.avg_response_time_minutes)}</td>
                `;
                tbody.appendChild(tr);
            });
        }

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

        function renderSkeletonStats() {
            ['total', 'active', 'handling', 'closed'].forEach(id => {
                document.getElementById(id).innerHTML = `<span class="placeholder col-6"></span>`;
            });

            chartDom.style.display = 'none';
        }

        async function fetchTotalTypeReport(startDate, endDate) {
            const params = {};
            if (startDate && endDate) {
                params.start_date = startDate.format('YYYY-MM-DD');
                params.end_date = endDate.format('YYYY-MM-DD');
            }

            try {
                const response = await axios.get('/dashboard/call-center/total-type-report', {
                    params
                });
                const laporan = response.data.data?.type_laporan || {};
                const show = response.data.data;
                renderTotalTypeReport(laporan, show);
            } catch (err) {
                console.error('Gagal fetch data total tipe laporan:', err);
            }
        }

        function renderTotalTypeReport(data, show) {
            const container = document.getElementById('total-type-report-container');
            container.innerHTML = '';

            const totalText = document.createElement('div');
            totalText.className = 'mb-3';
            totalText.innerHTML = `
                <p class="fs-5 fw-semibold text-muted mb-2">Total Semua Laporan:
                    <span class="fw-bold text-dark">${data.total || 0}</span>
                </p>
            `;
            container.appendChild(totalText);

            const grid = document.createElement('div');
            grid.className = 'incident-summary-grid';

            const items = [{
                    id: 'normal-reports',
                    icon: 'bi bi-person-check',
                    color: 'style="color: #ec4899;"',
                    label: 'Normal',
                    value: data.normal || 0
                },
                {
                    id: 'prank-reports',
                    icon: 'bi bi-emoji-angry text-danger',
                    color: '',
                    label: 'Prank',
                    value: data.prank || 0
                },
                {
                    id: 'ghost-reports',
                    icon: 'bi bi-incognito text-dark',
                    color: '',
                    label: 'Ghost',
                    value: data.ghost || 0
                },
                {
                    id: 'info-reports',
                    icon: 'bi bi-info-circle-fill text-info',
                    color: '',
                    label: 'Info',
                    value: data.info || 0
                }
            ];

            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'incident-item';
                div.innerHTML = `
                    <div class="incident-item-content">
                        <h2 id="${item.id}" class="fw-bold">${item.value}</h2>
                        <p class="text-muted">${item.label}</p>
                    </div>
                `;
                grid.appendChild(div);
            });

            function updateValue(id, value) {
                const el = document.getElementById(id);
                if (el) el.textContent = value;
            }

            updateValue('statusBaruTop', show.active);
            updateValue('statusDiprosesTop', show.handling);
            updateValue('statusSelesaiTop', show.closed);

            container.appendChild(grid);
        }

        function renderLoadingSkeleton(container) {
            container.innerHTML = `
                <div class="col-12">
                    <div class="placeholder-glow">
                        <div class="placeholder col-12 mb-2" style="height: 2rem;"></div>
                        <div class="placeholder col-6" style="height: 3rem;"></div>
                        <div class="placeholder col-6" style="height: 3rem;"></div>
                    </div>
                </div>
            `;
        }

        function fetchSummaryData(startDate, endDate) {
            const params = {};
            if (startDate && endDate) {
                params.start_date = startDate.format('YYYY-MM-DD');
                params.end_date = endDate.format('YYYY-MM-DD');
            }

            axios.get('/dashboard/call-center/summary-call', {
                    params
                })
                .then(function(response) {
                    var data = response.data.data;
                    document.getElementById("answered").textContent = data.answer;
                    document.getElementById("abandoned").textContent = data.abandon;
                    document.getElementById("total-calls").textContent = data.total_call;
                    document.getElementById("avg-call-duration").textContent = data.avg_call_duration;
                    document.getElementById("total-call-duration").textContent = data.total_call_duration;

                    Highcharts.chart('summary-speedometer-chart', {
                        chart: {
                            type: 'gauge',
                            backgroundColor: null,
                            plotBackgroundColor: null,
                            plotBorderWidth: 0,
                            plotShadow: false,
                            height: '100%'
                        },
                        title: {
                            text: 'Speedometer SLA',
                            style: {
                                color: '#ffffff',
                                fontSize: '16px'
                            }
                        },
                        pane: {
                            startAngle: -90,
                            endAngle: 90,
                            background: null,
                            center: ['50%', '75%'],
                            size: '120%'
                        },
                        yAxis: {
                            min: 0,
                            max: 100,
                            tickPixelInterval: 72,
                            tickPosition: 'inside',
                            tickLength: 20,
                            tickWidth: 2,
                            minorTickInterval: null,
                            labels: {
                                distance: 20,
                                style: {
                                    fontSize: '16px',
                                    color: '#ffffff'
                                }
                            },
                            lineWidth: 0,
                            plotBands: [{
                                    from: 0,
                                    to: 20,
                                    color: '#a7f3d0',
                                    thickness: 20
                                }, // hijau sangat muda
                                {
                                    from: 20,
                                    to: 40,
                                    color: '#6ee7b7',
                                    thickness: 20
                                },
                                {
                                    from: 40,
                                    to: 60,
                                    color: '#34d399',
                                    thickness: 20
                                },
                                {
                                    from: 60,
                                    to: 80,
                                    color: '#10b981',
                                    thickness: 20
                                },
                                {
                                    from: 80,
                                    to: 100,
                                    color: '#065f46',
                                    thickness: 20
                                } // hijau tua
                            ]
                        },
                        series: [{
                            name: 'SLA',
                            data: [data.kpi_call],
                            tooltip: {
                                valueSuffix: ' %',
                                backgroundColor: '#065f46',
                                style: {
                                    color: '#ffffff'
                                }
                            },
                            dataLabels: {
                                format: '<div style="text-align:center;"><span style="font-size:20px; color: white;">{y} %</span></div>',
                                useHTML: true,
                                y: 50
                            },
                            dial: {
                                radius: '80%',
                                backgroundColor: '#ffffff',
                                baseWidth: 12,
                                baseLength: '0%',
                                rearLength: '0%'
                            },
                            pivot: {
                                backgroundColor: '#ffffff',
                                radius: 6
                            }
                        }],
                        credits: {
                            enabled: false
                        },
                        navigation: {
                            buttonOptions: {
                                enabled: false
                            }
                        }
                    });
                })
                .catch(function(error) {
                    console.log("Error fetching data", error);
                });
        }
    </script>
@endpush
