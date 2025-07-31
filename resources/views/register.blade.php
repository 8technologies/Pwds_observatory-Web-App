@extends('layouts.base-layout')
@section('base-content')
@php $header_data['header_style'] = 2; @endphp

<main class="page-wrapper">
  @include('layouts.header', $header_data)

  <section class="position-relative h-100 pt-5 pb-4">
    <div class="container h-100 pt-5">
      <div class="row justify-content-center align-items-start h-100">

        {{-- Left: Registration Form --}}
        <div class="col-12 col-md-10 col-lg-6 d-flex align-items-start mb-4 mb-lg-0">
          <div class="w-100" style="max-width:526px;">
            @include('message.message')
            
            <div class="text-center mb-4">
              <h1 class="mb-3">Self Registration</h1>
              <p class="text-muted">Join the PWD Observatory Community</p>
            </div>
            
            <div class="alert alert-info border-0 mb-4">
              <div class="d-flex">
                <i class="bi bi-info-circle-fill text-info me-3 mt-1"></i>
                <div>
                  <p class="mb-0 fs-sm">
                    <strong>Dear respected visitor,</strong> thank you for your interest in the ICT for Persons With Disabilities. 
                    We are seeking to create a national database for Persons With Disabilities to enhance ways of 
                    reaching out and supporting. Please fill out this form to help us get to know you better.
                  </p>
                </div>
              </div>
            </div>
            
            <div class="text-center mb-4">
              <span class="text-muted fs-sm">Already have an account?</span>
              <a href="{{ route('login') }}" class="text-primary fw-medium text-decoration-none ms-1">
                <i class="bi bi-box-arrow-in-right me-1"></i>Login here
              </a>
            </div>

            <div class="card border-0 shadow-sm">
              <div class="card-body p-4">
                <form class="needs-validation" method="POST" action="{{ url('account-activation') }}" novalidate>
                  @csrf

                  {{-- Personal Information --}}
                  <div class="mb-4">
                    <h6 class="text-primary mb-3">
                      <i class="bi bi-person-fill me-2"></i>Personal Information
                    </h6>
                    
                    <div class="row">
                      <div class="col-sm-6 mb-3">
                        @include('components.input-text',[
                          'name'=>'name',
                          'label'=>'Full Name <span style="color:red">*</span>',
                          'placeholder'=>'Enter your full name'
                        ])
                      </div>
                      <div class="col-sm-6 mb-3">
                        @include('components.input-text',[
                          'name'=>'email',
                          'type'=>'email',
                          'label'=>'Email Address <span style="color:red">*</span>',
                          'placeholder'=>'Enter your email address'
                        ])
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-6 mb-3">
                        @include('components.input-text',[
                          'name'=>'password',
                          'type'=>'password',
                          'label'=>'Password <span style="color:red">*</span>',
                          'placeholder'=>'Create a password'
                        ])
                      </div>
                      <div class="col-sm-6 mb-3">
                        @include('components.input-text',[
                          'name'=>'password_confirmation',
                          'type'=>'password',
                          'label'=>'Confirm Password <span style="color:red">*</span>',
                          'placeholder'=>'Confirm your password'
                        ])
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-6 mb-3">
                        @include('components.input-text',[
                          'name'=>'phone_number',
                          'label'=>'Phone Number <span style="color:red">*</span>',
                          'placeholder'=>'07xxxxxxxx'
                        ])
                        <small class="text-muted">Format: 10 digits starting with 0</small>
                      </div>
                      <div class="col-sm-6 mb-3">
                        @include('components.input-text',[
                          'name' => 'dob',
                          'type' => 'date',
                          'label'=> 'Date of Birth <span style="color:red">*</span>',
                        ])
                      </div>
                    </div>
                  </div>

                  {{-- Location & Background --}}
                  <div class="mb-4">
                    <h6 class="text-primary mb-3">
                      <i class="bi bi-geo-alt-fill me-2"></i>Location & Background
                    </h6>
                    
                    <div class="row">
                      <div class="col-sm-6 mb-3">
                        <label for="district" class="form-label">District <span style="color: red">*</span></label>
                        <select name="district" id="district" class="form-select">
                          <option value="">Select your district…</option>
                          @foreach($districts as $id=>$d)
                            <option value="{{ $id }}" {{ old('district')==$id?'selected':'' }}>{{ $d }}</option>
                          @endforeach
                        </select>
                        @error('district')<div class="text-danger mt-1 fs-sm">{{ $message }}</div>@enderror
                      </div>
                      <div class="col-sm-6 mb-3">
                        @include('components.input-text',[
                          'name'=>'village',
                          'label'=>'Village <span style="color:red">*</span>',
                          'placeholder'=>'Enter your village'
                        ])
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-6 mb-3">
                        <label for="disability" class="form-label">Disability Type <span style="color: red">*</span></label>
                        <select name="disability" id="disability" class="form-select">
                          <option value="">Select your disability type…</option>
                          @foreach($disabilities as $id=>$name)
                            <option value="{{ $id }}" {{ old('disability')==$id?'selected':'' }}>{{ $name }}</option>
                          @endforeach
                        </select>
                        @error('disability')<div class="text-danger mt-1 fs-sm">{{ $message }}</div>@enderror
                      </div>
                      <div class="col-sm-6 mb-3">
                        <label class="form-label">Gender <span style="color: red">*</span></label>
                        <div class="mt-2">
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sex" value="Male" id="male" {{ old('sex')=='Male'?'checked':'' }}>
                            <label class="form-check-label" for="male">
                              <i class="bi bi-person-fill me-1"></i>Male
                            </label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sex" value="Female" id="female" {{ old('sex')=='Female'?'checked':'' }}>
                            <label class="form-check-label" for="female">
                              <i class="bi bi-person-dress me-1"></i>Female
                            </label>
                          </div>
                        </div>
                        @error('sex')<div class="text-danger mt-1 fs-sm">{{ $message }}</div>@enderror
                      </div>
                    </div>
                  </div>

                  {{-- Terms and Submit --}}
                  <div class="mb-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="terms" required>
                      <label class="form-check-label fs-sm" for="terms">
                        I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> 
                        and <a href="#" class="text-decoration-none">Privacy Policy</a>
                      </label>
                    </div>
                  </div>

                  <button type="submit" class="btn btn-primary shadow-primary btn-lg w-100 mb-3">
                    <i class="bi bi-person-plus me-2"></i>Create Account
                  </button>
                  
                  <div class="text-center">
                    <span class="text-muted fs-sm">Already have an account?</span>
                    <a href="{{ route('login') }}" class="text-primary fw-medium text-decoration-none ms-1">
                      Sign In
                    </a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        {{-- Right: Fixed Parallax Graphic --}}
        <div class="col-lg-6 d-none d-lg-block">
          <div class="position-sticky" style="top: 100px;">
            <div class="w-100 parallax mx-auto" style="max-width: 600px;">
              <div class="parallax-layer" data-depth="0.1">
                <img src="assets/img/landing/online-courses/hero/layer01.png" alt="Layer" class="img-fluid">
              </div>
              <div class="parallax-layer" data-depth="0.13">
                <img src="assets/img/landing/online-courses/hero/layer02.png" alt="Layer" class="img-fluid">
              </div>
              <div class="parallax-layer zindex-5" data-depth="-0.12">
                <img src="assets/img/landing/online-courses/hero/layer03.png" alt="Layer" class="img-fluid">
              </div>
              <div class="parallax-layer zindex-3" data-depth="0.27">
                <img src="assets/img/landing/online-courses/hero/layer04.png" alt="Layer" class="img-fluid">
              </div>
              <div class="parallax-layer zindex-1" data-depth="-0.18">
                <img src="assets/img/landing/online-courses/hero/layer05.png" alt="Layer" class="img-fluid">
              </div>
              <div class="parallax-layer zindex-1" data-depth="0.1">
                <img src="assets/img/landing/online-courses/hero/layer06.png" alt="Layer" class="img-fluid">
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>

<script src="{{ asset('assets/js/auth-forms.js') }}"></script>
@endsection
