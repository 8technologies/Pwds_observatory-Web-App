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
        <h5 class=" card-text text-center">Percentage Of Persons with Disabilities By Gender</h5>
        <div class="chart-container ">
            <canvas id="districtGenderCount"></canvas>
        </div>

    </div>
</div>






<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    var genderCountData = @json($gender_count);

    var ctx = document.getElementById('districtGenderCount').getContext('2d');

    // Ensure the datalabels plugin is registered
    Chart.register(ChartDataLabels);

    var genderCountChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: genderCountData.map(data => data.sex),
            datasets: [{
                label: 'Gender Count',
                data: genderCountData.map(data => data.count),
                backgroundColor: ['green', '#66c2ff'],
                borderColor: 'rgba(75, 192, 192, 2)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: true,
                },
                // Consolidate the datalabels configuration here
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
