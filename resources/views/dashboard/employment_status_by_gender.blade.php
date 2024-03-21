<div class="container card pt-5 mb-5" id="chart-description">
    <h5 class="text-center">Employment Status by Gender</h5>
    <div class="row" id="chart-content">
        <div class="col-md-6">
            <label for="EmploymentStatus">Select Employment Status:</label>
            <select id="employmentStatusSelector">
                <option value="Formal Employment">Formal Employment</option>
                <option value="Self Employment">Self Employment</option>
                <option value="unemployed">Unemployed</option>
            </select>
        </div>
    </div>
    <div class="chart-container">
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
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Employment Status by Gender'
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
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
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
