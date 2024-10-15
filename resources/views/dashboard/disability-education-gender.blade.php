<div class="card text-center" id="card-element">
    <div class="card-body" id="body-element">
        <h5 class="card-text text-center">Persons With Disabilities Education Status By Gender</h5>
        <div class="chart-container">
            <canvas id="educationGenderData"></canvas>
        </div>
    </div>
</div>

<script>
    // var educationData = @json($educationData);
    // var gender = @json($gender).filter(label => label !== null && label !== 'N/A');
    // var educationLevels = @json($educationLevels).filter(educ_levels => educ_levels != 'Unknown');


    // var gender = ['Male', 'Female'];
    // var educationLevels = ['Primary', 'Secondary', 'Tertiary'];
    // var educationData = [
    //     { education_level: 'Primary', sex: 'Male', count: 50 },
    //     { education_level: 'Secondary', sex: 'Male', count: 40 },
    //     { education_level: 'Tertiary', sex: 'Male', count: 30 },
    //     { education_level: 'Primary', sex: 'Female', count: 55 },
    //     { education_level: 'Secondary', sex: 'Female', count: 45 },
    //     { education_level: 'Tertiary', sex: 'Female', count: 35 }
    // ];

    // Check if gender is an object and convert it to an array
// Normalize the gender values, filter out unwanted values, and include only 'Male' and 'Female'
var gender = Array.isArray(@json($gender)) ? @json($gender)
    .filter(label => label !== null && ['male', 'female'].includes(label.toLowerCase()))
    : (typeof @json($gender) === 'object' ? Object.values(@json($gender))
    .filter(label => label !== null && ['male', 'female'].includes(label.toLowerCase())) : []);

var educationLevels = Array.isArray(@json($educationLevels)) ? @json($educationLevels).filter(educ_levels => educ_levels != 'Unknown') : [];
var educationData = @json($educationData);  

console.log("Gender:", gender);
console.log("Education Levels:", educationLevels);
console.log("Education Data:", educationData);

var data = {
    labels: educationLevels,
    datasets: gender.map(g => ({
        label: g,
        backgroundColor: g.toLowerCase() === 'male' ? 'skyblue' : 'green',
        data: educationLevels.map(level => {
            const found = educationData.find(d => d.education_level === level && d.sex.toLowerCase() === g.toLowerCase());
            return found ? found.count : 0;
        })
    }))
};

var configs = {
    type: 'bar',
    data: data,
    options: {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            },
            x: {
                ticks: {
                    autoSkip: false,
                    maxRotation: 45,
                    minRotation: 40
                }
            }
        },
        plugins: {
            legend: {
                display: true
            }
        }
    }
};

new Chart(document.getElementById("educationGenderData"), configs);

</script>
