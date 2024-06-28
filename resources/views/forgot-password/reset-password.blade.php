<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Account Activation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center vh-100 align-items-center"> <!-- vh-100 ensures full viewport height -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        <form class="needs-validation" method="POST" action="{{ route('password.update') }}"
                            novalidate>
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" class="form-control" id="email" placeholder="Email address"
                                    value="{{ $email ?? old('email') }}" name="email" required>
                            </div>

                            <div class="col-12 mb-3">
                                @include('components.input-text', [
                                    'name' => 'password',
                                    'type' => 'password',
                                    'label' => 'Password',
                                ])
                            </div>

                            <div class="col-12 mb-4">
                                @include('components.input-text', [
                                    'name' => 'password_confirmation',
                                    'type' => 'password',
                                    'label' => 'Confirm Password',
                                ])
                            </div>

                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary shadow-primary btn-lg w-100">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
