<div class="container card pt-5 mb-5" id="chart-description">
    <h5 class="text-center">Service Providers per Disability Category</h5>
    <div class="row" id="chart-content">
        <div class="col-md-12">
            <label for="selectDistrict">Select District: </label>
            <select name="districtService" id="districtService" class="form-control" onchange="UpdateDistrictService()">
                <option value="all">All Districts</option>
                @foreach ($districtServiceCounts as $district_name => $counts)
                    <option value="{{ $district_name }}">{{ $district_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="serviceProviderChart"></canvas>
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

        if (selectedDistrict === 'all') {
            serviceCountChart.data.labels = Object.keys(serviceDisabilityData);
            serviceCountChart.data.datasets[0].data = Object.values(serviceDisabilityData);
        } else {
            const districtData = districtServiceData[selectedDistrict] || {};
            serviceCountChart.data.labels = Object.keys(districtData);
            serviceCountChart.data.datasets[0].data = Object.values(districtData);
        }
        serviceCountChart.update();
    }
</script>
