<div class="card text-center">
    <div class="card-body">
        <h5 class="text-center">Persons With Disabilities Education Status By Gender in {{ $opdName }}</h5>
        <div class="chart-container">
            <canvas id="opdEducationGender"></canvas>
        </div>
    </div>
</div>

<script>
    var opdEducationData = @json($opdEducationData);
    var gender = @json($genders).filter(label => label !== null && label !== 'N/A');
    var opdEducationLevels = @json($opd_education_levels).filter(educ_levels => educ_levels != 'Unknown');

    var data = {
        labels: opdEducationLevels,
        datasets: gender.map(g => ({
            label: g,
            backgroundColor: g === 'Male' ? 'skyblue' : 'green',
            data: opdEducationLevels.map(level => {
                const found = opdEducationData.find(d => d.education_level === level && d
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

    new Chart(document.getElementById("opdEducationGender"), configs);
</script>
