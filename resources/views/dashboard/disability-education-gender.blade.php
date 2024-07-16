<div class="card text-center" id="card-element">
    <div class="card-body" id="body-element">
        <h5 class="card-text text-center">PWDs Education Status By Gender</h5>
        <div class="chart-container">
            <canvas id="educationGenderData"></canvas>
        </div>
    </div>
</div>

<script>
    var educationData = @json($educationData);
    var gender = @json($gender).filter(label => label !== null && label !== 'N/A');
    var educationLevels = @json($educationLevels).filter(educ_levels => educ_levels != 'Unknown');

    var data = {
        labels: educationLevels,
        datasets: gender.map(g => ({
            label: g,
            backgroundColor: g === 'Male' ? 'skyblue' : 'green',
            data: educationLevels.map(level => {
                const found = educationData.find(d => d.education_level === level && d
                    .sex ===
                    g);
                return found ? found.count : 0;
            })
        }))
    };

    var configs = {
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

    new Chart(document.getElementById("educationGenderData"), configs);
</script>
