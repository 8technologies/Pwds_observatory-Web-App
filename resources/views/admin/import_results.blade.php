<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Results</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-logo {
            max-width: 150px;
        }
        .footer {
            padding: 1rem;
            background-color: #f8f9fa;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="bg-light py-3 mb-4">
        <div class="container text-center">
            <img src="{{ asset('assets/img/logo-1.png') }}" alt="Eight Tech Consults Logo" class="header-logo">
            <h1 class="display-4 text-primary mt-3">PwD Profiling Results</h1>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="alert alert-info">
            <strong>Import complete!</strong> Total records: {{ $total_records }}, Imported: {{ $total_imported }}, Failed: {{ $total_failed }}
        </div>
        @if($error_message)
            <div class="alert alert-danger">
                <strong>Errors:</strong><br>
                {!! $error_message !!}
            </div>
        @endif

        <!-- Results Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Records</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                    <tr>
                        <td>{{ $result['record'] }}</td>
                        <td class="{{ $result['status'] == 'SUCCESS' ? 'text-success' : 'text-danger' }}">{{ $result['status'] }}</td>
                        <td>
                            @foreach($result['data'] as $header => $value)
                            <strong>{{ $header }}:</strong> {{ $value ?: 'N/A' }}<br>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="/admin/people" class="btn btn-primary">View People</a>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer text-center mt-5">
        <p class="mb-0">Generated on {{ \Carbon\Carbon::now()->format('d M Y') }} by 
            <a href="https://8technologies.net/" target="_blank" class="text-info font-weight-bold">Eight Tech Consults Ltd</a>
        </p>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
