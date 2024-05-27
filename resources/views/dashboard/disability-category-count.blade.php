<div class="container card pt-5 mb-5" id='chart-description'>
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">Count Of PWDs by Disability Category</h5>
        </div>
        <div class="col-12" id="item-select">
            <label for="districtSelect">
                <select name="districtSelector" id="districtSelector" onchange="UpdateCategory()" class="form-select">
                    <option value="all">All Districts</option>
                    @foreach ($districtDisabilityCounts as $districtName => $counts)
                        <option value="{{ $districtName }}">{{ $districtName }}</option>
                    @endforeach
                </select>
            </label>

        </div>
    </div>
    <div class="chart-container">
        <canvas id="disabilityCountChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const disabilityData = @json($disabilityCounts);
    var ctx = document.getElementById('disabilityCountChart').getContext('2d');
    var initialData = {
        labels: Object.keys(disabilityData),
        datasets: [{
            label: 'Number of Persons by Disability Category',
            data: Object.values(disabilityData), //Retrieving values from json object
            backgroundColor: 'green', // background color
            borderColor: 'green',
            borderWidth: 1
        }]
    };
    const disabilityChart = new Chart(ctx, {
        type: 'bar',
        data: initialData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    type: 'logarithmic',
                    ticks: {
                        callback: function(value, index, values) {
                            if (value === 10 || value === 100 || value === 1000 || value === 10000) {
                                return value.toString();
                            }
                        }
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        fontSize: 8,
                        minRotation: 45,
                        maxRotation: 40
                    }
                }
            }
        },
    });

    function UpdateCategory() {
        var selectedDistrictCategory = document.getElementById('districtSelector').value;
        if (selectedDistrictCategory === 'all') {
            disabilityChart.data.labels = Object.keys(disabilityData);
            disabilityChart.data.datasets[0].data = Object.values(disabilityData);
        } else {
            const districtData = @json($districtDisabilityCounts);
            const districtDisabilityData = districtData[selectedDistrictCategory];
            disabilityChart.data.labels = Object.keys(districtDisabilityData);
            disabilityChart.data.datasets[0].data = Object.values(districtDisabilityData);
        }
        disabilityChart.update();
    }
</script>
