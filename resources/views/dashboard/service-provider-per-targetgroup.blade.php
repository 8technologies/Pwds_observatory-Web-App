{{-- View for Creating bar Chart for representing number of service providers per target group --}}
<div class="card text-center" id="card-element">
    <div class="card-body" id="body-element">
        <h5 class="card-text text-center">Count Of Service Providers Per Target Group</h5>
        <div class="chart-container">
            <canvas id="targetGroupChart"></canvas>
        </div>
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
            maintainAspectRatio: false,
            scales: {
                y: { //label for y-axis
                    ticks: {
                        beginAtZero: true
                    }
                }
            }
        }
    });
</script>
