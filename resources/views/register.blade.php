@extends('layouts.base-layout')
@section('base-content')
@php $header_data['header_style'] = 2; @endphp

<main class="page-wrapper">
  @include('layouts.header', $header_data)

  <section class="position-relative h-100 pt-5 pb-4">
    <div class="container d-flex justify-content-center h-100 pt-5">
      <div class="w-100" style="max-width:526px;">
        <h1 class="text-center">Register</h1>
        <p class="lead fs-sm text-dark mt-3 border border-primary p-3 bg-secondary rounded">
          …Please fill out this form to help us get to know you better.
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

          {{-- Disability & sex --}}
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
  </section>
</main>
@endsection
