<style>
    .chart-container {
        position: relative;
        margin: auto;
        width: 100%;
        height: 50vh;
        padding-bottom: 5px;
        /* 70% of the viewport height */
    }

    #chart-content {
        height: 10vh;
        /* 10% of the viewport height */
        width: 100%;
        line-height: 4px;
    }

    #chart-description {
        height: 52vh;
        /* Adjust the height as needed */
        width: 100%;

    }

    #heading {
        padding: 2px;
        ;
    }

    #heading .text-center {
        font-weight: 500;
        font-family: "Roboto Mono", sans-serif;
        font-style: normal;
        font-size: 16px;
    }


    #item-select {
        height: 10vh;
        /* Adjust the height as needed */
        width: 100%;
    }

    #item-select label {
        font-weight: light;
        font-family: "Roboto", sans-serif;
        font-style: normal;
        font-size: 15px;
        padding: auto;
        margin: auto;

    }

    @media only screen and (min-width: 768px) {
        .chart-container {
            height: 300px;
            /* Shorter on small devices */
        }

    }

    .card {
        background-color: #fff;
        margin: 10px;
    }
</style>

<div class="container card pt-5 mb-5 bg-white" id="chart-description">
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">Percentage Of PWDs By Gender in {{ $opdName }}</h5>
        </div>
    </div>
    <div class="chart-container p-2 mb-2">
        <canvas id="opdGenderCount"></canvas>
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
