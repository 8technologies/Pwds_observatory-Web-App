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
            <h1 class="text-center">Self Registration</h1>
            <p class="lead fs-sm text-dark mt-3 border border-primary p-3 bg-secondary rounded">
              Dear respected visitor, thank you for your interest in the ICT for Persons With Disabilities. We
              are seeking to create a national database for Persons With Disabilities to enhance ways of
              reaching out and supporting. Please fill out this form to help us get to know you better.
            </p>
            <p class="text-center pb-3">Already registered? <a href="{{ url('login') }}">Login here</a></p>

            <form class="needs-validation" method="POST" action="{{ url('account-activation') }}" novalidate>
              @csrf

              {{-- Full Name & Email --}}
              <div class="row">
                <div class="col-sm-6 mb-3">
                  @include('components.input-text',['name'=>'name','label'=>'Full Name'])
                </div>
                <div class="col-sm-6 mb-3">
                  @include('components.input-text',['name'=>'email','type'=>'email','label'=>'Email Address'])
                </div>
              </div>

              {{-- Password & Confirmation --}}
              <div class="row">
                <div class="col-sm-6 mb-3">
                  @include('components.input-text',['name'=>'password','type'=>'password','label'=>'Password'])
                </div>
                <div class="col-sm-6 mb-3">
                  @include('components.input-text',['name'=>'password_confirmation','type'=>'password','label'=>'Confirm Password'])
                </div>
              </div>

              {{-- Phone & District --}}
              <div class="row">
                <div class="col-sm-6 mb-3">
                  @include('components.input-text',['name'=>'phone_number','label'=>'Phone Number'])
                </div>
                <div class="col-sm-6 mb-3">
                  <label for="district" class="form-label">District</label>
                  <select name="district" id="district" class="form-select">
                    <option value="">Select…</option>
                    @foreach($districts as $id=>$d)
                      <option value="{{ $id }}" {{ old('district')==$id?'selected':'' }}>{{ $d }}</option>
                    @endforeach
                  </select>
                  @error('district')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
              </div>

              {{-- Disability & Gender --}}
              <div class="row">
                <div class="col-sm-6 mb-3">
                  <label for="disability" class="form-label">Disability</label>
                  <select name="disability" id="disability" class="form-select">
                    <option value="">Select…</option>
                    @foreach($disabilities as $id=>$name)
                      <option value="{{ $id }}" {{ old('disability')==$id?'selected':'' }}>{{ $name }}</option>
                    @endforeach
                  </select>
                  @error('disability')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 mb-3">
                  <label class="form-label">Gender</label><br>
                  <label class="me-3">
                    <input type="radio" name="sex" value="Male"   {{ old('sex')=='Male'?'checked':'' }}> Male
                  </label>
                  <label class="me-3">
                    <input type="radio" name="sex" value="Female" {{ old('sex')=='Female'?'checked':'' }}> Female
                  </label>
                  @error('sex')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
              </div>

              {{-- Village --}}
              <div class="mb-3">
                @include('components.input-text',['name'=>'village','label'=>'Village'])
              </div>

              {{-- Date of Birth --}}
              <div class="mb-4">
                @include('components.input-text',[
                  'name' => 'dob',
                  'type' => 'date',
                  'label'=> 'Date of Birth',
                ])
              </div>

              <button type="submit" class="btn btn-primary shadow-primary btn-lg w-100">Sign up</button>
            </form>
          </div>
        </div>

        {{-- Right: Fixed Parallax Graphic --}}
        <div class="col-lg-6 d-none d-md-block">
          <div class="w-100 align-self-end parallax mx-auto" style="max-width: 600px; position: sticky; top: 100px;">
            <div class="parallax-layer" data-depth="0.1">
              <img src="assets/img/landing/online-courses/hero/layer01.png" alt="Layer">
            </div>
            <div class="parallax-layer" data-depth="0.13">
              <img src="assets/img/landing/online-courses/hero/layer02.png" alt="Layer">
            </div>
            <div class="parallax-layer zindex-5" data-depth="-0.12">
              <img src="assets/img/landing/online-courses/hero/layer03.png" alt="Layer">
            </div>
            <div class="parallax-layer zindex-3" data-depth="0.27">
              <img src="assets/img/landing/online-courses/hero/layer04.png" alt="Layer">
            </div>
            <div class="parallax-layer zindex-1" data-depth="-0.18">
              <img src="assets/img/landing/online-courses/hero/layer05.png" alt="Layer">
            </div>
            <div class="parallax-layer zindex-1" data-depth="0.1">
              <img src="assets/img/landing/online-courses/hero/layer06.png" alt="Layer">
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</main>
@endsection
