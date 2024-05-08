<div class="container card pt-5 mb-5" id="chart-description">
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">Service Providers Per Disability Category in {{ $opdName }}</h5>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="opdServiceProviderChart"></canvas>
    </div>
</div>

<script>
    const opd_service_data = @json($opdServiceCounts);

    var ctx = document.getElementById('opdServiceProviderChart').getContext('2d');
    var initialData = {
        labels: Object.keys(opd_service_data),
        datasets: [{
            label: 'Service Providers by Disability Category',
            data: Object.values(opd_service_data),
            backgroundColor: 'green',
            borderColor: 'green',
            borderWidth: 1
        }]
    };

    const serviceCountChart = new Chart(ctx, {
        type: 'bar',
        data: initialData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    type: 'logarithmic',
                    ticks: {
                        callback: function(value, index, values) {
                            if (value === 10 || value === 100 || value === 1000 || value === 10000) {
                                return value.toString();
                            }
                        }
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        fontSize: 8,
                        minRotation: 45,
                        maxRotation: 40
                    }
                }
            }
        }
    });
</script>
