const canvas = document.getElementById('sales-chart');

if (canvas) {
    const { Chart, registerables } = await import('chart.js');
    Chart.register(...registerables);

    const labels = JSON.parse(canvas.dataset.labels || '[]');
    const values = JSON.parse(canvas.dataset.values || '[]');

    new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Выручка, ₽',
                data: values,
                borderColor: '#1abb9c',
                backgroundColor: 'rgba(26, 187, 156, 0.15)',
                fill: true,
                tension: 0.35,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
}
