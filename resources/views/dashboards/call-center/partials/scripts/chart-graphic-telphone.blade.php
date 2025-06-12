<script>
    let chartCdrHourly;

    async function fetchCdrHourlyChart(startDate, endDate) {
        const date = startDate.format('YYYY-MM-DD');
        const response = await fetch(`/dashboard/call-center/hourly-stats?date=${date}`);
        const data = await response.json();

        const labels = data.map(d => d.hour);
        const abandoned = data.map(d => d.abandoned);
        const active = data.map(d => d.active);
        const enCola = data.map(d => d.en_cola);
        const completed = data.map(d => d.completed);

        const dataset = {
            labels: labels,
            datasets: [{
                    label: 'Ditinggalkan',
                    data: abandoned,
                    borderColor: '#ef4444',
                    backgroundColor: 'transparent',
                    tension: 0.5,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    borderWidth: 3,
                },
                {
                    label: 'Aktif',
                    data: active,
                    borderColor: '#3b82f6',
                    backgroundColor: 'transparent',
                    tension: 0.5,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    borderWidth: 3,
                },
                {
                    label: 'Dalam Antrean',
                    data: enCola,
                    borderColor: '#f59e0b',
                    backgroundColor: 'transparent',
                    tension: 0.5,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    borderWidth: 3,
                },
                {
                    label: 'Selesai',
                    data: completed,
                    borderColor: '#10b981',
                    backgroundColor: 'transparent',
                    tension: 0.5,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    borderWidth: 3,
                }
            ]
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
                    backgroundColor: '#111827',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 6,
                    padding: 10
                },
                legend: {
                    display: true,
                    labels: {
                        color: '#374151',
                        font: {
                            size: 13,
                            weight: '500'
                        },
                        usePointStyle: true,
                        pointStyle: 'line'
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12
                        },
                        beginAtZero: true
                    },
                    border: {
                        display: false
                    }
                }
            }
        };

        const ctx = document.getElementById('ctxThree').getContext('2d');

        if (chartCdrHourly) {
            chartCdrHourly.destroy();
        }

        chartCdrHourly = new Chart(ctx, {
            type: 'line',
            data: dataset,
            options: options
        });
    }
</script>
