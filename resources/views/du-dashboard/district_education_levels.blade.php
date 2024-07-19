<div class="card text-center">
    <div class="card-body">
        <h5 class="text-center">Persons With Disabilities Education by Gender in {{ $districtName }}</h5>
        <div class="chart-container">
            <canvas id="districtEducationGender"></canvas>
        </div>
    </div>

</div>

<script>
    var districtEducationData = @json($districtEducationData);
    var gender = @json($genders).filter(label => label !== null && label !== 'N/A');
    var districtEducationLevels = @json($district_educationLevels).filter(educ_levels => educ_levels != 'Unknown');

    var data = {
        labels: districtEducationLevels,
        datasets: gender.map(g => ({
            label: g,
            backgroundColor: g === 'Male' ? 'skyblue' : 'green',
            data: districtEducationLevels.map(level => {
                const found = districtEducationData.find(d => d.education_level === level && d
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

    new Chart(document.getElementById("districtEducationGender"), configs);
</script>
