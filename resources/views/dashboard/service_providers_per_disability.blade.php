<div class="card text-center" id="card-element">
    <div class="card-body" id="body-element">
        <h5 class="card-text text-center">Count Of Service Providers Per Disability Category</h5>
        <label for="selectDistrict">
            <select name="districtService" id="districtService" onchange="UpdateDistrictService()" class="form-select">
                <option value="all">All Districts</option>
                @foreach ($districtServiceCounts as $district_name => $counts)
                    <option value="{{ $district_name }}">{{ $district_name }}</option>
                @endforeach
            </select>
        </label>
        <div class="chart-container">
            <canvas id="serviceProviderChart"></canvas>
        </div>
    </div>
</div>

<script>
    const serviceDisabilityData = @json($serviceCounts);
    const districtServiceData = @json($districtServiceCounts);

    var ctx = document.getElementById('serviceProviderChart').getContext('2d');
    var initialData = {
        labels: Object.keys(serviceDisabilityData),
        datasets: [{
            label: 'Service Providers by Disability Category',
            data: Object.values(serviceDisabilityData),
            backgroundColor: 'green',
            borderColor: 'green',
            borderWidth: 1
        }]
    };

    const serviceCountChart = new Chart(ctx, {
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
        }
    });

    function UpdateDistrictService() {
        var selectedDistrict = document.getElementById('districtService').value;

        // Debugging: Log the selected district to ensure it's being captured correctly
        console.log("Selected District:", selectedDistrict);

        var updatedLabels, updatedData;

        if (selectedDistrict === 'all') {
            updatedLabels = Object.keys(serviceDisabilityData);
            updatedData = Object.values(serviceDisabilityData);
        } else {
            const districtDisabData = districtServiceData[selectedDistrict];
            if (districtDisabData) {
                updatedLabels = Object.keys(districtDisabData);
                updatedData = Object.values(districtDisabData);
            } else {
                // Fallback or error handling if data for the selected district is not found
                console.error("No data found for selected district:", selectedDistrict);
                updatedLabels = [];
                updatedData = [];
            }
        }

        // Debugging: Log the updated labels and data
        console.log("Updated Labels:", updatedLabels, "Updated Data:", updatedData);

        serviceCountChart.data.labels = updatedLabels;
        serviceCountChart.data.datasets[0].data = updatedData;

        serviceCountChart.update();
    }
</script>
