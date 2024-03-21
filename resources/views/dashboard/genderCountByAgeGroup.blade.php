<div class="container card pt-5 mb-5" id="chart-description">
    <h5 class="text-center">Number of Person with Disability by Age-group and Gender</h5>
    <div class="chart-container">
        <canvas id="pyramidChart"></canvas>
    </div>
</div>


<script>
    const genderCountData = @json($disabilityCounts);
    const ageGroups = Object.keys(genderCountData).filter(label => label !== null);
    const maleData = ageGroups.map(ageGroup => genderCountData[ageGroup]['Male'] || 0);
    const female = ageGroups.map(ageGroup => (genderCountData[ageGroup]['Female'] || 0));

    const femaleData = []
    female.forEach(dataPoint => {
        femaleData.push(dataPoint * -1)
    });


    const datasets = [{
            label: 'Female',
            data: femaleData,
            backgroundColor: 'green',
            borderColor: 'rgba(0, 0, 0, 0.2)',
            borderWidth: 1
        },
        {
            label: 'Male',
            data: maleData,
            backgroundColor: '#66c2ff',
            borderColor: 'rgba(0, 0, 0, 0.2)',
            borderWidth: 1
        },

    ];

    const tooltip = {
        yAlign: 'bottom',
        titleAlign: 'center',
        callbacks: {
            label: function(context) {
                return `${context.dataset.label}: ${Math.abs(context.raw)}`
            }
        }
    }
    const config = {
        type: 'bar',
        data: {
            labels: ageGroups,
            datasets: datasets
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    stacked: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return Math.abs(value);
                        }
                    },
                    title: {
                        display: true,
                        text: 'Number of Persons',
                        color: 'black',
                    }
                },
                y: {
                    stacked: true
                }
            },
            plugins: {
                tooltip,
            }
        }
    };

    var ctx = document.getElementById('pyramidChart').getContext('2d');
    new Chart(ctx, config);
</script>
