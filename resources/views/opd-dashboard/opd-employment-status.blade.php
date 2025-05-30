<div class="card text-center">>
    <div class="card-body">
        <h5 class="card-text text-center">Employment Status by Gender</h5>
        <label for="EmploymentStatus">
            <select id="opdEmploymentSelector" class="form-select">
                <option value="Formal Employment">Formal Employment</option>
                <option value="Self Employment">Self Employment</option>
                {{-- To be worked on --}}
                {{-- <option value="Unemployed">Unemployed</option> --}}
            </select>
            <div class="chart-container">
                <canvas id="opdEmploymentStatusChart"></canvas>
            </div>
        </label>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var opdEmploymentData = @json($opdEmploymentData); // Assume this is your data

        var ctx = document.getElementById('opdEmploymentStatusChart').getContext('2d');
        var opdEmploymentStatusChart = new Chart(ctx, {
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
            var filteredData = opdEmploymentData.filter(function(item) {
                return item.employment_status === selectedStatus && item.sex !== null && item.sex !==
                    'N/A';
            });

            var countsByGender = filteredData.reduce(function(acc, item) {
                acc[item.sex] = (acc[item.sex] || 0) + item.count;
                return acc;
            }, {});

            opdEmploymentStatusChart.data = {
                labels: Object.keys(countsByGender),
                datasets: [{
                    label: `${selectedStatus} by Gender`,
                    data: Object.values(countsByGender),
                    backgroundColor: ['green', '#66c2ff'],
                    borderColor: 'rgba(75, 192, 192, 2)',
                    borderWidth: 1
                }]
            };

            opdEmploymentStatusChart.update();
        }

        // Initial chart update
        updateChart(document.getElementById('opdEmploymentSelector').value);

        // Event listener for the selector
        document.getElementById('opdEmploymentSelector').addEventListener('change', function() {
            updateChart(this.value);
        });
    });
</script>
