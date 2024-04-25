<div class="container card pt-5 mb-5" id="chart-description">
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">Education Type by Gender</h5>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="educationGenderChart"></canvas>
    </div>
</div>

<script>
    const educationData = @json($educationData);
    var gender = @json($gender).filter(label => label !== null && label !== 'N/A');
    const educationLevels = @json($educationLevels).filter(level => level !== 'Unknown');

    const data = {
        labels: educationLevels,
        datasets: gender.map(g => ({
            label: g,
            backgroundColor: g === 'Male' ? 'skyblue' : 'green',
            data: educationLevels.map(level => {
                return educationData[level][g] || 0;
            })
        }))
    };

    const configs = {
        type: 'bar',
        data: data,
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 40
                    }
                }
            },
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    };

    new Chart(document.getElementById('educationGenderChart'), configs);
</script>
