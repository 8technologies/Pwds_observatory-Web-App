{{-- This is the view file for the chart. It uses the Chart.js library to display the chart for DUs and OPDs.  --}}
<div class="card" id="card-element">
    <div class="card-body" id="body-element">
        <h5 class="card-text text-center">District Unions Vs NOPDs Per Membership</h5>
        <label for="membershipChart">
            <select name="membershipChart" id="membershipChart" class="form-select">
                <option value="mebership_all">Show All</option>
                <option value="membership_du">District Unions</option>
                <option value="membership_nopd">NOPDs</option>
            </select>
        </label>
        <div class="chart-container">
            <canvas id="memberChart"></canvas>
        </div>
    </div>
</div>

<!-- Include Chart.js library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


{{-- This is the script to create the chart. It uses the Chart.js library to create the chart.  --}}
<script>
    // Get the canvas element from the DOM
    var ctx = document.getElementById('memberChart').getContext('2d');
    var initialMemberData = {
        labels: {!! json_encode($formattedMembershipTypes) !!},
        datasets: [{
            label: 'District Unions',
            data: {!! json_encode($membershipDataDU->pluck('count')) !!},
            backgroundColor: 'green',
            borderColor: 'green',
            borderWidth: 1
        }, {
            label: 'NOPDs',
            data: {!! json_encode($membershipDataOPD->pluck('count')) !!},
            backgroundColor: '#66c2ff',
            borderColor: '#66c2ff',
            borderWidth: 1
        }]
    };

    // Creating the chart
    var memberChart = new Chart(ctx, {
        type: 'bar',
        data: initialMemberData,
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

    //Function for updating charts on change
    function updateMembershipChart(selectedType) {
        var newData;
        if (selectedType === 'membership_nopd') {
            newData = {
                labels: {!! json_encode($formattedMembershipTypes) !!},
                datasets: [{
                    label: 'Number of NOPDs per Membership Type',
                    data: {!! json_encode($membershipDataOPD->pluck('count')) !!},
                    backgroundColor: 'green',
                    borderColor: 'green',
                    borderWidth: 1
                }]
            };
        } else if (selectedType === 'membership_du') {
            newData = {
                labels: {!! json_encode($formattedMembershipTypes) !!},
                datasets: [{
                    label: 'Number of District Unions per Membership Type',
                    data: {!! json_encode($membershipDataDU->pluck('count')) !!},
                    backgroundColor: '#66c2ff',
                    borderColor: '#66c2ff',
                    borderWidth: 1
                }]
            };
        } else {
            newData = initialMemberData;
        }

        memberChart.data = newData;
        memberChart.update();
    }

    document.getElementById('membershipChart').addEventListener('change', function() {
        var selectedType = this.value;
        updateMembershipChart(selectedType);
    });
</script>
