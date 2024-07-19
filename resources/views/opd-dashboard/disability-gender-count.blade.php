<style>
    .card {
        background: #ffffff;
        margin: 10px;
        padding: 10px;
        height: 400px;
        width: 100%;
    }

    .card-body {
        margin: 10px;
        padding: 10px;

    }

    .chart-container {
        height: 300px;
        width: 100%;
    }
</style>

<div class="card text-center">
    <div class="card-body">
        <h5 class="card-text text-center">Percentage Of Persons With Disabilities By Gender in {{ $opdName }}</h5>
        <div class="chart-container p-2 mb-2">
            <canvas id="opdGenderCount"></canvas>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    var opdCountData = @json($genderCount);

    var ctx = document.getElementById('opdGenderCount').getContext('2d');

    //Registering data labels
    Chart.register(ChartDataLabels);

    var genderCountChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: opdCountData.map(data => data.sex),
            datasets: [{
                label: 'Gender Count',
                data: opdCountData.map(data => data.count),
                backgroundColor: ['#66c2ff', 'green'],
                borderColor: 'rgba(75, 192, 192, 2)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRation: 2,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        boxWidth: 20,
                        padding: 10,
                        font: {
                            size: 12,
                        }
                    }

                },
                // Data labels configuration
                datalabels: {
                    color: '#fff',
                    formatter: (value, ctx) => {
                        let sum = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        let percentage = ((value * 100) / sum).toFixed(2) + "%";
                        return percentage;
                    }
                }
            },
        }
    });
</script>
