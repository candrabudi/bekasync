@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .card-custom {
            border-left: 5px solid;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            min-height: 100px;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .card-custom.blue {
            border-left-color: #007bff;
        }

        .card-custom.orange {
            border-left-color: #ff9500;
        }

        .card-custom.cyan {
            border-left-color: #17a2b8;
        }

        .card-custom .icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            transition: background-color 0.3s ease;
            flex-shrink: 0;
        }

        .card-custom .icon.blue-bg {
            background-color: #e7f1ff;
        }

        .card-custom .icon.orange-bg {
            background-color: #fff3e0;
        }

        .card-custom .icon.cyan-bg {
            background-color: #e6f7fa;
        }

        .card-custom .icon img {
            width: 22px;
            height: 22px;
        }

        .card-custom .title {
            font-size: 13px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            white-space: normal;
            overflow-wrap: break-word;
        }

        .card-custom .value {
            font-size: 26px;
            font-weight: 600;
            color: #343a40;
        }

        /* .container {
                    max-width: 1200px;
                } */

        .col-md-3 {
            display: flex;
            justify-content: center;
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
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-end gap-2">
                    <div>
                        <label for="daterange" class="form-label fw-semibold mb-1">Filter Tanggal</label>
                        <input type="text" id="daterange"
                            class="form-control form-control-sm rounded-pill border-secondary"
                            style="cursor: pointer; max-width: 250px;" readonly />
                    </div>
                </div>
            </div>
        </div>


        @include('dashboards.partials.menu')


        <div class="row">
            <!-- Active Agents -->
            <div class="col-md-3">
                <div class="card-custom blue d-flex align-items-center">
                    <div class="icon blue-bg">
                        <img src="https://cdn-icons-png.flaticon.com/512/9095/9095819.png" alt="Agent Icon">
                    </div>
                    <div>
                        <div class="title">Active Agents</div>
                        <div class="value"><span id="active-agent">0</span> / <span id="max-agent">0</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom blue d-flex align-items-center">
                    <div class="icon blue-bg">
                        <img src="https://cdn-icons-png.flaticon.com/512/811/811476.png" alt="Chat Icon">
                    </div>
                    <div>
                        <div class="title">Avg 1st Reply Time</div>
                        <div class="value">00:00:00</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom blue d-flex align-items-center">
                    <div class="icon blue-bg">
                        <img src="https://cdn-icons-png.flaticon.com/512/15480/15480282.png" alt="Reply Icon">
                    </div>
                    <div>
                        <div class="title">Avg Reply Time</div>
                        <div class="value">00:00:00</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom blue d-flex align-items-center">
                    <div class="icon blue-bg">
                        <img src="https://cdn-icons-png.flaticon.com/512/4285/4285648.png" alt="Clock Icon">
                    </div>
                    <div>
                        <div class="title">Avg Duration per Conversation</div>
                        <div class="value">00:00:00</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card-custom orange d-flex align-items-center">
                    <div class="icon orange-bg">
                        <img src="https://cdn-icons-png.flaticon.com/512/190/190406.png" alt="Cross Icon">
                    </div>
                    <div>
                        <div class="title">Unassigned Conversations</div>
                        <div class="value" id="unassigned-value">0</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-custom blue d-flex align-items-center">
                    <div class="icon blue-bg">
                        <img src="https://cdn-icons-png.flaticon.com/512/2972/2972543.png" alt="Hourglass Icon">
                    </div>
                    <div>
                        <div class="title">Active Conversations</div>
                        <div class="value" id="active-value">0</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-custom cyan d-flex align-items-center">
                    <div class="icon cyan-bg">
                        <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="Check Icon">
                    </div>
                    <div>
                        <div class="title">Completed Conversations</div>
                        <div class="value" id="completed-value">0</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-custom blue d-flex align-items-center">
                    <div class="icon blue-bg">
                        <img src="https://cdn-icons-png.flaticon.com/512/14862/14862551.png" alt="Smile Icon">
                    </div>
                    <div>
                        <div class="title">Avg CSAT</div>
                        <div class="value"><span id="avg-csat">0.00</span> / 5</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-stretch">
            <div class="col-md-6 d-flex">
                <div class="card card-flush w-100">
                    <!-- Card Header -->
                    <div class="card-header pt-5 d-flex justify-content-between align-items-start">
                        <h3 class="card-title flex-column">
                            <span class="card-label fw-bold text-gray-800">WhatsApp Conversation Usage</span>
                            <span id="total-conversations" class="text-gray-500 mt-1 fw-semibold fs-6"></span>
                        </h3>

                    </div>

                    <!-- Card Body -->
                    <div id="wa-usage-body" class="card-body">

                    </div>
                </div>
            </div>


            <div class="col-md-6 d-flex">
                <div class="card shadow-sm border-0 w-100">
                    <div class="card-header pt-5 d-flex justify-content-between align-items-start">
                        <h3 class="card-title flex-column">
                            <span class="card-label fw-bold text-gray-800">Hourly Conversations</span>
                            <span id="total-conversations" class="text-gray-500 mt-1 fw-semibold fs-6"></span>
                        </h3>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="flex-grow-1" style="position: relative;">
                            <canvas id="conversationsChart" style="max-height: 350px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row g-5 g-xl-10">
            <div class="col-xxl-12">

                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-7">
                        <h4 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Agent Performance</span>
                        </h4>
                    </div>

                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                <thead>
                                    <tr class="border-bottom-0">
                                        <th class="p-0 w-50px"></th>
                                        <th class="p-0 min-w-175px">Agent</th>
                                        <th class="p-0 min-w-175px">Status</th>
                                        <th class="p-0 min-w-150px">Active Chats</th>
                                        <th class="p-0 min-w-150px">Completed Chats</th>
                                        <th class="p-0 min-w-50px">Avg 1st Reply</th>
                                        <th class="p-0 min-w-50px">Avg Reply</th>
                                        <th class="p-0 min-w-50px">Avg Duration</th>
                                    </tr>
                                </thead>
                                <tbody id="agent-performance-body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        let conversationsChartInstance = null;

        function initializeDateRangePicker(callback) {
            const start = moment().startOf('day');
            const end = moment().endOf('day');

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
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                        .endOf('month')
                    ]
                }
            }, callback);
        }

        function updateDateRangeDisplay(element, start, end) {
            if (element) {
                element.textContent = `${start.format('MMM D, YYYY')} - ${end.format('MMM D, YYYY')}`;
            }
        }

        function fetchActiveAgentsData() {
            axios.get('/dashboard/omni-channel/active-agent')
                .then(function(response) {
                    const data = response.data;
                    const activeAgentEl = document.getElementById('active-agent');
                    const maxAgentEl = document.getElementById('max-agent');
                    if (activeAgentEl) activeAgentEl.innerText = data.active_agent.active || '0';
                    if (maxAgentEl) maxAgentEl.innerText = data.active_agent.max_agent || '0';
                })
                .catch(function(error) {
                    console.error('Error fetching active agents data:', error);
                });
        }

        function fetchConversationsSummary(startDate, endDate) {
            axios.get('/dashboard/omni-channel/conversations-summary', {
                params: {
                    start_date: startDate,
                    end_date: endDate
                }
            }).then(function(response) {
                const data = response.data;

                document.querySelector('.card-custom.blue:nth-child(2) .value').innerText = data
                    .avg_1st_reply_time || '00:00:00';
                document.querySelector('.card-custom.blue:nth-child(3) .value').innerText = data.avg_reply_time ||
                    '00:00:00';
                document.querySelector('.card-custom.blue:nth-child(4) .value').innerText = data
                    .avg_duration_time || '00:00:00';

                const csatValue = typeof data.avg_csat === 'number' && !isNaN(data.avg_csat) ?
                    data.avg_csat.toFixed(2) : '0.00';
                document.getElementById('avg-csat').innerText = csatValue;

                document.getElementById('unassigned-value').innerText = data.conversations_status.unassigned || '0';
                document.getElementById('active-value').innerText = data.conversations_status.active || '0';
                document.getElementById('completed-value').innerText = data.conversations_status.completed || '0';

                const totalHourlyConversations = data.conversations_data.chart_data.datasets.conversations.reduce((
                    sum, value) => sum + value, 0);
                document.getElementById('hourly-total-conversations').innerText =
                    `Total: ${totalHourlyConversations} conversations`;

                updateConversationsChart(data.conversations_data.chart_data.labels, data.conversations_data
                    .chart_data.datasets.conversations);

            }).catch(function(error) {
                console.error('Error fetching conversations summary data:', error);
            });
        }

        function updateConversationsChart(labels, data) {
            const canvas = document.getElementById('conversationsChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            if (conversationsChartInstance) {
                conversationsChartInstance.destroy();
            }

            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(0, 123, 255, 0.3)');
            gradient.addColorStop(1, 'rgba(0, 123, 255, 0)');

            conversationsChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Conversations',
                        data: data,
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: '#007bff',
                        borderWidth: 2,
                        pointRadius: 0,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleColor: '#fff',
                            bodyColor: '#ddd',
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#999',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#999',
                                font: {
                                    size: 12
                                },
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        function fetchWhatsAppUsage(startDate, endDate) {
            axios.get('/dashboard/omni-channel/whatsapp-usage', {
                params: {
                    start_date: startDate,
                    end_date: endDate
                }
            }).then(response => {
                const data = response.data.wa_conversation_usage;
                const total = Object.values(data).reduce((a, b) => a + b, 0);
                const totalEl = document.getElementById("wa-total-conversations");
                if (totalEl) totalEl.innerText = `Total: ${total} conversations`;

                const usageItems = {
                    user_initiated: {
                        label: "User Initiated",
                        icon: "ki-user",
                        color: "info"
                    },
                    business_initiated: {
                        label: "Business Initiated",
                        icon: "ki-briefcase",
                        color: "warning"
                    },
                    service: {
                        label: "Service",
                        icon: "ki-gear",
                        color: "success"
                    },
                    marketing: {
                        label: "Marketing",
                        icon: "ki-megaphone",
                        color: "danger"
                    },
                    utility: {
                        label: "Utility",
                        icon: "ki-clipboard",
                        color: "primary"
                    },
                    authentication: {
                        label: "Authentication",
                        icon: "ki-shield",
                        color: "dark"
                    }
                };

                const container = document.getElementById("wa-usage-body");
                if (container) {
                    container.innerHTML = '';
                    const entries = Object.entries(data);

                    for (let i = 0; i < entries.length; i += 2) {
                        const [key1, value1] = entries[i];
                        const item1 = usageItems[key1];

                        let secondItemHTML = '';
                        if (i + 1 < entries.length) {
                            const [key2, value2] = entries[i + 1];
                            const item2 = usageItems[key2];
                            secondItemHTML = `
                            <div class="d-flex align-items-center me-5 w-50">
                                <div class="symbol symbol-40px me-3">
                                    <span class="symbol-label bg-light-${item2.color}">
                                        <i class="ki-duotone ${item2.icon} fs-2x text-${item2.color}"></i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-800 fw-bold fs-6">${item2.label}</span>
                                    <span class="fw-semibold fs-7 d-block text-gray-600">${value2} messages</span>
                                </div>
                            </div>
                        `;
                        }

                        container.innerHTML += `
                        <div class="d-flex justify-content-between mb-4">
                            <div class="d-flex align-items-center me-5 w-50">
                                <div class="symbol symbol-40px me-3">
                                    <span class="symbol-label bg-light-${item1.color}">
                                        <i class="ki-duotone ${item1.icon} fs-2x text-${item1.color}"></i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-800 fw-bold fs-6">${item1.label}</span>
                                    <span class="fw-semibold fs-7 d-block text-gray-600">${value1} messages</span>
                                </div>
                            </div>
                            ${secondItemHTML}
                        </div>
                    `;
                    }
                }
            }).catch(error => {
                console.error("Error fetching WhatsApp usage data:", error);
            });
        }

        function fetchAgentPerformance(startDate, endDate) {
            axios.get('/dashboard/omni-channel/agent-performance', {
                params: {
                    start_date: startDate,
                    end_date: endDate
                }
            }).then(response => {
                const users = response.data.users;
                const tableBody = document.getElementById('agent-performance-body');
                if (tableBody) {
                    tableBody.innerHTML = '';

                    users.forEach(user => {
                        const statusDisplay = user.status_online === 'online' ? 'Online' : 'Offline';
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>
                            <div class="symbol symbol-40px">
                                <span class="symbol-label bg-light-info">
                                    <i class="ki-duotone ki-abstract-24 fs-2x text-info"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                        </td>
                        <td class="ps-0">
                            <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">${user.fullname || user.username}</a>
                            <span class="text-muted fw-semibold d-block fs-7">${statusDisplay}</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${statusDisplay}</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.active_chat || '0'}</span>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Active Chats</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.completed_chat || '0'}</span>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Completed Chats</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.avg_1st_reply_time || '00:00:00'}</span>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Avg 1st Reply</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.avg_reply_time || '00:00:00'}</span>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Avg Reply</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.avg_duration_time || '00:00:00'}</span>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Avg Duration</span>
                        </td>
                    `;
                        tableBody.appendChild(row);
                    });
                }
            }).catch(error => {
                console.error("Error fetching agent performance data:", error);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchActiveAgentsData();

            const conversationsRangeText = document.getElementById("conversations-range-text");
            const waRangeText = document.getElementById("wa-range-text");
            const agentRangeText = document.getElementById("agent-range-text");

            let conversationsStart = moment().startOf('month');
            let conversationsEnd = moment().endOf('month');
            let waStart = moment().startOf('month');
            let waEnd = moment().endOf('month');
            let agentStart = moment().startOf('month');
            let agentEnd = moment().endOf('month');

            initializeDateRangePicker(function(start, end, label) {
                updateDateRangeDisplay(conversationsRangeText, start, end);
                updateDateRangeDisplay(waRangeText, start, end);
                updateDateRangeDisplay(agentRangeText, start, end);

                conversationsStart = start;
                conversationsEnd = end;
                waStart = start;
                waEnd = end;
                agentStart = start;
                agentEnd = end;

                fetchConversationsSummary(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                fetchWhatsAppUsage(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
                fetchAgentPerformance(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });

            updateDateRangeDisplay(conversationsRangeText, conversationsStart, conversationsEnd);
            fetchConversationsSummary(conversationsStart.format('YYYY-MM-DD'), conversationsEnd.format(
                'YYYY-MM-DD'));

            updateDateRangeDisplay(waRangeText, waStart, waEnd);
            fetchWhatsAppUsage(waStart.format('YYYY-MM-DD'), waEnd.format('YYYY-MM-DD'));

            updateDateRangeDisplay(agentRangeText, agentStart, agentEnd);
            fetchAgentPerformance(agentStart.format('YYYY-MM-DD'), agentEnd.format('YYYY-MM-DD'));
        });
    </script>
@endpush
