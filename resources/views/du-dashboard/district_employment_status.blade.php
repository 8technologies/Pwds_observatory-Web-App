<div class="container card pt-5 mb-5" id="chart-description">
    <div class="row" id="chart-content">
        <div class="col-12" id="heading">
            <h5 class="text-center">Employment Status By Gender</h5>
        </div>
        <div class="col-12" id="item-select">
            <label for="EmploymentStatus">
                <select id="employmentDistrictEmployment" class="form-select">
                    <option value="Formal Employment">Formal Employment</option>
                    <option value="Self Employment">Self Employment</option>
                    {{-- To be worked on --}}
                    {{-- <option value="Unemployed">Unemployed</option> --}}
                </select>
            </label>
        </div>
    </div>
    <div class="chart-container p-2 mb-2">
        <canvas id="districtEmploymentStatusChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    var districtEmploymentData = @json($employmentData); // Assume this is your data

    // Format districtEmploymentData
    var employmentStatusData = districtEmploymentData.map(function(item) {
        return {
            employment_status: item.employment_status,
            sex: item.sex,
            count: item.count
        };
    });

    var ctx = document.getElementById('districtEmploymentStatusChart').getContext('2d');
    var districtEmploymentStatusChart = new Chart(ctx, {
        type: 'pie',
        data: null, // Data will be set by updateDistrictEmployment function
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
        plugins: [ChartDataLabels],
    });

    // Function to update chart based on selected employment status
    function updateDistrictEmployment(selectedStatus) {
        var filteredData = employmentStatusData.filter(function(item) {
            return item.employment_status === selectedStatus && item.sex !== null && item.sex !== 'N/A';
        });

        var countsByGender = filteredData.reduce(function(acc, item) {
            acc[item.sex] = (acc[item.sex] || 0) + item.count;
            return acc;
        }, {});

        districtEmploymentStatusChart.data = {
            labels: Object.keys(countsByGender),
            datasets: [{
                label: `${selectedStatus} by Gender`,
                data: Object.values(countsByGender),
                backgroundColor: ['green', '#66c2ff'],
                borderColor: 'rgba(75, 192, 192, 2)',
                borderWidth: 1
            }]
        };

        districtEmploymentStatusChart.update();
    }

    // Initial chart update
    updateDistrictEmployment(document.getElementById('employmentDistrictEmployment').value);

    // Event listener for the selector
    document.getElementById('employmentDistrictEmployment').addEventListener('change', function() {
        updateDistrictEmployment(this.value);
    });
</script>
