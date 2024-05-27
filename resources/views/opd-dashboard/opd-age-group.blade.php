<div class="container card pt-5 mb-5" id="chart-description">
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">PWDs By Age-group and Gender in {{ $opdName }}</h5>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="OpdAgeGroup"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var opdGenderCount = @json($opd_disability_count);
    // Sorting function tailored for your specific age group format
    var opdAgeGroups = Object.keys(opdGenderCount)
        .filter(label => label !== null)
        .sort((a, b) => {
            // Handle the '65+' age group separately
            if (a === '65+') return 1;
            if (b === '65+') return -1;
            var startAgeA = parseInt(a.split(' - ')[0], 10);
            var startAgeB = parseInt(b.split(' - ')[0], 10);
            return startAgeB - startAgeA;
        });
    var maleData = opdAgeGroups.map(ageGroup => opdGenderCount[ageGroup]['Male'] || 0);
    var femaleData = opdAgeGroups.map(ageGroup => (opdGenderCount[ageGroup]['Female'] || 0)).map(dataPoint =>
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
            labels: opdAgeGroups,
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

    var ctx = document.getElementById('OpdAgeGroup').getContext('2d');
    new Chart(ctx, config);
</script>
