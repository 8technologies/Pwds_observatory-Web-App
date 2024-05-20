{{-- View for Creating bar Chart for representing number of people with Disability per district --}}
<div class="container card pt-5 mb-5" id="chart-description">
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">Percentage of PWDs per Gender</h5>
        </div>
        <div class="col-md-12" id="item-select">
            <label for="districtSelect">
                <select id="districtSelect" onchange="updateDistrict()" class="form-select">
                    <option value="">All Districts</option>
                    @foreach ($barChart->pluck('district')->unique() as $district)
                        <option value="{{ $district }}">{{ $district }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>

    <div class="chart-container p-2 mb-2">
        <canvas id="pieChart"></canvas>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    var ctx = document.getElementById('pieChart').getContext('2d');
    var originalData = {
        labels: {!! json_encode($sex) !!}.filter(function(label) {
            return label !== 'N/A';
        }),
        datasets: [{
            label: 'PwDs By Gender',
            data: [{!! $barChart->where('sex', 'Male')->sum('count') !!},
                {!! $barChart->where('sex', 'Female')->sum('count') !!}
            ],
            backgroundColor: ['green', '#66c2ff'],
            borderColor: 'rgba(75, 192, 192, 2)',
            borderWidth: 1
        }]
    };
    var districtChart = new Chart(ctx, {
        type: 'pie',
        data: originalData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: true, // Adjust based on your requirement
                },
                datalabels: {
                    color: '#fff',
                    formatter: (value, ctx) => {
                        let sum = ctx.chart._metasets[ctx.datasetIndex].total;
                        let percentage = (value * 100 / sum).toFixed(2) + "%";
                        return percentage;
                    }
                }
            }
        },
        plugins: [ChartDataLabels],
    });


    function updateDistrict() {
        var selectedDistrict = document.getElementById('districtSelect').value;

        // If All Districts selected, show the original data
        if (selectedDistrict === "") {
            districtChart.data = originalData;
        } else {
            // Filter data for the selected district
            var filteredData = {!! json_encode($barChart) !!}.filter(function(item) {
                return item.district === selectedDistrict;
            });

            // Update chart with filtered data
            districtChart.data = {
                labels: {!! json_encode($sex) !!}.filter(function(label) {
                    return label !== 'N/A';
                }),
                datasets: [{
                    data: filteredData.map(item => item.count),
                    backgroundColor: ['green', '#66c2ff'],
                    borderColor: 'rgba(75, 192, 192, 2)',
                    borderWidth: 1
                }]
            };
        }

        // districtChart.options.aspectRatio = 0.5;
        // Redraw the chart
        districtChart.update();
    }
</script>
