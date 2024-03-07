{{-- This is the view file for the chart. It uses the Chart.js library to display the chart for DUs and OPDs.  --}}
<div class="container card pt-5 mb-5" id="chart-description">
    <h5 class="text-center">DU Vs NOPD per Membership Type</h5>
    <div class="row" id="chart-content">
        <div class="col-md-6">
            <label for="membershipChart">Select Chart Type:</label>
            <select name="membershipChart" id="membershipChart">
                <option value="mebership_all">Show All</option>
                <option value="membership_du">District Unions</option>
                <option value="membership_nopd">NOPDs</option>
            </select>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="memberChart"></canvas>
    </div>
</div>

<!-- Include Chart.js library from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


{{-- This is the script to create the chart. It uses the Chart.js library to create the chart.  --}}
<script>
    // Get the canvas element from the DOM
    var ctx = document.getElementById('memberChart').getContext('2d');
    var initialMemberData = {
        labels: {!! json_encode($membershipTypes) !!},
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
            scales: {
                y: { //label for y-axis
                    title: {
                        display: true,
                        text: 'Count',
                        font: {
                            size: 15,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        precision: 0
                    }
                },
                x: { // label for x-axis
                    title: {
                        display: true,
                        text: 'Membership Type',
                        font: {
                            size: 15,
                            weight: 'bold'
                        }
                    }
                }
            },
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
                labels: {!! json_encode($membershipTypes) !!},
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
                labels: {!! json_encode($membershipTypes) !!},
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
