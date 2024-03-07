{{-- View for Creating bar Chart for representing number of service providers per target group --}}
<div class="container card pt-5 mb-5" id="chart-description">
    <h5 class="text-center">Number of Service Providers Per Target group</h5>

    <div class="chart-container">
        <canvas id="targetGroupChart"></canvas>
    </div>
</div>

{{-- Script for generating a dynamic barchart for target group --}}
<script>
    var ctx = document.getElementById('targetGroupChart').getContext('2d');
    var targetGroupChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($targetGroup) !!},
            datasets: [{
                label: 'Number of Service Providers per Target Group',
                data: {!! json_encode($targetGroupData->pluck('count')) !!},
                backgroundColor: 'green',
                borderColor: 'green',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { //label for y-axis
                    title: {
                        display: true,
                        text: 'Count',
                        font: {
                            size: 15,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }
            }
        }
    });
</script>
