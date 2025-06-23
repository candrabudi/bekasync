@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-green: #28a745;
            --light-green: #4CAF50;
            --main-bg: #f0f2f5;
            --card-bg: #ffffff;
            --header-bg: #e9ecef;
            --text-color: #343a40;
            --muted-text-color: #6c757d;
            --border-color: #dee2e6;
            --box-shadow: rgba(0, 0, 0, 0.08);

            --green-online: #2ecc71;
            --red-offline: #dc3545;

            --color-blue: #007bff;
            --bg-blue-opacity: rgba(0, 123, 255, 0.1);
            --color-orange: #fd7e14;
            --bg-orange-opacity: rgba(253, 126, 20, 0.1);
            --color-green: #28a745;
            --bg-green-opacity: rgba(40, 167, 69, 0.1);
            --color-red: #dc3545;
            --bg-red-opacity: rgba(220, 53, 69, 0.1);
            --color-purple: #6f42c1;
            --bg-purple-opacity: rgba(111, 66, 193, 0.1);
            --color-gray: #6c757d;
            --bg-gray-opacity: rgba(108, 117, 125, 0.1);

            --agent-list-max-height: 700px;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 4px 12px var(--box-shadow);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            color: var(--text-color);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header .ri-icon {
            font-size: 1.2em;
            color: var(--primary-green);
        }

        .card-body {
            padding: 20px;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            border-radius: 12px;
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            visibility: hidden;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .spinner-border {
            color: var(--primary-green) !important;
        }

        .total-conversations .metric-value {
            font-size: 3.5rem;
            font-weight: bold;
            color: var(--primary-green);
            line-height: 1;
            margin-bottom: 10px;
        }

        .total-conversations .sub-metrics {
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .total-conversations .sub-metrics div {
            flex: 1;
        }

        .total-conversations .sub-label {
            font-size: 0.8rem;
            color: var(--muted-text-color);
        }

        .total-conversations .sub-value {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--text-color);
        }

        .whatsapp-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed var(--border-color);
            font-size: 0.95rem;
        }

        .whatsapp-list-item:last-child {
            border-bottom: none;
        }

        .whatsapp-list-item span.category-name {
            color: var(--text-color);
        }

        .whatsapp-list-item span.category-count {
            font-weight: 600;
            color: var(--primary-green);
        }

        .agent-availability-summary-section {
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }

        .agent-availability-summary-section .summary-count {
            font-size: 2.2rem;
            font-weight: bold;
            color: var(--text-color);
            line-height: 1;
            padding-bottom: 10px;
        }

        .agent-availability-summary-section .summary-label {
            font-size: 0.85rem;
            color: var(--muted-text-color);
            margin-bottom: 10px;
        }

        .agent-availability-summary-section .progress {
            height: 8px;
            background-color: var(--border-color);
            width: 100%;
            border-radius: 4px;
        }

        .agent-availability-summary-section .progress-bar {
            background-color: var(--green-online);
            border-radius: 4px;
        }

        .response-metrics-card .metric-item {
            margin-bottom: 15px;
        }

        .response-metrics-card .metric-item:last-child {
            margin-bottom: 0;
        }

        .response-metrics-card .metric-label {
            font-size: 0.85rem;
            color: var(--muted-text-color);
            margin-bottom: 5px;
        }

        .response-metrics-card .metric-value {
            font-size: 1.6rem;
            color: var(--text-color);
            font-weight: bold;
        }

        .response-metrics-card .satisfaction-value {
            font-size: 2.5rem;
            color: var(--primary-green);
            font-weight: bold;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .response-metrics-card .satisfaction-value .ri-star-fill {
            color: #f1c40f;
        }

        .response-metrics-card .satisfaction-note {
            font-size: 0.75rem;
            color: var(--muted-text-color);
            margin-top: 5px;
        }

        .top-tags-card .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 120px;
            color: var(--muted-text-color);
            font-style: italic;
        }

        .top-tags-card .card-body>div {
            width: 100%;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 8px;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .top-tags-card .card-body>div:last-child {
            margin-bottom: 0;
        }

        .top-tags-card .card-body>div:hover {
            background-color: #e0e4e8;
            /* box-shadow: 0 2px 8px var(--box-shadow); */
        }

        .top-tags-card .card-body .text-primary {
            color: var(--primary-green) !important;
        }

        .top-tags-card .card-body .ri-hashtag {
            color: var(--primary-green);
        }

        .activity-chart-card .chart-area {
            background-color: var(--header-bg);
            border-radius: 8px;
            padding: 20px;
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted-text-color);
            font-size: 1rem;
            border: 1px dashed var(--border-color);
        }

        .activity-chart-card .chart-controls {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 15px;
        }

        .activity-chart-card .form-check-input {
            background-color: #e9ecef;
            border-color: var(--border-color);
            cursor: pointer;
        }

        .activity-chart-card .form-check-input:checked {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .activity-chart-card .form-check-label {
            color: var(--muted-text-color);
            font-size: 0.85rem;
            cursor: pointer;
        }

        #agentListContainer {
            max-height: var(--agent-list-max-height);
            overflow-y: hidden; 
            padding-right: 5px;
            position: relative;
        }

        #agentListContainer.manual-scroll {
            overflow-y: auto;
        }

        #agentListContainer::-webkit-scrollbar {
            width: 5px;
        }

        #agentListContainer::-webkit-scrollbar-track {
            background: var(--header-bg);
            border-radius: 10px;
        }

        #agentListContainer::-webkit-scrollbar-thumb {
            background: var(--muted-text-color);
            border-radius: 10px;
        }

        #agentListContainer.manual-scroll:hover::-webkit-scrollbar-thumb {
            background: #999;
        }

        .form-control.dark-mode {
            background-color: var(--header-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 15px;
        }

        .form-control.dark-mode::placeholder {
            color: var(--muted-text-color);
        }

        .form-control.dark-mode:focus {
            background-color: var(--header-bg);
            color: var(--text-color);
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }

        .operator-card {
            background-color: var(--card-bg);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .operator-card:hover {
            background-color: #f8f9fa;
            box-shadow: 0 2px 8px var(--box-shadow);
        }

        .operator-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .operator-name {
            font-weight: 600;
            color: var(--text-color);
            font-size: 1.05rem;
        }

        .operator-email {
            font-size: 0.8rem;
            color: var(--muted-text-color);
        }

        .status-badge {
            border-radius: 15px;
            padding: 4px 10px;
            font-size: 0.7rem;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
        }

        .status-online {
            background-color: var(--green-online);
        }

        .status-offline {
            background-color: var(--red-offline);
        }

        .operator-metrics {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 1px dashed var(--border-color);
            padding-bottom: 10px;
        }

        .operator-metrics div {
            flex: 1;
            text-align: center;
            border-right: 1px dashed var(--border-color);
        }

        .operator-metrics div:last-child {
            border-right: none;
        }

        .operator-metrics .label {
            font-size: 0.7rem;
            color: var(--muted-text-color);
            margin-bottom: 3px;
        }

        .operator-metrics .value {
            font-size: 0.95rem;
            color: var(--text-color);
            font-weight: 500;
        }

        .operator-actions {
            display: flex;
            justify-content: space-around;
        }

        .operator-actions div {
            flex: 1;
            text-align: center;
            font-weight: 500;
        }

        .operator-actions .action-label {
            font-size: 0.75rem;
            color: var(--muted-text-color);
            margin-bottom: 3px;
        }

        .operator-actions .action-value {
            font-size: 1.2rem;
            color: var(--text-color);
            font-weight: bold;
        }

        /* Styles for Select2 dropdown within Bootstrap */
        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px); /* Match Bootstrap form-control height */
            border: 1px solid var(--border-color);
            border-radius: 0.375rem; /* Match Bootstrap border-radius */
            background-color: var(--header-bg);
            color: var(--text-color);
            padding: 0.375rem 0.75rem;
            display: flex;
            align-items: center;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            color: var(--text-color);
            line-height: 1.5; /* Match Bootstrap line-height */
            padding-left: 0; /* Remove default padding */
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px); /* Match container height */
            right: 0.375rem;
            top: 0; /* Align arrow to top */
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--muted-text-color) transparent transparent transparent;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent var(--muted-text-color) transparent;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: var(--muted-text-color);
        }

        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: var(--primary-green) !important;
            color: white !important;
        }

        .select2-dropdown {
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            background-color: var(--card-bg);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .select2-search input {
            background-color: var(--header-bg) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-color) !important;
        }

        .select2-results__option {
            color: var(--text-color);
        }
    </style>
    @include('dashboards.call-center.partials.styles.main')
@endpush

@section('content')
    <div class="container">

        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-xl-4 col-md-6 mb-3 mb-md-0">
                <div class="page-title-content">
                    <h3 class="mb-1 fw-bold">Dashboard Omni Channel</h3>
                    <p class="text-muted mb-0">Selamat Datang di Dashboard Omni Channel</p>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 text-md-end text-start">
                <div class="d-flex align-items-center gap-3 justify-content-end">
                    <select id="channel-select" class="form-select" style="width: 400px;">
                    </select>

                    <div id="daterange-display" class="btn btn-outline-secondary d-flex align-items-center gap-2"
                        style="cursor: pointer; width: 400px; text-align: center;">
                        <i class="ri-calendar-line"></i>
                        <span id="daterange-text"></span>
                    </div>
                </div>
            </div>
        </div>

        @include('dashboards.partials.menu') 

        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card total-conversations h-100">
                            <div class="card-header"><i class="ri-chat-1-line ri-icon"></i> Total Percakapan</div>
                            <div class="card-body text-center position-relative">
                                <div class="loading-overlay" id="loading-total-conversations">
                                    <div class="spinner-border text-light" role="status"><span
                                            class="visually-hidden">Loading...</span></div>
                                </div>
                                <div class="metric-value" id="total-conversations-value"></div>
                                <div class="sub-metrics">
                                    <div>
                                        <div class="sub-label">Tidak Dikenal</div>
                                        <div class="sub-value" id="unassigned-value"></div>
                                    </div>
                                    <div>
                                        <div class="sub-label">Aktif</div>
                                        <div class="sub-value" id="active-value"></div>
                                    </div>
                                    <div>
                                        <div class="sub-label">Selesai</div>
                                        <div class="sub-value" id="completed-value"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card whatsapp-conversations h-100">
                            <div class="card-header"><i class="ri-whatsapp-line ri-icon"></i> Percakapan Whatsapp</div>
                            <div class="card-body position-relative">
                                <div class="loading-overlay" id="loading-whatsapp-conversations">
                                    <div class="spinner-border text-light" role="status"><span
                                            class="visually-hidden">Loading...</span></div>
                                </div>
                                <div id="wa-usage-content">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card response-metrics-card h-100">
                            <div class="card-header"><i class="ri-time-line ri-icon"></i> Rata-rata Waktu Respon</div>
                            <div class="card-body position-relative">
                                <div class="loading-overlay" id="loading-response-time">
                                    <div class="spinner-border text-light" role="status"><span
                                            class="visually-hidden">Loading...</span></div>
                                </div>
                                <div class="metric-item text-center">
                                    <div class="metric-label">Waktu Respon Pertama</div>
                                    <div class="metric-value" id="avg-1st-reply-time"></div>
                                </div>
                                <div class="metric-item text-center">
                                    <div class="metric-label">Rata-rata Waktu Respon</div>
                                    <div class="metric-value" id="avg-reply-time"></div>
                                </div>
                                <div class="metric-item text-center">
                                    <div class="metric-label">Durasi Per Percakapan</div>
                                    <div class="metric-value" id="avg-duration-time"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card response-metrics-card h-100">
                            <div class="card-header"><i class="ri-star-line ri-icon"></i> Kepuasan Pelanggan</div>
                            <div class="card-body text-center d-flex flex-column justify-content-center position-relative">
                                <div class="loading-overlay" id="loading-customer-satisfaction">
                                    <div class="spinner-border text-light" role="status"><span
                                            class="visually-hidden">Loading...</span></div>
                                </div>
                                <div class="satisfaction-value" id="avg-csat"></div>
                                <div class="satisfaction-note"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12 mb-4">
                        <div class="card top-tags-card h-100">
                            <div class="card-header"><i class="ri-price-tag-3-line ri-icon"></i> Top 5 Percakapan By Tag
                            </div>
                            <div class="card-body position-relative">
                                <div class="loading-overlay" id="loading-top-tags">
                                    <div class="spinner-border text-light" role="status"><span
                                            class="visually-hidden">Loading...</span></div>
                                </div>
                                <div id="top-tags-content">
                                    <p><i class="ri-information-line"></i> Tidak ada data.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card activity-chart-card h-100">
                            <div class="card-header"><i class="ri-bar-chart-line ri-icon"></i> Aktivitas Percakapan Hari
                                Ini <span class="badge bg-secondary ms-2" id="hourly-total-conversations"></span>
                            </div>
                            <div class="card-body position-relative">
                                <div class="loading-overlay" id="loading-activity-chart">
                                    <div class="spinner-border text-light" role="status"><span
                                            class="visually-hidden">Loading...</span></div>
                                </div>
                                <div class="chart-area" id="conversationActivityChart">
                                    <canvas id="conversationsChart"></canvas>
                                </div>
                                <div class="chart-controls">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="all-chart" value="all"
                                            checked>
                                        <label class="form-check-label" for="all-chart">All</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="incoming-chart"
                                            value="incoming">
                                        <label class="form-check-label" for="incoming-chart">Incoming</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="outgoing-chart"
                                            value="outgoing">
                                        <label class="form-check-label" for="outgoing-chart">Outgoing</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-90">
                    <div class="card-header"><i class="ri-group-line ri-icon"></i> Ketersediaan Agent</div>
                    <div class="card-body d-flex flex-column position-relative">
                        <div class="loading-overlay" id="loading-agent-details">
                            <div class="spinner-border text-light" role="status"><span
                                    class="visually-hidden">Loading...</span></div>
                        </div>

                        <div class="agent-availability-summary-section">
                            <div class="summary-count" id="agent-online-count"></div>
                            <div class="summary-label">Agents Online</div>
                            <div class="progress w-100" role="progressbar" aria-label="Agent Availability"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="0">
                                <div class="progress-bar" style="width: 0%"></div>
                            </div>
                        </div>

                        <input type="text" class="form-control dark-mode" id="agentSearchInput"
                            placeholder="Cari agen (nama atau email)...">

                        <div id="agentListContainer">
                            <div class="agent-list-content" id="agent-performance-body">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        const BASE_API_URL = `${window.location.origin}/sosmed`;

        let conversationsChartInstance = null;

        let currentChannel = 'all';
        let currentStartDate = moment().startOf('day').format('YYYY-MM-DD');
        let currentEndDate = moment().endOf('day').format('YYYY-MM-DD');

        const csrfToken = document.querySelector('meta[name="csrf-token"]') ?
            document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

        const customAxios = axios.create({
            baseURL: BASE_API_URL,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            }
        });

        function toggleLoading(loaderId, show) {
            const loader = document.getElementById(loaderId);
            if (loader) {
                if (show) {
                    loader.classList.add('active');
                } else {
                    loader.classList.remove('active');
                }
            }
        }

        function initializeDateRangePicker(callback) {
            const start = moment().startOf('day');
            const end = moment().endOf('day');

            $('#daterange-display').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    fromLabel: "From",
                    toLabel: "To",
                    customRangeLabel: "Custom",
                    daysOfWeek: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                    monthNames: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                        "September", "Oktober", "November", "Desember"
                    ],
                    firstDay: 1
                },
                ranges: {
                    'Hari Ini': [moment().startOf('day'), moment().endOf('day')], // Menggunakan startOf/endOf hari ini
                    'Kemarin': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
                    '30 Hari Terakhir': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                        .endOf('month')
                    ]
                }
            }, callback);

            updateDateRangeDisplay(document.getElementById('daterange-text'), start, end);
        }

        function updateDateRangeDisplay(element, start, end) {
            if (element) {
                if (start.format('YYYY-MM-DD') === end.format('YYYY-MM-DD')) {
                    element.textContent = `${start.format('MMM D, YYYY')}`;
                } else {
                    element.textContent = `${start.format('MMM D, YYYY')} - ${end.format('MMM D, YYYY')}`;
                }
            }
        }

        async function fetchSubscriptionList() {
            try {
                const response = await customAxios.post(`${BASE_API_URL}/subscription`);
                const data = response.data.data;

                const selectEl = $('#channel-select');
                selectEl.empty(); 
                selectEl.append('<option value="all">All Channels</option>');

                data.forEach(channel => {
                    selectEl.append(new Option(channel.account_name, channel.id));
                });

                selectEl.select2({
                    placeholder: "Pilih Channel",
                    allowClear: true 
                });

                selectEl.val('all').trigger('change');

                selectEl.on('change', function() {
                    const selectedValue = $(this).val();
                    currentChannel = selectedValue; 
                    updateAllDashboardData(); 
                });

            } catch (error) {
                console.error('Error fetching subscription list:', error);
            }
        }

        async function fetchActiveAgentsData(channel) {
            toggleLoading('loading-agent-details', true);
            try {
                const response = await customAxios.post(`${BASE_API_URL}/active-agents`, {
                    channel: channel,
                    date: [currentStartDate, currentEndDate]
                });
                const data = response.data.data;

                const agentOnlineCountEl = document.getElementById('agent-online-count');
                const progressBar = document.querySelector('.agent-availability-summary-section .progress-bar');

                const activeAgents = data.active_agent.active || 0;
                const maxAgents = data.active_agent.max_agent || 0;

                if (agentOnlineCountEl) {
                    agentOnlineCountEl.innerText = `${activeAgents} / ${maxAgents}`;
                }

                if (progressBar) {
                    const percentage = maxAgents > 0 ? (activeAgents / maxAgents) * 100 : 0;
                    progressBar.style.width = `${percentage}%`;
                }

            } catch (error) {
                console.error('Error fetching active agents data:', error);
                document.getElementById('agent-online-count').innerText = '0 / 0';
                document.querySelector('.agent-availability-summary-section .progress-bar').style.width = '0%';
            } finally {
                toggleLoading('loading-agent-details', false); 
            }
        }

        async function fetchConversationsSummary(channel, startDate, endDate) {
            toggleLoading('loading-total-conversations', true);
            toggleLoading('loading-response-time', true);
            toggleLoading('loading-customer-satisfaction', true);
            toggleLoading('loading-activity-chart', true);
            try {
                const response = await customAxios.post(`${BASE_API_URL}/conversation-summary`, {
                    channel: channel,
                    date: [startDate, endDate]
                });
                const data = response.data.data;

                document.getElementById('total-conversations-value').innerText = (data.conversations_status
                    .unassigned || 0) + (data.conversations_status.active || 0) + (data.conversations_status
                    .completed || 0) || '0';
                document.getElementById('unassigned-value').innerText = data.conversations_status.unassigned || '0';
                document.getElementById('active-value').innerText = data.conversations_status.active || '0';
                document.getElementById('completed-value').innerText = data.conversations_status.completed || '0';

                document.getElementById('avg-1st-reply-time').innerText = data.avg_1st_reply_time || '00:00:00';
                document.getElementById('avg-reply-time').innerText = data.avg_reply_time || '00:00:00';
                document.getElementById('avg-duration-time').innerText = data.avg_duration_time || '00:00:00';

                const avgCsat = parseFloat(data.avg_csat);
                const csatValue = !isNaN(avgCsat) ? avgCsat.toFixed(2) : '0.00';
                console.log("hallo: " + csatValue);

                document.getElementById('avg-csat').innerText = csatValue;
                document.querySelector('.satisfaction-note').innerText = data.avg_csat_note || '(belum ada data)';

                const totalHourlyConversations = data.conversations_data.chart_data.datasets.conversations.reduce((sum,
                    value) => sum + value, 0);
                document.getElementById('hourly-total-conversations').innerText =
                    `Total: ${totalHourlyConversations} conversations`;

                updateConversationsChart(
                    data.conversations_data.chart_data.labels,
                    data.conversations_data.chart_data.datasets.inbound_conversations || [],
                    data.conversations_data.chart_data.datasets.outbound_conversations || [],
                    data.conversations_data.chart_data.datasets.conversations || []
                );

            } catch (error) {
                console.error('Error fetching conversations summary data:', error);
                document.getElementById('total-conversations-value').innerText = '0';
                document.getElementById('unassigned-value').innerText = '0';
                document.getElementById('active-value').innerText = '0';
                document.getElementById('completed-value').innerText = '0';
                document.getElementById('avg-1st-reply-time').innerText = '00:00:00';
                document.getElementById('avg-reply-time').innerText = '00:00:00';
                document.getElementById('avg-duration-time').innerText = '00:00:00';
                document.getElementById('avg-csat').innerText = '0.00';
                document.querySelector('.satisfaction-note').innerText = '(gagal memuat data)';
                document.getElementById('hourly-total-conversations').innerText = 'Total: 0 conversations';
                updateConversationsChart([], [], [], []); // Kosongkan chart
            } finally {
                toggleLoading('loading-total-conversations', false);
                toggleLoading('loading-response-time', false);
                toggleLoading('loading-customer-satisfaction', false);
                toggleLoading('loading-activity-chart', false);
            }
        }

        function updateConversationsChart(labels, inboundData, outboundData, allData) {
            const canvas = document.getElementById('conversationsChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            if (conversationsChartInstance) {
                conversationsChartInstance.destroy();
            }

            const inboundGradient = ctx.createLinearGradient(0, 0, 0, 300);
            inboundGradient.addColorStop(0, 'rgba(74, 144, 226, 0.7)'); 
            inboundGradient.addColorStop(1, 'rgba(74, 144, 226, 0.2)');

            const outboundGradient = ctx.createLinearGradient(0, 0, 0, 300);
            outboundGradient.addColorStop(0, 'rgba(46, 204, 113, 0.7)'); 
            outboundGradient.addColorStop(1, 'rgba(46, 204, 113, 0.2)');

            const allGradient = ctx.createLinearGradient(0, 0, 0, 300);
            allGradient.addColorStop(0, 'rgba(255, 165, 0, 0.7)'); 
            allGradient.addColorStop(1, 'rgba(255, 165, 0, 0.2)');

            conversationsChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'All',
                            data: allData,
                            fill: true,
                            backgroundColor: allGradient,
                            borderColor: 'orange',
                            borderWidth: 1.5,
                            pointRadius: 1,
                            pointHoverRadius: 3,
                            tension: 0.3,
                            cubicInterpolationMode: 'monotone',
                            hidden: !document.getElementById('all-chart').checked
                        },
                        {
                            label: 'Incoming',
                            data: inboundData,
                            fill: true,
                            backgroundColor: inboundGradient,
                            borderColor: 'var(--color-blue)',
                            borderWidth: 1.5,
                            pointRadius: 1,
                            pointHoverRadius: 3,
                            tension: 0.3,
                            cubicInterpolationMode: 'monotone',
                            hidden: !document.getElementById('incoming-chart').checked
                        },
                        {
                            label: 'Outgoing',
                            data: outboundData,
                            fill: true,
                            backgroundColor: outboundGradient,
                            borderColor: 'var(--green-online)',
                            borderWidth: 1.5,
                            pointRadius: 1,
                            pointHoverRadius: 3,
                            tension: 0.3,
                            cubicInterpolationMode: 'monotone',
                            hidden: !document.getElementById('outgoing-chart').checked
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false 
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            titleColor: '#fff',
                            bodyColor: '#ddd',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 4,
                            padding: 10,
                            callbacks: {
                                title: (context) => context?.[0]?.label || '',
                                label: (context) => `${context.dataset.label}: ${context.formattedValue}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'category',
                            ticks: {
                                color: 'var(--muted-text-color)',
                                autoSkip: true,
                                maxTicksLimit: 10 
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)',
                                drawBorder: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'var(--muted-text-color)',
                                stepSize: 1,
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)',
                                drawBorder: false
                            }
                        }
                    }
                }
            });

            document.querySelectorAll('.activity-chart-card .form-check-input').forEach(checkbox => {
                checkbox.removeEventListener('change', handleChartCheckboxChange); 
                checkbox.addEventListener('change', handleChartCheckboxChange); 
            });
        }

        function handleChartCheckboxChange() {
            if (!conversationsChartInstance) return;

            const allChecked = document.getElementById('all-chart').checked;
            const incomingChecked = document.getElementById('incoming-chart').checked;
            const outgoingChecked = document.getElementById('outgoing-chart').checked;

            conversationsChartInstance.data.datasets.forEach(dataset => {
                if (dataset.label === 'All') {
                    dataset.hidden = !allChecked;
                } else if (dataset.label === 'Incoming') {
                    dataset.hidden = !incomingChecked;
                } else if (dataset.label === 'Outgoing') {
                    dataset.hidden = !outgoingChecked;
                }
            });
            conversationsChartInstance.update();
        }

        async function fetchWhatsAppUsage(channel, startDate, endDate) {
            toggleLoading('loading-whatsapp-conversations', true);
            try {
                const response = await customAxios.post(`${BASE_API_URL}/wa-usage`, {
                    channel: channel,
                    date: [startDate, endDate]
                });
                const data = response.data.data.wa_conversation_usage;

                const container = document.getElementById("wa-usage-content");
                if (!container) return;

                container.innerHTML = ''; 
                const usageItemsMap = {
                    utility: {
                        label: "Utility",
                        icon: "ri-tools-line",
                        colorClass: "color-purple",
                        bgClass: "bg-purple-opacity"
                    },
                    service: {
                        label: "Service",
                        icon: "ri-customer-service-2-line",
                        colorClass: "color-green",
                        bgClass: "bg-green-opacity"
                    },
                    marketing: {
                        label: "Marketing",
                        icon: "ri-megaphone-line",
                        colorClass: "color-red",
                        bgClass: "bg-red-opacity"
                    },
                    authentication: {
                        label: "Authentication",
                        icon: "ri-fingerprint-line",
                        colorClass: "color-gray",
                        bgClass: "bg-gray-opacity"
                    }
                };

                const sortedKeys = Object.keys(usageItemsMap).sort(); 
                let hasData = false;
                sortedKeys.forEach(key => {
                    const itemProps = usageItemsMap[key];
                    const value = data[key] || 0;
                    if (value > 0) hasData = true; 

                    container.innerHTML += `
                        <div class="whatsapp-list-item">
                            <span class="category-name">${itemProps.label}</span>
                            <span class="category-count">${value}</span>
                        </div>
                    `;
                });

                if (!hasData) {
                    container.innerHTML =
                        '<p class="text-muted-text-color"><i class="ri-information-line"></i> Tidak ada data penggunaan WhatsApp.</p>';
                }

            } catch (error) {
                console.error("Error fetching WhatsApp usage data:", error);
                const container = document.getElementById("wa-usage-content");
                if (container) {
                    container.innerHTML =
                        '<p class="text-muted-text-color"><i class="ri-information-line"></i> Gagal memuat data penggunaan WhatsApp.</p>';
                }
            } finally {
                toggleLoading('loading-whatsapp-conversations', false);
            }
        }

        let agentScrollInterval;
        const agentScrollSpeed = 0.5;
        const agentIntervalTime = 20; 
        let originalAgentContentHeight = 0;

        const agentListContainer = document.getElementById('agentListContainer');
        const agentListContent = document.getElementById('agent-performance-body');

        function initializeAgentScrolling() {
            stopAgentScrolling();
            const allAgentCards = agentListContent.querySelectorAll('.operator-card');
            const visibleAgentCards = Array.from(allAgentCards).filter(card => card.style.display !== 'none');

            const originalChildrenHtml = visibleAgentCards.map(child => child.outerHTML).join('');

            if (originalChildrenHtml.trim() === '' || visibleAgentCards.length === 0) {
                agentListContent.innerHTML =
                    `<div class="operator-card text-center text-muted-text-color" style="padding: 20px;">Tidak ada data agen untuk ditampilkan.</div>`;
                agentListContainer.scrollTop = 0;
                return;
            }

            const tempMeasureDiv = document.createElement('div');
            tempMeasureDiv.style.visibility = 'hidden';
            tempMeasureDiv.style.position = 'absolute';
            tempMeasureDiv.style.top = '-9999px';
            tempMeasureDiv.style.width = agentListContent.offsetWidth + 'px';
            tempMeasureDiv.innerHTML = originalChildrenHtml;
            document.body.appendChild(tempMeasureDiv);
            originalAgentContentHeight = tempMeasureDiv.offsetHeight;
            document.body.removeChild(tempMeasureDiv);

            agentListContent.innerHTML = ''; 

            const numDuplicates = Math.ceil((agentListContainer.offsetHeight * 2) / originalAgentContentHeight) + 1;
            for (let i = 0; i < numDuplicates; i++) {
                agentListContent.innerHTML += originalChildrenHtml;
            }

            agentListContainer.scrollTop = 0; 
            startAgentScrolling();
        }

        function startAgentScrolling() {
            stopAgentScrolling();
            agentListContainer.classList.remove('manual-scroll'); 
            agentScrollInterval = setInterval(() => {
                agentListContainer.scrollTop += agentScrollSpeed;
                if (agentListContainer.scrollTop >= originalAgentContentHeight) {
                    agentListContainer.scrollTop = 0;
                }
            }, agentIntervalTime);
        }

        function stopAgentScrolling() {
            clearInterval(agentScrollInterval);
            agentListContainer.classList.add('manual-scroll'); 
        }

        async function fetchAgentPerformance(channel, startDate, endDate) {
            toggleLoading('loading-agent-details', true);
            try {
                const response = await customAxios.post(`${BASE_API_URL}/agent-performance`, {
                    channel: channel,
                    date: [startDate, endDate]
                });
                const users = response.data.data.users;

                let agentCardsHtml = '';
                if (users.length === 0) {
                    agentCardsHtml =
                        `<div class="operator-card text-center text-muted-text-color" style="padding: 20px;">Tidak ada data agen.</div>`;
                } else {
                    users.forEach(user => {
                        const statusDisplay = user.status_online === 'online' ? 'Online' : 'Offline';
                        const statusColor = user.status_online === 'online' ? 'status-online' :
                            'status-offline';

                        agentCardsHtml += `
                            <div class="operator-card" data-name="${user.fullname || user.username}" data-email="${user.username}">
                                <div class="operator-info">
                                    <div>
                                        <div class="operator-name">${user.fullname || user.username}</div>
                                        <div class="operator-email">${user.username || 'N/A'}</div>
                                    </div>
                                    <span class="status-badge ${statusColor}">${statusDisplay}</span>
                                </div>
                                <div class="operator-metrics">
                                    <div><div class="label">AVG Durasi</div><div class="value">${user.avg_duration_time || '00:00:00'}</div></div>
                                    <div><div class="label">AVG Respon 1st</div><div class="value">${user.avg_1st_reply_time || '00:00:00'}</div></div>
                                    <div><div class="label">AVG Respon</div><div class="value">${user.avg_reply_time || '00:00:00'}</div></div>
                                </div>
                                <div class="operator-actions">
                                    <div><div class="action-label">Active</div><div class="action-value">${user.active_chat || '0'}</div></div>
                                    <div><div class="action-label">Completed</div><div class="action-value">${user.completed_chat || '0'}</div></div>
                                </div>
                            </div>
                        `;
                    });
                }

                agentListContent.innerHTML = agentCardsHtml;

                initializeAgentScrolling();

            } catch (error) {
                console.error("Error fetching agent performance data:", error);
                agentListContent.innerHTML =
                    `<div class="operator-card text-center text-muted-text-color" style="padding: 20px;">Gagal memuat data agen.</div>`;
                stopAgentScrolling(); // Hentikan scroll jika gagal
                agentListContainer.scrollTop = 0;
            } finally {
                toggleLoading('loading-agent-details', false);
            }
        }

        async function fetchTopTags(channel, startDate, endDate) {
            toggleLoading('loading-top-tags', true);
            try {
                const response = await customAxios.post(`${BASE_API_URL}/top-tags`, {
                    channel: channel,
                    date: [startDate, endDate]
                });
                const data = response.data.data;
                const topTagsContent = document.getElementById('top-tags-content');

                if (topTagsContent) {
                    topTagsContent.innerHTML = ''; 
                    if (data && data.length > 0) {
                        data.slice(0, 5).forEach(tag => { 
                            topTagsContent.innerHTML += `
                                <div class="d-flex justify-content-between align-items-center p-3 rounded-lg">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-hashtag text-primary fs-5"></i>
                                        <span class="fs-6" style="color: var(--text-color);">${tag.name}</span>
                                    </div>
                                    <span class="fs-5 fw-bold" style="color: var(--color-blue);">${tag.count}</span>
                                </div>
                            `;
                        });
                    } else {
                        topTagsContent.innerHTML = '<p class="text-muted-text-color"><i class="ri-information-line"></i> Tidak ada data.</p>';
                    }
                }
            } catch (error) {
                console.error("Error fetching top tags data:", error);
                const topTagsContent = document.getElementById('top-tags-content');
                if (topTagsContent) {
                    topTagsContent.innerHTML = '<p class="text-muted-text-color"><i class="ri-information-line"></i> Gagal memuat data tag.</p>';
                }
            } finally {
                toggleLoading('loading-top-tags', false);
            }
        }

        function updateAllDashboardData() {
            const momentStart = moment(currentStartDate);
            const momentEnd = moment(currentEndDate);

            updateDateRangeDisplay(document.getElementById('daterange-text'), momentStart, momentEnd);

            fetchActiveAgentsData(currentChannel);
            fetchConversationsSummary(currentChannel, currentStartDate, currentEndDate);
            fetchWhatsAppUsage(currentChannel, currentStartDate, currentEndDate);
            fetchAgentPerformance(currentChannel, currentStartDate, currentEndDate);
            fetchTopTags(currentChannel, currentStartDate, currentEndDate);
        }

        document.addEventListener('DOMContentLoaded', function() {
            function updateCurrentTime() {
                const now = moment();
                const dateTimeHeader = document.getElementById('current-date-time');
                if (dateTimeHeader) {
                    dateTimeHeader.textContent = now.format('dddd, DD/MM/YYYY - HH:mm:ss');
                }
            }
            setInterval(updateCurrentTime, 1000);
            updateCurrentTime();
            fetchSubscriptionList().then(() => {
                initializeDateRangePicker(function(start, end) {
                    currentStartDate = start.format('YYYY-MM-DD');
                    currentEndDate = end.format('YYYY-MM-DD');
                    updateAllDashboardData();
                });

                updateAllDashboardData();
            });

            const agentSearchInput = document.getElementById('agentSearchInput');
            agentSearchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const allAgentCardsInContent = agentListContent.querySelectorAll('.operator-card');

                if (searchTerm !== '') {
                    stopAgentScrolling();
                    agentListContainer.scrollTop = 0; 
                } else {
                    if (!agentListContainer.matches(':hover')) {
                        startAgentScrolling();
                    }
                }

                let hasVisibleResults = false;
                allAgentCardsInContent.forEach(card => {
                    const agentName = card.dataset.name ? card.dataset.name.toLowerCase() : '';
                    const agentEmail = card.dataset.email ? card.dataset.email.toLowerCase() : '';

                    if (agentName.includes(searchTerm) || agentEmail.includes(searchTerm)) {
                        card.style.display = 'block';
                        hasVisibleResults = true;
                    } else {
                        card.style.display = 'none'; 
                    }
                });

                if (!hasVisibleResults && searchTerm !== '') {
                    agentListContent.innerHTML = `<div class="operator-card text-center text-muted-text-color" style="padding: 20px;">Tidak ada agen yang cocok dengan pencarian Anda.</div>`;
                } else if (searchTerm === '' && agentListContent.children.length === 1 && agentListContent.children[0].textContent.includes('Tidak ada agen yang cocok')) {
                    fetchAgentPerformance(currentChannel, currentStartDate, currentEndDate);
                } else if (!hasVisibleResults && searchTerm === '') {
                     agentListContent.innerHTML = `<div class="operator-card text-center text-muted-text-color" style="padding: 20px;">Tidak ada data agen.</div>`;
                }

                if (searchTerm === '') {
                    initializeAgentScrolling();
                }
            });


            agentListContainer.addEventListener('mouseenter', stopAgentScrolling);
            agentListContainer.addEventListener('mouseleave', function() {
                if (document.getElementById('agentSearchInput').value.trim() === '') {
                    startAgentScrolling();
                }
            });
        });
    </script>
@endpush