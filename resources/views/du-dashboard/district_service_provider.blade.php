<div class="card text-center">
    <div class="card-body">
        <h5 class="card-text text-center">Service Providers Per Disability Category in {{ $districtName }}</h5>
        <div class="chart-container">
            <canvas id="district_serviceProviderChart"></canvas>
        </div>
    </div>
</div>

<script>
    const district_service_data = @json($serviceCounts);

    var ctx = document.getElementById('district_serviceProviderChart').getContext('2d');
    var initialData = {
        labels: Object.keys(district_service_data),
        datasets: [{
            label: 'Service Providers by Disability Category',
            data: Object.values(district_service_data),
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
