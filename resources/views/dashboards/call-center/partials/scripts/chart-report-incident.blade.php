<script>
    const ctx = document.getElementById('chartjsBalanceTrend').getContext('2d');
    let chartBalanceTrend;

    function formatDateDM(date) {
        const d = new Date(date);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        return `${day}/${month}`;
    }

    async function fetchDailyBalanceTrend(startDate, endDate) {
        try {
            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);

            const response = await fetch(`/dashboard/call-center/get-daily-report-chart?${params.toString()}`);
            if (!response.ok) throw new Error('Network response was not ok');
            return await response.json();
        } catch {
            return null;
        }
    }

    function renderChartBalanceTrend(labels, values) {
        if (!Array.isArray(labels) || !Array.isArray(values)) return;

        if (chartBalanceTrend) {
            chartBalanceTrend.data.labels = labels;
            chartBalanceTrend.data.datasets[0].data = values;
            chartBalanceTrend.update();
            return;
        }

        chartBalanceTrend = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Total Laporan',
                    data: values,
                    borderColor: '#60a5fa',
                    backgroundColor: 'rgba(96, 165, 250, 0.15)',
                    fill: true,
                    tension: 0,
                    borderCapStyle: 'butt',
                    borderJoinStyle: 'miter',
                    pointRadius: 0,
                    borderWidth: 4,
                    hoverBorderWidth: 5,
                }]

            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'nearest',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#60a5fa',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            label: ctx => `Total: ${ctx.parsed.y}`
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#4b5563',
                            font: {
                                size: 13,
                                weight: '600'
                            }
                        },
                        border: {
                            display: true,
                            color: '#e5e7eb'
                        }
                    },
                    y: {
                        display: true,
                        grid: {
                            drawBorder: false,
                            color: '#e5e7eb',
                            borderDash: [4, 6],
                        },
                        ticks: {
                            color: '#4b5563',
                            font: {
                                size: 13
                            },
                            beginAtZero: true
                        },
                        border: {
                            display: false
                        }
                    }
                }
            }
        });
    }


    async function updateReport(period) {
        const response = await fetch(`/dashboard/call-center/get-daily-report-chart?period=${period}`);
        if (!response.ok) return;
        const data = await response.json();

        const labels = data.labels || [];
        const values = data.datasets?.[0]?.data || [];

        const totalReport = values.reduce((a, b) => a + b, 0);
        document.getElementById('total-report').textContent = totalReport.toLocaleString();

        document.getElementById('period-label').textContent = period === 'last7days' ? 'Last 7 Days' : 'This Month';

        renderChartBalanceTrend(labels, values);
    }


    document.getElementById('filter-period').addEventListener('change', e => updateReport(e.target.value));

    updateReport('last7days');
</script>
