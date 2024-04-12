<div class="container card pt-5 mb-5" id="chart-description">
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">Person with Disability by Age-group and Gender in {{ $districtName }}</h5>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="districtAgeGroup"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var districtGenderCount = @json($district_disability_counts);
    // Sorting function tailored for your specific age group format
    var district_ageGroups = Object.keys(districtGenderCount)
        .filter(label => label !== null)
        .sort((a, b) => {
            // Handle the '65+' age group separately
            if (a === '65+') return 1;
            if (b === '65+') return -1;
            var startAgeA = parseInt(a.split(' - ')[0], 10);
            var startAgeB = parseInt(b.split(' - ')[0], 10);
            return startAgeB - startAgeA;
        });
    var maleData = district_ageGroups.map(ageGroup => districtGenderCount[ageGroup]['Male'] || 0);
    var femaleData = district_ageGroups.map(ageGroup => (districtGenderCount[ageGroup]['Female'] || 0)).map(dataPoint =>
        dataPoint * -1);

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
            labels: district_ageGroups,
            datasets: datasets
        },
        options: {
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    stacked: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return Math.abs(value);
                        }
                    },
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

    var ctx = document.getElementById('districtAgeGroup').getContext('2d');
    new Chart(ctx, config);
</script>
