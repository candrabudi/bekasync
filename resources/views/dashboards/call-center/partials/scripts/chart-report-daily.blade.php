<script>
    const ctxTwo = document.getElementById('dailyReportChart').getContext('2d');
    let chartDailyReport;

    function renderChart(chartData) {
        const data = {
            labels: chartData.labels,
            datasets: chartData.datasets.map(ds => ({
                label: ds.label,
                data: ds.data,
                borderColor: ds.borderColor,
                backgroundColor: ds.backgroundColor,
                fill: false,
                tension: 0.5,
                pointRadius: 0,
                pointHoverRadius: 6,
                borderWidth: 3,
                pointBackgroundColor: ds.borderColor,
                clip: 10,
            })),
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'nearest',
                intersect: false
            },
            plugins: {
                tooltip: {
                    backgroundColor: '#1a73e8',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 6,
                    padding: 10,
                    displayColors: true,
                },
                legend: {
                    display: true
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        beginAtZero: true,
                        stepSize: 5
                    },
                    border: {
                        display: false
                    }
                }
            }
        };

        if (chartDailyReport) {
            chartDailyReport.data = data;
            chartDailyReport.options = options;
            chartDailyReport.update();
        } else {
            chartDailyReport = new Chart(ctxTwo, {
                type: 'line',
                data: data,
                options: options
            });
        }
    }

    function fetchChartData(startDate, endDate) {
        const start = moment(startDate).format('YYYY-MM-DD');
        const end = moment(endDate).format('YYYY-MM-DD');
        const url = `/dashboard/call-center/daily-report-chart-hourly?start_date=${start}&end_date=${end}`;

        axios.get(url)
            .then(response => {
                renderChart(response.data);
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);
            });
    }


    // Panggil awal saat page load
    document.addEventListener('DOMContentLoaded', () => {
        const today = moment().format('YYYY-MM-DD');
        fetchChartData(today, today);
    });
</script>
