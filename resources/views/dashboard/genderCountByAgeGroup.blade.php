<div class="card text-center" id="card-element">
    <div class="card-body" id="body-element">
        <h5 class="card-text text-center">Number Of Persons With Disabilities By Age-group and Gender</h5>
        <div class="chart-container">
            <canvas id="pyramidChart"></canvas>
        </div>
    </div>
</div>


<script>
    const genderCountData = @json($disabilityCounts);
    // Sorting function tailored for your specific age group format
    const ageGroups = Object.keys(genderCountData)
        .filter(label => label !== null)
        .sort((a, b) => {
            // Place '65+' at the top of the list
            if (a === '65+') return -1;
            if (b === '65+') return 1;
            // Compare start ages of other age groups descending
            const startAgeA = parseInt(a.split(' - ')[0], 10);
            const startAgeB = parseInt(b.split(' - ')[0], 10);
            return startAgeB - startAgeA; // Keep as descending order
        });

    const maleData = ageGroups.map(ageGroup => genderCountData[ageGroup]['Male'] || 0);
    const femaleData = ageGroups.map(ageGroup => (genderCountData[ageGroup]['Female'] || 0)).map(dataPoint =>
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
            labels: ageGroups,
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

    var ctx = document.getElementById('pyramidChart').getContext('2d');
    new Chart(ctx, config);
</script>
