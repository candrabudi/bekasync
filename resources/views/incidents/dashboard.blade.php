@extends('layouts.app') {{-- Assumes you have a layouts/app.blade.php file --}}

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bs-body-font-family: 'Inter', sans-serif;
            --card-border-radius: 1.25rem;
            --card-box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --card-hover-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            --icon-bg-light: rgba(var(--bs-primary-rgb), 0.1);
            --text-primary-dark: #2c3e50;
            --bg-light-blue: #e6f0ff;
            --bg-light-green: #e8f9e6;
            --bg-light-cyan: #e6f8fa;
            --bg-light-yellow: #fff8e6;
            --bg-light-red: #ffebe6;
            --bg-light-grey: #f0f3f6;
        }
/* 
        body {
            background-color: #f8fafd;
            font-family: var(--bs-body-font-family);
            color: #34495e;
        } */

        .container.py-4 {
            padding-top: 2.5rem !important;
            padding-bottom: 2.5rem !important;
        }

        .card {
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: var(--card-box-shadow);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--card-hover-shadow);
            transform: translateY(-5px);
        }

        .card-header-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #ebf2f7;
            padding: 1.5rem 2rem;
            border-top-left-radius: var(--card-border-radius);
            border-top-right-radius: var(--card-border-radius);
        }

        .card-body-custom {
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #5d6d7e;
            margin-bottom: .6rem;
            font-size: 0.95rem;
        }

        .form-control {
            border-radius: .75rem;
            padding: .85rem 1.2rem;
            border: 1px solid #e0e6ed;
            background-color: #ffffff;
            font-size: 0.95rem;
            color: #495c6f;
        }

        .form-control:focus {
            border-color: #8bb4e0;
            box-shadow: 0 0 0 0.2rem rgba(66, 133, 244, 0.2);
        }

        .input-group-text {
            background-color: #ffffff;
            border: 1px solid #e0e6ed;
            border-right: none;
            border-radius: .75rem 0 0 .75rem;
            padding: .85rem 1rem;
            color: #7f8c8d;
        }

        .input-group-text+.form-control {
            border-left: none;
        }

        .form-control.rounded-end-pill {
            border-top-right-radius: .75rem !important;
            border-bottom-right-radius: .75rem !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .dashboard-header h2 {
            font-weight: 800;
            color: var(--text-primary-dark);
            font-size: 2.2rem;
        }

        .dashboard-header .breadcrumb-item a {
            color: #95a5a6;
            font-weight: 500;
        }

        .dashboard-header .breadcrumb-item.active {
            color: #7f8c8d;
            font-weight: 600;
        }

        .summary-card {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 1.8rem;
            height: 100%;
        }

        .summary-card .icon-wrapper {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 0.8rem;
            padding: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 55px;
            height: 55px;
            margin-bottom: 1.2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .summary-card .icon-wrapper i {
            font-size: 1.8rem;
            line-height: 1;
        }

        .summary-card.bg-primary-subtle {
            background-color: var(--bg-light-blue) !important;
        }

        .summary-card.bg-primary-subtle .icon-wrapper {
            background-color: rgba(66, 133, 244, 0.1);
        }

        .summary-card.bg-primary-subtle .icon-wrapper i {
            color: #4285F4;
        }

        .summary-card.bg-primary-subtle h2 {
            color: var(--text-primary-dark);
        }

        .summary-card.bg-success-subtle {
            background-color: var(--bg-light-green) !important;
        }

        .summary-card.bg-success-subtle .icon-wrapper {
            background-color: rgba(15, 157, 88, 0.1);
        }

        .summary-card.bg-success-subtle .icon-wrapper i {
            color: #34A853;
        }

        .summary-card.bg-success-subtle h2 {
            color: var(--text-primary-dark);
        }

        .summary-card.bg-info-subtle {
            background-color: var(--bg-light-cyan) !important;
        }

        .summary-card.bg-info-subtle .icon-wrapper {
            background-color: rgba(60, 186, 219, 0.1);
        }

        .summary-card.bg-info-subtle .icon-wrapper i {
            color: #3cbadb;
        }

        .summary-card.bg-info-subtle h2 {
            color: var(--text-primary-dark);
        }

        .summary-card.bg-warning-subtle {
            background-color: var(--bg-light-yellow) !important;
        }

        .summary-card.bg-warning-subtle .icon-wrapper {
            background-color: rgba(244, 180, 0, 0.1);
        }

        .summary-card.bg-warning-subtle .icon-wrapper i {
            color: #FBBC05;
        }

        .summary-card.bg-warning-subtle h2 {
            color: var(--text-primary-dark);
        }

        .summary-card.bg-danger-subtle {
            background-color: #ffebe6 !important;
        }

        .summary-card.bg-danger-subtle .icon-wrapper {
            background-color: rgba(219, 68, 55, 0.1);
        }

        .summary-card.bg-danger-subtle .icon-wrapper i {
            color: #EA4335;
        }

        .summary-card.bg-danger-subtle h2 {
            color: var(--text-primary-dark);
        }

        .summary-card.bg-secondary-subtle {
            background-color: #f0f3f6 !important;
        }

        .summary-card.bg-secondary-subtle .icon-wrapper {
            background-color: rgba(127, 140, 141, 0.1);
        }

        .summary-card.bg-secondary-subtle .icon-wrapper i {
            color: #607D8B;
        }

        .summary-card.bg-secondary-subtle h2 {
            color: var(--text-primary-dark);
        }

        .summary-card h6 {
            font-size: 0.85rem;
            color: #7f8c8d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 0.3rem;
        }

        .summary-card h2 {
            font-size: 2.1rem;
            font-weight: 700;
            line-height: 1.2;
            color: var(--text-primary-dark);
        }

        .chart-container {
            position: relative;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #chartCategory {
            max-height: 550px;
            height: auto;
        }

        #chartDinas {
            max-height: 450px;
            height: auto;
        }

        @media (max-width: 767.98px) {
            .summary-card {
                padding: 1.5rem;
                align-items: center;
            }

            .summary-card .icon-wrapper {
                margin-bottom: 0.8rem;
            }

            .summary-card h6,
            .summary-card h2 {
                text-align: center;
            }

            .chart-container {
                height: 300px;
            }

            .card-header-custom,
            .card-body-custom {
                padding: 1.2rem 1.5rem;
            }

            .dashboard-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
@endpush


@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-5 dashboard-header">
            <h2 class="fw-bold text-dark">Dashboard Laporan Insiden</h2>
        </div>

        <div class="row g-4 align-items-end mb-5">
            <div class="col-md-4">
                <label for="daterange" class="form-label">Rentang Waktu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar-range"></i></span>
                    <input type="text" id="daterange" class="form-control rounded-end-pill">
                </div>
            </div>
            <div class="col-md-8 d-flex justify-content-end">

            </div>
        </div>
        <div class="row g-4 mb-5" id="summaryCards">
            @for ($i = 0; $i < 8; $i++)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="card p-4 shadow-sm summary-card placeholder-glow bg-white">
                        <div class="icon-wrapper placeholder bg-secondary rounded-3"></div>
                        <div>
                            <h6 class="text-muted placeholder col-7"></h6>
                            <h2 class="placeholder col-8"></h2>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header-custom">
                        <h5 class="card-title mb-0 fw-bold text-dark">Kejadian per Kategori</h5>
                    </div>
                    <div class="card-body-custom d-flex flex-column">
                        <div class="overflow-auto flex-grow-1" style="min-height: 400px;">
                            <canvas id="chartCategory"></canvas>
                        </div>
                        <p id="noDataCategory" class="text-muted text-center mt-3 d-none">Tidak ada data untuk kategori pada
                            rentang waktu ini.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header-custom">
                        <h5 class="card-title mb-0 fw-bold text-dark">Distribusi Dinas</h5>
                    </div>
                    <div class="card-body-custom d-flex flex-column">
                        <div class="chart-container flex-grow-1" style="min-height: 200px;">
                            <canvas id="chartDinas"></canvas>
                        </div>
                        <p id="noDataDinas" class="text-muted text-center mt-3 d-none">Tidak ada data untuk dinas pada
                            rentang waktu ini.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header-custom">
                        <h5 class="card-title mb-0 fw-bold text-dark">Tren Kejadian Harian</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="chart-container" style="height: 350px;">
                            <canvas id="chartTrend"></canvas>
                        </div>
                        <p id="noDataTrend" class="text-muted text-center mt-3 d-none">Tidak ada data tren harian pada
                            rentang waktu ini.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header-custom">
                        <h5 class="card-title mb-0 fw-bold text-dark">Kejadian per Kecamatan</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="chart-container" style="height: 350px;">
                            <canvas id="chartLocation"></canvas>
                        </div>
                        <p id="noDataLocation" class="text-muted text-center mt-3 d-none">Tidak ada data untuk kecamatan
                            pada rentang waktu ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <script>
        feather.replace();

        let charts = {};
        let selectedStart = moment().subtract(30, 'days');
        let selectedEnd = moment();

        const CHART_COLORS = [
            '#4285F4',
            '#34A853',
            '#FBBC05',
            '#EA4335',
            '#8E24AA',
            '#009688',
            '#FF9800',
            '#607D8B',
            '#2196F3',
            '#4CAF50',
            '#FFC107',
            '#F44336',
            '#673AB7',
            '#00BCD4',
            '#FF5722',
            '#9E9E9E',
            '#795548',
            '#E91E63'
        ];

        const CHART_BORDER_COLORS = [
            '#1976D2',
            '#2E7D32',
            '#F9A825',
            '#C62828',
            '#4A148C',
            '#004D40',
            '#E65100',
            '#455A64',
            '#1565C0',
            '#2E7D32',
            '#FFB300',
            '#D32F2F',
            '#512DA8',
            '#00838F',
            '#D84315',
            '#616161',
            '#5D4037',
            '#C2185B'
        ];


        document.addEventListener('DOMContentLoaded', () => {
            const selectedStart = moment().startOf('month');
            const selectedEnd = moment().endOf('month');

            $('#daterange').daterangepicker({
                startDate: selectedStart,
                endDate: selectedEnd,
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Tahun Ini': [moment().startOf('year'), moment().endOf('year')]
                },
                locale: {
                    format: 'DD MMMM YYYY',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                    fromLabel: 'Dari',
                    toLabel: 'Sampai',
                    customRangeLabel: 'Rentang Kustom',
                    weekLabel: 'M',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ],
                    firstDay: 1
                }
            }, function(start, end) {
                $('#daterange').val(start.format('DD MMMM YYYY') + ' - ' + end.format('DD MMMM YYYY'));
                fetchDashboardData();
            });

            $('#daterange').val(selectedStart.format('DD MMMM YYYY') + ' - ' + selectedEnd.format('DD MMMM YYYY'));

            fetchDashboardData();
        });


        function trimLabel(label, maxLength = 20) {
            const displayLabel = label === null || label === '' ? 'Tidak Diketahui' : String(label);
            return displayLabel.length > maxLength ? displayLabel.slice(0, maxLength) + '...' : displayLabel;
        }

        function toggleNoDataMessage(chartId, hasData) {
            const messageElement = document.getElementById(`noData${chartId.replace('chart', '')}`);
            const canvasElement = document.getElementById(chartId);

            if (messageElement && canvasElement) {
                if (hasData) {
                    messageElement.classList.add('d-none');
                    canvasElement.classList.remove('d-none');
                } else {
                    messageElement.classList.remove('d-none');
                    canvasElement.classList.add('d-none');
                    if (charts[chartId]) {
                        charts[chartId].destroy();
                        delete charts[chartId];
                    }
                }
            }
        }

        function fetchDashboardData() {
            const start = selectedStart.format('YYYY-MM-DD');
            const end = selectedEnd.format('YYYY-MM-DD');

            document.getElementById('summaryCards').innerHTML = `
                @for ($i = 0; $i < 8; $i++)
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card p-4 shadow-sm summary-card placeholder-glow bg-white">
                            <div class="icon-wrapper placeholder bg-secondary rounded-3"></div>
                            <div>
                                <h6 class="text-muted placeholder col-7"></h6>
                                <h2 class="placeholder col-8"></h2>
                            </div>
                        </div>
                    </div>
                @endfor
            `;

            axios.get('/incidents/dashboard/data', {
                    params: {
                        start,
                        end
                    }
                })
                .then(res => {
                    const data = res.data;

                    const totalIncidents = data.summary.total || 0;
                    const incidentsToday = data.summary.today || 0;
                    const incidentsThisWeek = data.summary.this_week ?? 0;
                    const incidentsThisMonth = data.summary.this_month ?? 0;

                    let topCategory = 'N/A';
                    let topCategoryCount = 0;
                    if (data.by_category && data.by_category.length > 0) {
                        const meaningfulCategories = data.by_category.filter(d => d.category !== '-' && d.total > 0);
                        if (meaningfulCategories.length > 0) {
                            const sortedCategories = meaningfulCategories.sort((a, b) => b.total - a.total);
                            topCategory = sortedCategories[0].category;
                            topCategoryCount = sortedCategories[0].total;
                        } else if (data.by_category.some(d => d.category === '-' && d.total > 0)) {
                            const unknownCategory = data.by_category.find(d => d.category === '-');
                            topCategory = 'Kategori Lainnya';
                            topCategoryCount = unknownCategory.total;
                        }
                    }

                    let topDinas = 'N/A';
                    let topDinasCount = 0;
                    if (data.by_dinas && data.by_dinas.length > 0) {
                        const sortedDinas = data.by_dinas.sort((a, b) => b.total - a.total);
                        if (sortedDinas[0].total > 0) {
                            topDinas = sortedDinas[0].dinas;
                            topDinasCount = sortedDinas[0].total;
                        }
                    }

                    const incidentsWithLocation = (data.by_location || []).filter(d => d.subdistrict !== null).reduce((
                        sum, d) => sum + d.total, 0);
                    const activeDinasCount = (data.by_dinas || []).filter(d => d.total > 0).length;
                    const distinctCategoriesCount = (data.by_category || []).filter(d => d.category !== '-').length;
                    const lastDayTrend = (data.trend && data.trend.length > 0) ? data.trend[data.trend.length - 1]
                        .total : 0;

                    let avgDailyIncidents = 0;
                    if (data.trend && data.trend.length > 0) {
                        const totalTrendIncidents = data.trend.reduce((sum, item) => sum + item.total, 0);
                        avgDailyIncidents = (totalTrendIncidents / data.trend.length).toFixed(1);
                    }

                    document.getElementById('summaryCards').innerHTML = `
 ${createCard('grid', 'Total kejadian', totalIncidents, 'bg-primary-subtle')}
 ${createCard('activity', 'Kejadian hari ini', incidentsToday, 'bg-success-subtle')}
 ${createCard('calendar', 'Minggu ini', incidentsThisWeek, 'bg-info-subtle')}
 ${createCard('archive', 'Bulan ini', incidentsThisMonth, 'bg-warning-subtle')}
 ${createCard('tag', 'Kategori terbanyak', trimLabel(topCategory, 12) + ' (' + topCategoryCount + ')', 'bg-danger-subtle', topCategory)}
 ${createCard('briefcase', 'Dinas terlibat', trimLabel(topDinas, 12) + ' (' + topDinasCount + ')', 'bg-secondary-subtle', topDinas)}
 ${createCard('map-pin', 'Berlokasi', incidentsWithLocation, 'bg-info-subtle')}
 ${createCard('list', 'Kategori unik', distinctCategoriesCount, 'bg-warning-subtle')}
 ${createCard('trending-up', 'Kejadian terakhir', lastDayTrend, 'bg-success-subtle')}
 ${createCard('bar-chart-2', 'Rata-rata harian', avgDailyIncidents, 'bg-secondary-subtle')}
`;

                    function createCard(icon, title, value, bgClass, tooltip = '') {
                        return `
    <div class="col-6 col-md-4 col-lg-3 col-xl-2 mb-3">
      <div class="card shadow-sm rounded-4 summary-card ${bgClass} p-3 h-100 d-flex flex-column justify-content-between">
        <div class="d-flex align-items-center mb-2">
          <i data-feather="${icon}" class="me-2 text-dark" style="width:18px;height:18px;"></i>
          <small class="text-muted fw-medium">${title}</small>
        </div>
        <div>
          <h5 class="mb-0 fw-semibold" title="${tooltip}">${value}</h5>
        </div>
      </div>
    </div>
 `;
                    }

                    feather.replace();

                    Chart.defaults.font.family = 'var(--bs-body-font-family)';
                    Chart.defaults.font.size = 12;
                    Chart.defaults.color = '#34495e';
                    Chart.defaults.borderColor = '#e0e6ed';
                    Chart.defaults.responsive = true;
                    Chart.defaults.maintainAspectRatio = false;
                    Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(44, 62, 80, 0.9)';
                    Chart.defaults.plugins.tooltip.titleColor = '#ecf0f1';
                    Chart.defaults.plugins.tooltip.bodyColor = '#ecf0f1';
                    Chart.defaults.plugins.tooltip.borderColor = '#34495e';
                    Chart.defaults.plugins.tooltip.borderWidth = 1;
                    Chart.defaults.plugins.tooltip.cornerRadius = 8;
                    Chart.defaults.plugins.tooltip.displayColors = true;

                    Chart.defaults.plugins.legend.labels.font = {
                        size: 11,
                        weight: '500'
                    };
                    Chart.defaults.plugins.legend.labels.boxWidth = 15;
                    Chart.defaults.plugins.legend.labels.padding = 10;
                    Chart.defaults.plugins.legend.labels.color = '#5d6d7e';

                    let categoriesData = (data.by_category || []).filter(d => d.total > 0);
                    if (categoriesData.some(d => d.category !== '-' && d.total > 0)) {
                        categoriesData = categoriesData.filter(d => d.category !== '-');
                    }
                    categoriesData.sort((a, b) => b.total - a.total);

                    const hasCategoryData = categoriesData.length > 0;
                    toggleNoDataMessage('chartCategory', hasCategoryData);
                    if (hasCategoryData) {
                        const labels = categoriesData.map(d => d.category === '-' ? 'Kategori Lainnya' : d.category);
                        const totals = categoriesData.map(d => d.total);

                        const maxLabelLength = 20;
                        const shortenedLabels = labels.map(label =>
                            label.length > maxLabelLength ? label.slice(0, maxLabelLength) + '…' : label
                        );

                        const colors = [
                            '#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f',
                            '#edc949', '#af7aa1', '#ff9da7', '#9c755f', '#bab0ab'
                        ];
                        const backgroundColors = totals.map((_, i) => colors[i % colors.length]);

                        renderChart('chartCategory', 'doughnut', shortenedLabels, totals, 'Jumlah Kejadian', {
                            plugins: {
                                tooltip: {
                                    backgroundColor: '#fff',
                                    titleColor: '#333',
                                    bodyColor: '#000',
                                    borderColor: '#ccc',
                                    borderWidth: 1,
                                    callbacks: {
                                        label: function(context) {
                                            const value = context.raw;
                                            const fullLabel = labels[context.dataIndex];
                                            return `${fullLabel}: ${new Intl.NumberFormat('id-ID').format(value)}`;
                                        }
                                    }
                                },
                                datalabels: {
                                    display: true,
                                    color: '#fff',
                                    formatter: (value, context) => {
                                        const total = context.chart.data.datasets[0].data.reduce((a, b) =>
                                            a + b, 0);
                                        const percentage = (value / total) * 100;
                                        return percentage >= 5 ? `${percentage.toFixed(1)}%` : '';
                                    },
                                    font: {
                                        size: 13,
                                        weight: 'bold'
                                    }
                                },
                                legend: {
                                    position: 'right',
                                    align: 'center',
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: {
                                            size: 12
                                        }
                                    }
                                }
                            },
                            cutout: '55%',
                            animation: {
                                animateRotate: true,
                                duration: 1000
                            },
                            datasets: [{
                                backgroundColor: backgroundColors
                            }]
                        });
                    }


                    const dinasFiltered = (data.by_dinas || []).filter(d => d.total > 0);
                    const hasDinasData = dinasFiltered.length > 0;
                    toggleNoDataMessage('chartDinas', hasDinasData);
                    if (hasDinasData) {
                        const dinasTotalSum = dinasFiltered.reduce((sum, d) => sum + d.total, 0);
                        const originalDinasLabels = dinasFiltered.map(d => d.dinas);

                        renderChart('chartDinas', 'pie', originalDinasLabels, dinasFiltered.map(d => d.total),
                            'Distribusi Dinas', {
                                plugins: {
                                    datalabels: {
                                        display: true,
                                        color: '#fff',
                                        formatter: (value, context) => {
                                            if (value === 0) return '';
                                            const percentage = ((value / dinasTotalSum) * 100).toFixed(1);
                                            return percentage + '%';
                                        },
                                        font: {
                                            weight: 'bold',
                                            size: 11
                                        },
                                        textShadow: true,
                                        shadowColor: 'rgba(0,0,0,0.4)',
                                        shadowBlur: 5
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = originalDinasLabels[context.dataIndex] || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                label += new Intl.NumberFormat('id-ID').format(context.raw) +
                                                    ' kejadian';
                                                return label;
                                            },
                                            title: function(context) {
                                                return originalDinasLabels[context[0].dataIndex];
                                            }
                                        }
                                    },
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            boxWidth: 15,
                                            padding: 10,
                                            font: {
                                                size: 11
                                            }
                                        }
                                    }
                                }
                            });
                    }

                    const hasTrendData = data.trend && data.trend.length > 0;
                    toggleNoDataMessage('chartTrend', hasTrendData);
                    if (hasTrendData) {
                        renderChart('chartTrend', 'line', data.trend.map(d => moment(d.date).format('DD MMM')), data
                            .trend.map(d => d.total),
                            'Jumlah Kejadian Harian', {
                                plugins: {
                                    datalabels: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        ticks: {
                                            autoSkip: true,
                                            maxRotation: 0,
                                            minRotation: 0,
                                            color: '#5d6d7e',
                                            font: {
                                                size: 11
                                            }
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: '#ebf2f7'
                                        },
                                        ticks: {
                                            callback: function(value) {
                                                if (value >= 1000000) return (value / 1000000).toFixed(1) +
                                                    ' JT';
                                                if (value >= 1000) return (value / 1000).toFixed(0) + ' RB';
                                                return value;
                                            },
                                            color: '#5d6d7e',
                                            font: {
                                                size: 11
                                            }
                                        }
                                    }
                                },
                                elements: {
                                    line: {
                                        backgroundColor: 'rgba(66, 133, 244, 0.2)',
                                        borderColor: '#4285F4',
                                        tension: 0.3,
                                        borderWidth: 3,
                                        fill: true
                                    },
                                    point: {
                                        backgroundColor: '#4285F4',
                                        borderColor: '#fff',
                                        borderWidth: 2,
                                        radius: 4,
                                        hoverRadius: 6
                                    }
                                }
                            });
                    }

                    const locationsData = (data.by_location || []).filter(d => d.subdistrict !== null && d.total > 0);
                    locationsData.sort((a, b) => b.total - a.total);
                    const hasLocationData = locationsData.length > 0;
                    toggleNoDataMessage('chartLocation', hasLocationData);
                    if (hasLocationData) {
                        const labels = locationsData.map(d => d.subdistrict);
                        const totals = locationsData.map(d => d.total);

                        renderChart('chartLocation', 'bar', labels, totals, 'Jumlah Kejadian', {
                            plugins: {
                                datalabels: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 0,
                                        autoSkip: true,
                                        color: '#5d6d7e',
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#ebf2f7'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            if (value >= 1000000) return (value / 1000000).toFixed(1) +
                                                ' JT';
                                            if (value >= 1000) return (value / 1000).toFixed(0) + ' RB';
                                            return value;
                                        },
                                        color: '#5d6d7e',
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            },
                            barThickness: 20,
                            maxBarThickness: 30,
                        });
                    }

                })
                .catch(error => {
                    console.error('Error fetching dashboard data:', error);
                    document.getElementById('summaryCards').innerHTML = `
                        <div class="col-12"><div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Gagal memuat data dashboard. Silakan coba lagi nanti.
                        </div></div>`;
                    toggleNoDataMessage('chartCategory', false);
                    toggleNoDataMessage('chartDinas', false);
                    toggleNoDataMessage('chartTrend', false);
                    toggleNoDataMessage('chartLocation', false);
                });
        }

        function renderChart(id, type, labels, data, label, extraOptions = {}) {
            if (charts[id]) charts[id].destroy();

            const displayLabelsForLegend = type === 'pie' ? labels.map(l => trimLabel(l, 20)) : labels;

            charts[id] = new Chart(document.getElementById(id), {
                type,
                data: {
                    labels: displayLabelsForLegend,
                    datasets: [{
                        label,
                        data,
                        backgroundColor: type === 'pie' ? CHART_COLORS : CHART_COLORS[
                            0],
                        borderColor: type === 'pie' ? CHART_BORDER_COLORS : CHART_BORDER_COLORS[
                            0],
                        borderWidth: type === 'pie' ? 2 : 1,
                        fill: false,
                        tension: type === 'line' ? 0.3 : 0,
                        borderRadius: type === 'bar' ? 6 : 0,
                        barPercentage: 0.8,
                        categoryPercentage: 0.8
                    }]
                },
                options: {
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        datalabels: {
                            display: type !== 'pie',
                            color: '#495c6f',
                            anchor: 'end',
                            align: 'top',
                            font: {
                                weight: 'bold',
                                size: 10
                            },
                            formatter: (value) => {
                                if (value >= 1000000) return (value / 1000000).toLocaleString('id-ID', {
                                    maximumFractionDigits: 1
                                }) + ' JT';
                                if (value >= 1000) return (value / 1000).toLocaleString('id-ID', {
                                    maximumFractionDigits: 0
                                }) + ' RB';
                                return value.toLocaleString('id-ID');
                            }
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    return labels[context[0].dataIndex];
                                },
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += new Intl.NumberFormat('id-ID').format(context.raw);
                                    return label;
                                }
                            }
                        }
                    },
                    ...extraOptions
                },
                plugins: [ChartDataLabels]
            });
        }
    </script>
@endpush
