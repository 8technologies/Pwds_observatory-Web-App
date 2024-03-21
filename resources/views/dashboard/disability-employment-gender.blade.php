<div class="container card pt-5 mb-5" id="chart-description">
    <h5 class="text-center">Education Type by Gender</h5>
    <div class="chart-container">
        <canvas id="educationGenderChart"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const educationData = @json($educationData);
        const gender = @json($gender).filter(label => label !== null && label !==
            'N/A'); // Assuming this is an array like ['Male', 'Female']
        const educationLevels =
            @json($educationLevels).filter(educ_levels => educ_levels !=
                'Unknown'); // This should be an array of education level names

        const data = {
            labels: educationLevels,
            datasets: gender.map(g => ({
                label: g,
                backgroundColor: g === 'Male' ? 'skyblue' :
                'green', // Choose colors as per your preference
                data: educationLevels.map(level => {
                    const found = educationData.find(d => d.education_level === level &&
                        d.sex === g);
                    return found ? found.count : 0;
                })
            }))
        };

        var config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    xAxes: [{
                        //rotation to 90 degrees
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 40
                        }
                    }]
                },
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        };

        new Chart(document.getElementById('educationGenderChart'), config);
    });
</script>
