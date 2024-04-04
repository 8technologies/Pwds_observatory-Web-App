<div class="container card pt-5 mb-5" id="chart-description">
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">Employment Status by Gender</h5>
        </div>
        <div class="col-12" id="item-select">
            <label for="EmploymentStatus">Employment Status:</label>
            <select id="employmentStatusSelector">
                <option value="Formal Employment">Formal Employment</option>
                <option value="Self Employment">Self Employment</option>
                <option value="unemployed">Unemployed</option>
            </select>
        </div>
    </div>
    <div class="chart-container p-2 mb-2">
        <canvas id="employmentStatusChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var employmentStatusData = @json($employmentStatusData); // Assume this is your data

        var ctx = document.getElementById('employmentStatusChart').getContext('2d');
        var employmentStatusChart = new Chart(ctx, {
            type: 'pie',
            data: null, // Data will be set by updateChart function
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 3,
                plugins: {
                    legend: {
                        display: true,
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
        });

        // Function to update chart based on selected employment status
        function updateChart(selectedStatus) {
            var filteredData = employmentStatusData.filter(function(item) {
                return item.employment_status === selectedStatus;
            });

            var countsByGender = filteredData.reduce(function(acc, item) {
                acc[item.sex] = (acc[item.sex] || 0) + item.count;
                return acc;
            }, {});

            employmentStatusChart.data = {
                labels: Object.keys(countsByGender),
                datasets: [{
                    label: `${selectedStatus} by Gender`,
                    data: Object.values(countsByGender),
                    backgroundColor: ['green', '#66c2ff'],
                    borderColor: 'rgba(75, 192, 192, 2)',
                    borderWidth: 1
                }]
            };

            employmentStatusChart.update();
        }

        // Initial chart update
        updateChart(document.getElementById('employmentStatusSelector').value);

        // Event listener for the selector
        document.getElementById('employmentStatusSelector').addEventListener('change', function() {
            updateChart(this.value);
        });
    });
</script>
