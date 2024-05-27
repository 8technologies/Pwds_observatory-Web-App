<div class="container card pt-5 mb-5" id='chart-description'>
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">PWDs By Disability Category in {{ $opdName }}</h5>
        </div>

    </div>
    <div class="chart-container">
        <canvas id="opdDisabilityCategoryChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    const opdDisabilityCategory = @json($opdDisabilityCount);
    var ctx = document.getElementById('opdDisabilityCategoryChart').getContext('2d');
    var initialData = {
        labels: Object.keys(opdDisabilityCategory),
        datasets: [{
            label: 'Disability Category',
            data: Object.values(opdDisabilityCategory), //Retrieving values from json object
            backgroundColor: 'green', // background color
            borderColor: 'green',
            borderWidth: 1
        }]
    };
    const disabilityChart = new Chart(ctx, {
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
            },
            plugins: {
                datalabels: {
                    display: true,
                    color: 'black',
                    align: 'center',
                    anchor: 'center',
                    formatter: (value, ctx) => {
                        return value.toString();
                    }
                }
            },
            plugins: [ChartDataLabels]
        }
    });
</script>
