<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <!-- Page Header with Logo -->
    <div class="text-center mb-4">
        <img src="assets/img/logo-1.png" alt="Eight Tech Consults Logo" class="img-fluid" style="max-width: 150px;">
        <h1 class="display-4 text-primary mt-3">{{ $content }}</h1>
    </div>

    <!-- Card: Total PwDs -->
    <div class="card mb-4">
        <div class="card-header text-center bg-info text-white">
            <h2>Total PwDs: <span class="font-weight-bold">{{ $personsCount }}</span></h2>
        </div>
        <div class="card-body">
            <p class="card-text">
                This number represents the total number of Persons with Disabilities (PwDs) registered in the ICT Observatory. The observatory tracks various indicators related to their access to ICT resources, training, and employment opportunities.
            </p>
        </div>
    </div>

    <!-- Card: Total Users -->
    <div class="card mb-4">
        <div class="card-header text-center bg-info text-white">
            <h2>Total Users: <span class="font-weight-bold">{{ $usersCount }}</span></h2>
        </div>
        <div class="card-body">
            <p class="card-text">
                This number indicates the total number of users who have interacted with the ICT for PwDs Observatory platform. This includes trainers, service providers, and other stakeholders contributing to the ecosystem.
            </p>
        </div>
    </div>

      <!-- Card: Total Districts -->
 <div class="card mb-4">
        <div class="card-header text-center bg-info text-white">
            <h2>Total Districts Covered: <span class="font-weight-bold">{{ $districtsCount }}</span></h2>
        </div>
        <div class="card-body">
            <p class="card-text">
                The total number of districts represented in the observatory in Uganda. This data helps in identifying the geographical spread of Persons with Disabilities (PwDs) and the availability of resources in different regions.
            </p>
        </div> 
 </div>

 <!-- Card: Total Organisations -->
 <div class="card mb-4">
        <div class="card-header text-center bg-info text-white">
            <h2>Total Organisations: <span class="font-weight-bold">{{ $organisationsCount }}</span></h2>
        </div>
        <div class="card-body">
            <p class="card-text">
                The total number of organizations registered on the ICT for PwDs Observatory platform. These organizations include NGOs, government agencies, and private sector companies working towards the inclusion and empowerment of Persons with Disabilities.
            </p>
        </div>
    </div>

     <!-- Card: Total Jobs -->
     <div class="card mb-4">
        <div class="card-header text-center bg-info text-white">
            <h2>Total Jobs: <span class="font-weight-bold">{{ $jobsCount }}</span></h2>
        </div>
        <div class="card-body">
            <p class="card-text">
                The total number of job opportunities made available through the observatory for Persons with Disabilities (PwDs). This showcases employment opportunities provided by various organizations committed to inclusive hiring.
            </p>
        </div>
    </div>

    <!-- Card: Total Disabilities -->
    <div class="card mb-4">
        <div class="card-header text-center bg-info text-white">
            <h2>Total Disabilities: <span class="font-weight-bold">{{ $disabilitiesCount }}</span></h2>
        </div>
        <div class="card-body">
            <p class="card-text">
                This figure represents the total number of unique disabilities categorized and tracked within the observatory. It helps identify and address specific challenges faced by individuals with varying types of disabilities.
            </p>
        </div>
    </div>
     <!-- Card: Total Innovations -->
    <div class="card mb-4">
        <div class="card-header text-center bg-info text-white">
            <h2>Total Innovations: <span class="font-weight-bold">{{ $innovationsCount }}</span></h2>
        </div>
        <div class="card-body">
            <p class="card-text">
                The total number of innovative solutions and technologies developed to address the needs of Persons with Disabilities (PwDs). These innovations are aimed at improving accessibility, education, and employment opportunities for PwDs.
            </p>
        </div>
    </div>
    
    <!-- Card: Total Regions -->
    <!-- <div class="card mb-4">
        <div class="card-header text-center bg-info text-white">
            <h2>Total Regions: <span class="font-weight-bold">{{ $regionsCount }}</span></h2>
        </div>
        <div class="card-body">
            <p class="card-text">
                The observatory tracks data across four key regions: Central, Eastern, Northern, and Western. This regional segmentation helps in understanding the distribution of services and resources for Persons with Disabilities across different geographical areas.
            </p>
        </div>
    </div> -->

    <!-- Custom Footer -->
    <footer class="text-center mt-5">
        <p class="text-muted">Generated on {{ \Carbon\Carbon::now()->format('d M Y') }} by 
            <a href="https://8technologies.net/" target="_blank" class="text-info font-weight-bold">Eight Tech Consults Ltd</a>
        </p>
    </footer>
</body>
</html>