
<style>
  /* Only target this card */
  #card-element {
    border: 2px solid #C9E4C5;       /* Pale mint green */
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 2rem;
    background: #FAFCFA;            /* Very light mint */
  }

  #card-element .card-body {
    padding: 1.5rem;
  }

  /* Soft header bar */
  #card-element h5 {
    background: #E6F4EA;             /* Light mint */
    color: #2F5533;                  /* Darker forest green */
    margin: -1.5rem -1.5rem 1rem;
    padding: 0.75rem 1.25rem;
    border-bottom: 2px solid #B8DCC8;
    font-weight: 600;
  }

  /* Gentle form‐select styling, scoped */
  #card-element .form-select {
    max-width: 200px;
    margin-right: 1rem;
    display: inline-block;
    background: #FCFEFC;
    border: 1px solid #D1E8D3;
    color: #2F5533;
  }

  /* Pastel box around chart */
  #card-element .chart-container {
    border: 1px solid #C9E4C5;
    border-radius: 4px;
    background: #F4FCF7;
    padding: 1rem;
    height: 300px;
    margin-top: 1rem;
  }

  /* Make only this canvas fill its container */
  #card-element canvas {
    width: 100% !important;
    height: 100% !important;
  }
</style>




<div class="card text-center" id="card-element">
    <div class="card-body" id="body-element">
        <h5 class="card-text text-center">Count Of Persons With Disabilities by Disability Category</h5>
        <label for="districtSelect">
            <select name="districtSelector" id="districtSelector" onchange="UpdateCategory()" class="form-select">
                <option value="all">All Districts</option>
                @foreach ($districtDisabilityCounts as $districtName => $counts)
                    <option value="{{ $districtName }}">{{ $districtName }}</option>
                @endforeach
            </select>
        </label>
        <label for="disabilityCountFilter">
            <select name="disabilityCountFilter" id="disabilityCountFilter" onchange="UpdateCategory()" class="form-select">
                <option value="all">All Disabilities</option>
                 <option value="5" selected>Top 5 Disabilities</option>
                <option value="2">Top 2 Disabilities</option> 
            </select>
        </label>
        <div class="chart-container">
            <canvas id="disabilityCountChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const disabilityData = @json($disabilityCounts);
    var ctx = document.getElementById('disabilityCountChart').getContext('2d');
    var initialData = {
        labels: Object.keys(disabilityData),
        datasets: [{
            label: 'Number of Persons by Disability Category',
            data: Object.values(disabilityData), //Retrieving values from json object
            backgroundColor: 'green', // background color
            borderColor: 'green',
            borderWidth: 1
        }]
    };
    const disabilityChart = new Chart(ctx, {
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
        },
    });

    function UpdateCategory() {
    var selectedDistrictCategory = document.getElementById('districtSelector').value;
    var disabilityFilter = document.getElementById('disabilityCountFilter').value;
    
    let filteredData;
    if (selectedDistrictCategory === 'all') {
        filteredData = disabilityData;
    } else {
        const districtData = @json($districtDisabilityCounts);
        filteredData = districtData[selectedDistrictCategory];
    }

    // Apply filter for top 5 or top 2 if selected
    let labels = Object.keys(filteredData);
    let data = Object.values(filteredData);
    if (disabilityFilter === '5') {
        labels = labels.slice(0, 5);
        data = data.slice(0, 5);
    } else if (disabilityFilter === '2') {
        labels = labels.slice(0, 2);
        data = data.slice(0, 2);
    }

    // Update the chart
    disabilityChart.data.labels = labels;
    disabilityChart.data.datasets[0].data = data;
    disabilityChart.update();
}

 UpdateCategory();
</script>
