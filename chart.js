document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('ordersChart').getContext('2d');

    const ordersChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], // Full month names
            datasets: [{
                label: `Orders Count (${new Date().getFullYear()})`,
                data: orderCounts, // Ensure this is a 12-value array, one for each month
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Order Count'
                    },
                    ticks: {
                        stepSize: 5 // Sets the y-axis increments to 10
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: `Order Statistics for ${new Date().getFullYear()}`,
                    font: {
                        size: 18
                    }
                }
            }
        }
    });
});
