{{-- This is the view file for the chart. It uses the Chart.js library to display the chart for DUs and OPDs per region.  --}}
<div class="container card pt-5 mb-5" id="chart-description">
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">District Unions Vs NOPDs Per Region</h5>
        </div>
        <div class="col-12" id="item-select">
            <label for="organisationType">
                <select name="organisationType" id="organisationType" class="form-select">
                    <option value="all">Show All</option>
                    <option value="du">District Unions</option>
                    <option value="opd">NOPDs</option>
                </select>
            </label>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="regionChart"></canvas>
    </div>
</div>

<!-- Include Chart.js library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('regionChart').getContext('2d');
    var initialOrganisationData = {
        labels: {!! json_encode($regions) !!},
        datasets: [{
            label: 'District Unions',
            data: {!! json_encode($chartDataDU->pluck('count')) !!},
            backgroundColor: 'green',
            borderColor: 'green',
            borderWidth: 1
        }, {
            label: 'NOPDs',
            data: {!! json_encode($chartDataOPD->pluck('count')) !!},
            backgroundColor: '#66c2ff',
            borderColor: '#66c2ff',
            borderWidth: 1
        }]
    };

    var regionChart = new Chart(ctx, {
        type: 'bar',
        data: initialOrganisationData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                }
            }
        }
    });

    function updateOrganisationChart(selectedOrgan) {
        var newData;
        if (selectedOrgan === 'opd') {
            newData = {
                labels: {!! json_encode($regions) !!},
                datasets: [{
                    label: 'NOPDs per Region',
                    data: {!! json_encode($chartDataOPD->pluck('count')) !!},
                    backgroundColor: '#66c2ff',
                    borderColor: '#66c2ff',
                    borderWidth: 1
                }]
            };
        } else if (selectedOrgan === 'du') {
            newData = {
                labels: {!! json_encode($regions) !!},
                datasets: [{
                    label: 'District Unions per Region',
                    data: {!! json_encode($chartDataDU->pluck('count')) !!},
                    backgroundColor: 'green',
                    borderColor: 'green',
                    borderWidth: 1
                }]
            };
        } else {
            newData = initialOrganisationData;
        }

        regionChart.data = newData;
        regionChart.update();
    }

    document.getElementById('organisationType').addEventListener('change', function() {
        var selectedOrgan = this.value;
        updateOrganisationChart(selectedOrgan);
    });

    //Customize the chart size
</script>
