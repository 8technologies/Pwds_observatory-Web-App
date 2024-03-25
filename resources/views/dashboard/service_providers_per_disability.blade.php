<div class="container card pt-5 mb-5" id="chart-description">
    <h5 class="text-center">District Unions Vs Organisation for Person with Disability By Region</h5>
    <div class="row" id="chart-content">
        <div class="col-md-12">
            <label for="selectDistrict">Select District: </label>
            <select id="districtService">
                <option value="all">All Districts</option>
                @foreach ($availableDistricts as $district)
                    <option value="{{ $district }}">{{ $district }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="serviceProviderChart"></canvas>
    </div>
</div>



{{-- <div class="container card pt-5 mb-5" id="chart-description">
    <h5 class="text-center">Number of Service Providers By Disability Category</h5>
    <div class="row" id="chart-content">
        <div class="col-md-12">
            <label for="selectDistrict">Select District: </label>
            <select id="districtService">
                <option value="all">All Districts</option>
                @foreach ($availableDistricts as $district)
                    <option value="{{ $district }}">{{ $district }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="chart-container">
        <canvas id="serviceProviderChart"></canvas>
    </div>
</div>
 --}}

<script>
    (function() {
        var serviceProviderCounts = @json($serviceProviderCounts);
        var currentDistrict = 'all';

        const districtService = document.getElementById('districtService');
        districtService.addEventListener('change', function() {
            currentDistrict = this.value;
            updateServiceChart(currentDistrict);
        });

        var ctx = document.getElementById('serviceProviderChart').getContext('2d');
        var serviceProvidersChart;

        function updateServiceChart(district) {
            var labels = @json($disabilityNames);
            var serviceData;

            if (district === 'all') {
                // Aggregate counts for all districts
                serviceData = labels.map(label => {
                    let total = 0;
                    for (var district in serviceProviderCounts) {
                        total += serviceProviderCounts[district][label] || 0;
                    }
                    return total;
                });
            } else {
                // Use counts for the selected district
                serviceData = labels.map(label => serviceProviderCounts[district][label] || 0);
            }

            if (serviceProvidersChart) {
                serviceProvidersChart.destroy(); // Destroy the old chart instance before creating a new one
            }

            serviceProvidersChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number of Service Providers',
                        data: serviceData,
                        backgroundColor: 'green',
                        borderColor: 'green',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'logarithmic',
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    if (value === 5 || value === 50 || value === 100 || value === 200) {
                                        return value.toString();
                                    }
                                }
                            }
                        },
                        x: {
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 40
                            }
                        }
                    }
                }
            });
        }

        updateServiceChart(currentDistrict); // Initialize the chart
    })();
</script>
