<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Percakapan - New Design</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">

    <style>
        :root {
            --main-bg: #1e263a;
            /* Main background color, slightly darker */
            --card-bg: #2b354a;
            /* Card background color */
            --header-bg: #35415c;
            /* Header background, slightly lighter than card */
            --text-color: #e0e6f0;
            /* Light text color */
            --muted-text-color: #aebacd;
            /* Muted text color */
            --accent-blue: #4a90e2;
            /* Primary blue for accents */
            --green-online: #2ecc71;
            /* Green for online status */
            --red-offline: #e74c3c;
            /* Red for offline status */
            --chart-bg: #222a3e;
            /* Background for chart area */
            --border-color: rgba(255, 255, 255, 0.1);
            /* Subtle border for separation */

            /* Additional colors from previous script (for WhatsApp usage) */
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

            /* New variable for agent list max height */
            --agent-list-max-height: 400px;
        }

        body {
            background-color: var(--main-bg);
            color: var(--text-color);
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 0.9rem;
        }

        .container-fluid {
            padding: 20px;
        }

        .card {
            background-color: var(--card-bg);
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            overflow: hidden;
            /* Ensure content respects border-radius */
        }

        .card-header {
            background-color: var(--header-bg);
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
            color: var(--accent-blue);
        }

        .card-body {
            padding: 20px;
        }

        /* --- Loading Overlay --- */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(43, 53, 74, 0.9);
            /* card-bg with transparency */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            /* Ensure it's above other content */
            border-radius: 12px;
            /* Match card border-radius */
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            /* Hidden by default */
            visibility: hidden;
            /* Hidden by default */
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }


        /* --- Total Percakapan Card --- */
        .total-conversations .metric-value {
            font-size: 3.5rem;
            font-weight: bold;
            color: var(--accent-blue);
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

        /* --- Percakapan Whatsapp Card --- */
        .whatsapp-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.05);
            /* Dashed line as in image */
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
            color: var(--accent-blue);
        }

        /* --- Ketersediaan Agent (SUMMARY part of the new combined card) --- */
        .agent-availability-summary-section {
            padding-bottom: 20px;
            /* Add some padding below summary before separator */
            margin-bottom: 20px;
            /* Space between summary and list */
            border-bottom: 1px solid var(--border-color);
            /* Separator line */
            text-align: center;
        }

        .agent-availability-summary-section .summary-count {
            font-size: 2.2rem;
            font-weight: bold;
            color: var(--text-color);
            line-height: 1;
        }

        .agent-availability-summary-section .summary-label {
            font-size: 0.85rem;
            color: var(--muted-text-color);
            margin-bottom: 10px;
        }

        .agent-availability-summary-section .progress {
            height: 8px;
            background-color: rgba(255, 255, 255, 0.1);
            width: 100%;
            /* Ensure progress bar takes full width */
        }

        .agent-availability-summary-section .progress-bar {
            background-color: var(--green-online);
        }

        /* --- Response Time Metrics Card --- */
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
            color: var(--accent-blue);
            /* Matching blue in image for 0.00 */
            font-weight: bold;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .response-metrics-card .satisfaction-value .ri-star-fill {
            color: #f1c40f;
            /* Yellow star */
        }

        .response-metrics-card .satisfaction-note {
            font-size: 0.75rem;
            color: var(--muted-text-color);
            margin-top: 5px;
        }

        /* --- Top 5 Percakapan By Tag Card --- */
        .top-tags-card .card-body {
            display: flex;
            flex-direction: column;
            /* Changed to column for list items */
            align-items: center;
            justify-content: center;
            min-height: 120px;
            /* Ensure it has some height */
            color: var(--muted-text-color);
            font-style: italic;
        }

        /* Specific styling for top tags list items added by JS */
        .top-tags-card .card-body>div {
            width: 100%;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.05);
            transition: background-color 0.2s ease;
        }

        .top-tags-card .card-body>div:last-child {
            margin-bottom: 0;
        }

        .top-tags-card .card-body>div:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .top-tags-card .card-body .text-primary {
            color: var(--accent-blue) !important;
        }

        .top-tags-card .card-body .ri-hashtag {
            color: var(--accent-blue);
        }


        /* --- Aktivitas Percakapan Hari Ini Chart Card --- */
        .activity-chart-card .chart-area {
            background-color: var(--chart-bg);
            border-radius: 8px;
            padding: 20px;
            min-height: 250px;
            /* Placeholder height for chart */
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted-text-color);
            font-size: 1rem;
            border: 1px dashed rgba(255, 255, 255, 0.05);
        }

        .activity-chart-card .chart-controls {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 15px;
        }

        .activity-chart-card .form-check-input {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            cursor: pointer;
        }

        .activity-chart-card .form-check-input:checked {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
        }

        .activity-chart-card .form-check-label {
            color: var(--muted-text-color);
            font-size: 0.85rem;
            cursor: pointer;
        }


        /* --- Agent List (Detail part of the new combined card) --- */
        #agentListContainer {
            max-height: var(--agent-list-max-height);
            overflow-y: hidden;
            padding-right: 5px;
            position: relative;
        }

        #agentListContainer.manual-scroll {
            overflow-y: auto;
        }

        /* Custom scrollbar styling */
        #agentListContainer::-webkit-scrollbar {
            width: 5px;
        }

        #agentListContainer::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        #agentListContainer::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        /* Show scrollbar on hover for manual-scroll state */
        #agentListContainer.manual-scroll:hover::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
        }


        .form-control.dark-mode {
            background-color: var(--chart-bg);
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
            background-color: var(--chart-bg);
            color: var(--text-color);
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 0.25rem rgba(74, 144, 226, 0.25);
        }

        .operator-card {
            background-color: var(--main-bg);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: background-color 0.2s ease;
        }

        .operator-card:hover {
            background-color: #252e44;
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
        }

        .status-online {
            background-color: var(--green-online);
            color: white;
        }

        .status-offline {
            background-color: var(--red-offline);
            color: white;
        }

        .operator-metrics {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.05);
            padding-bottom: 10px;
        }

        .operator-metrics div {
            flex: 1;
            text-align: center;
            border-right: 1px dashed rgba(255, 255, 255, 0.05);
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

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(46, 204, 113, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12 mb-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="text-white mb-0">Dashboard Statistik</h3>
                            <p class="text-muted-text-color mb-0" id="current-date-time"></p>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                    id="channel-filter-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-filter-line"></i> <span id="selected-channel-text">All Channels</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="channel-filter-dropdown"
                                    id="channel-filter">
                                    <li><a class="dropdown-item" href="#" data-value="all">All Channels</a></li>
                                </ul>
                            </div>
                            <div id="daterange-display"
                                class="btn btn-outline-secondary d-flex align-items-center gap-2"
                                style="cursor: pointer;">
                                <i class="ri-calendar-line"></i>
                                <span id="daterange-text"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-xl-3 mb-4">
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
                                        <div class="sub-label">Unrecognized</div>
                                        <div class="sub-value" id="unassigned-value"></div>
                                    </div>
                                    <div>
                                        <div class="sub-label">Active</div>
                                        <div class="sub-value" id="active-value"></div>
                                    </div>
                                    <div>
                                        <div class="sub-label">Completed</div>
                                        <div class="sub-value" id="completed-value"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-3 mb-4">
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
                    <div class="col-lg-6 col-xl-3 mb-4">
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
                    <div class="col-lg-6 col-xl-3 mb-4">
                        <div class="card response-metrics-card h-100">
                            <div class="card-header"><i class="ri-star-line ri-icon"></i> Kepuasan Pelanggan</div>
                            <div
                                class="card-body text-center d-flex flex-column justify-content-center position-relative">
                                <div class="loading-overlay" id="loading-customer-satisfaction">
                                    <div class="spinner-border text-light" role="status"><span
                                            class="visually-hidden">Loading...</span></div>
                                </div>
                                <div class="satisfaction-value" id="avg-csat"></div>
                                <div class="satisfaction-note"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card top-tags-card h-100">
                            <div class="card-header"><i class="ri-price-tag-3-line ri-icon"></i> Top 5 Percakapan By
                                Tag</div>
                            <div class="card-body position-relative">
                                <div class="loading-overlay" id="loading-top-tags">
                                    <div class="spinner-border text-light" role="status"><span
                                            class="visually-hidden">Loading...</span></div>
                                </div>
                                <div id="top-tags-content">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card activity-chart-card h-100">
                            <div class="card-header"><i class="ri-bar-chart-line ri-icon"></i> Aktivitas Percakapan
                                Hari Ini <span class="badge bg-secondary ms-2" id="hourly-total-conversations"></span>
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
                                        <input class="form-check-input" type="checkbox" id="all-chart"
                                            value="all" checked>
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
                <div class="card h-100">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>

    <script>
        const BASE_API_URL = 'http://localhost:8000/sosmed';
        let conversationsChartInstance = null;

        let currentChannel = 'all';
        let currentStartDate = moment().startOf('day').format('YYYY-MM-DD');
        let currentEndDate = moment().endOf('day').format('YYYY-MM-DD');

        // Configure Axios instance with custom headers
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ?
            document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

        const customAxios = axios.create({
            baseURL: BASE_API_URL,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Host': 'kotabekasiv2.sakti112.id',
                'Origin': 'https://kotabekasiv2.sakti112.id',
                'sec-ch-ua': '"Google Chrome";v="137", "Chromium";v="137", "Not/A)Brand";v="24"',
                'sec-ch-ua-mobile': '?0',
                'sec-ch-ua-platform': '"Windows"',
                'Sec-Fetch-Dest': 'empty',
                'Sec-Fetch-Mode': 'cors',
                'Sec-Fetch-Site': 'same-origin',
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36',
                'Content-Type': 'application/json'
            }
        });

        // Helper function to manage loading overlays
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

        // --- Date Range Picker Initialization ---
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
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
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
                element.textContent = `${start.format('MMM D, HH:mm')} - ${end.format('MMM D, HH:mm')}`;
            }
        }

        // --- Data Fetching Functions ---

        async function fetchSubscriptionList() {
            try {
                const response = await customAxios.post(`${BASE_API_URL}/subscription`);
                const data = response.data.data;
                const channelFilterUl = document.getElementById('channel-filter');
                const selectedChannelText = document.getElementById('selected-channel-text');

                if (channelFilterUl) {
                    channelFilterUl.innerHTML =
                        '<li><a class="dropdown-item" href="#" data-value="all">All Channels</a></li>';
                    data.forEach(channel => {
                        const li = document.createElement('li');
                        const a = document.createElement('a');
                        a.classList.add('dropdown-item');
                        a.href = "#";
                        a.dataset.value = channel.id;
                        a.textContent = channel.account_name;
                        li.appendChild(a);
                        channelFilterUl.appendChild(li);
                    });

                    const allChannelsOption = channelFilterUl.querySelector('[data-value="all"]');
                    if (allChannelsOption) {
                        selectedChannelText.textContent = allChannelsOption.textContent;
                    }

                    channelFilterUl.addEventListener('click', (event) => {
                        const target = event.target;
                        if (target.classList.contains('dropdown-item')) {
                            currentChannel = target.dataset.value;
                            selectedChannelText.textContent = target.textContent;
                            updateAllDashboardData();
                        }
                    });
                }
            } catch (error) {
                console.error('Error fetching subscription list:', error);
            }
        }

        async function fetchActiveAgentsData(channel) {
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

                const csatValue = typeof data.avg_csat === 'number' && !isNaN(data.avg_csat) ? data.avg_csat.toFixed(
                    2) : '0.00';
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
                // Set default/error values if API fails
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
                updateConversationsChart([], [], [], []); // Clear chart if data fails
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
                            borderColor: 'var(--accent-blue)',
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
                checkbox.addEventListener('change', (e) => {
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
                });
            });
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
                        '<p><i class="ri-information-line"></i> Tidak ada data penggunaan WhatsApp.</p>';
                }


            } catch (error) {
                console.error("Error fetching WhatsApp usage data:", error);
                const container = document.getElementById("wa-usage-content");
                if (container) {
                    container.innerHTML =
                        '<p><i class="ri-information-line"></i> Gagal memuat data penggunaan WhatsApp.</p>';
                }
            } finally {
                toggleLoading('loading-whatsapp-conversations', false);
            }
        }

        // --- Custom Agent List Scrolling Logic (Re-implemented for infinite loop) ---
        let agentScrollInterval;
        const agentScrollSpeed = 0.5; // Pixels per interval
        const agentIntervalTime = 20; // Milliseconds per interval
        let originalAgentContentHeight = 0; // Height of one full set of agent cards

        const agentListContainer = document.getElementById('agentListContainer');
        const agentListContent = document.getElementById('agent-performance-body'); // This is now the content wrapper

        function initializeAgentScrolling() {
            // Stop any existing scroll interval before re-initializing
            stopAgentScrolling();

            // Store initial HTML to be duplicated (based on current filtered/loaded data)
            // It's important to get the current content, not the hardcoded placeholders
            const originalChildrenHtml = Array.from(agentListContent.children)
                .filter(child => child.style.display !== 'none') // Only count visible cards
                .map(child => child.outerHTML)
                .join('');

            // If there's no actual content to scroll, display a message and exit
            if (originalChildrenHtml.trim() === '' || agentListContent.children.length === 0) {
                agentListContent.innerHTML =
                    `<div class="operator-card text-center text-muted-text-color" style="padding: 20px;">Tidak ada data agen untuk ditampilkan.</div>`;
                agentListContainer.scrollTop = 0;
                return;
            }

            // Create a temporary container to accurately measure the height of the current visible cards
            const tempMeasureDiv = document.createElement('div');
            tempMeasureDiv.style.visibility = 'hidden';
            tempMeasureDiv.style.position = 'absolute';
            tempMeasureDiv.style.top = '-9999px';
            tempMeasureDiv.style.width = agentListContent.offsetWidth + 'px';
            tempMeasureDiv.innerHTML = originalChildrenHtml;
            document.body.appendChild(tempMeasureDiv);
            originalAgentContentHeight = tempMeasureDiv.offsetHeight;
            document.body.removeChild(tempMeasureDiv);

            // Clear current content and build the duplicated content
            agentListContent.innerHTML = '';
            // Duplicate the original content multiple times to create the seamless loop effect.
            // Ensure enough duplicates to fill the container and provide buffer for smooth looping.
            const numDuplicates = Math.ceil((agentListContainer.offsetHeight * 2) / originalAgentContentHeight) + 1;
            for (let i = 0; i < numDuplicates; i++) {
                agentListContent.innerHTML += originalChildrenHtml;
            }

            // Reset scroll position and start scrolling
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
                stopAgentScrolling();
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
                                    <span class="fs-5 fw-bold" style="color: var(--accent-blue);">${tag.count}</span>
                                </div>
                            `;
                        });
                    } else {
                        topTagsContent.innerHTML = '<p><i class="ri-information-line"></i> Tidak ada data.</p>';
                    }
                }
            } catch (error) {
                console.error("Error fetching top tags data:", error);
                const topTagsContent = document.getElementById('top-tags-content');
                if (topTagsContent) {
                    topTagsContent.innerHTML = '<p><i class="ri-information-line"></i> Gagal memuat data tag.</p>';
                }
            } finally {
                toggleLoading('loading-top-tags', false);
            }
        }

        // --- Master Data Update Function ---
        function updateAllDashboardData() {
            const momentStart = moment(currentStartDate);
            const momentEnd = moment(currentEndDate);

            updateDateRangeDisplay(document.getElementById('daterange-text'), momentStart, momentEnd);

            // Trigger all data fetches
            fetchActiveAgentsData(currentChannel);
            fetchConversationsSummary(currentChannel, currentStartDate, currentEndDate);
            fetchWhatsAppUsage(currentChannel, currentStartDate, currentEndDate);
            fetchAgentPerformance(currentChannel, currentStartDate, currentEndDate);
            fetchTopTags(currentChannel, currentStartDate, currentEndDate);
        }

        // --- DOM Ready and Initializations ---
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

            // Initial calls to trigger loading states and data fetches
            fetchSubscriptionList().then(() => {
                initializeDateRangePicker(function(start, end) {
                    currentStartDate = start.format('YYYY-MM-DD');
                    currentEndDate = end.format('YYYY-MM-DD');
                    updateAllDashboardData();
                });

                updateAllDashboardData();
            });

            // --- Agent Search Functionality (interacts with custom scroll) ---
            const agentSearchInput = document.getElementById('agentSearchInput');

            agentSearchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const allAgentCardsInContent = agentListContent.querySelectorAll('.operator-card');

                if (searchTerm !== '') {
                    stopAgentScrolling(); // Stop auto-scroll when searching
                    agentListContainer.scrollTop = 0; // Reset scroll to top for search results
                } else {
                    // Resume scrolling only if search term is empty AND mouse is not over container
                    if (!agentListContainer.matches(':hover')) {
                        initializeAgentScrolling
                    (); // Re-initialize to ensure duplication and scroll restart
                    }
                }

                let hasVisibleResults = false;
                // Filter visible cards based on search term. Note: this applies to all duplicated cards.
                // The `initializeAgentScrolling` handles re-duplicating only the *current* visible ones when search is cleared.
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

                // You might want to update the `originalAgentContentHeight` measurement here
                // if you need the infinite scroll to work on the *filtered* list,
                // but that adds complexity and can cause flickering if the user types quickly.
                // For now, the infinite scroll only works for the *full* list when search is inactive.
            });

            // Event listeners for pause on hover for agent list
            agentListContainer.addEventListener('mouseenter', stopAgentScrolling);
            agentListContainer.addEventListener('mouseleave', function() {
                // Only resume scrolling if search input is empty
                if (document.getElementById('agentSearchInput').value.trim() === '') {
                    startAgentScrolling();
                }
            });
        });
    </script>
</body>

</html>
