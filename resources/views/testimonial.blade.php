@extends('layouts.layout-main')
@section('main-content')
    <!-- Hero -->
    <section class="position-relative pt-5">

        <!-- Background -->
       <div class="position-absolute top-0 start-0 w-100 bg-position-bottom-center bg-size-cover bg-repeat-0 top-lining">
            
            <div class="d-none d-lg-block" style="height: 178px;"></div>
        </div>

        <!-- Content -->
        <div class="container position-relative zindex-5 pt-5">
            <div class="row">
                <div class="col-lg-6">
                    <!-- Breadcrumb -->
                    <nav class="pt-md-2 pt-lg-3 pb-4 pb-md-5 mb-xl-4" aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ url('') }}"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Testimonials</li>
                        </ol>
                    </nav>
                </div>
            </div>
              <div class="row justify-content-center mb-3">
      <div class="col-lg-7 text-center" data-aos="fade-up" data-aos-delay="0">
        <h2 class="line-bottom text-center mb-4">Program Testimonials</h2>
        <p class="text-center mt-2">
                
            </p>
      </div>
    </div>

        </div>
    </section>
    

    <!-- Service-->
<div class="testimonials">
  <div class="container">
       <div class="video-gallery">
                <div class="video-item">
                    <video controls>
                        <source src="assets/testimonials/ICT4PWD1.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="video-caption"><h4>Introduction of National Digital Observatory in Arua</h4></div>
                </div>
                <div class="video-item">
                    <video controls>
                        <source src="assets/testimonials/ICT4PWD3.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="video-caption"><h4>Using the Digital Observatory to manage member information in district unions</h4></div>
                </div>
                <div class="video-item">
                    <video controls>
                        <source src="assets/testimonials/ICT4PWD4.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="video-caption"><h4>Digital Skilling in Yumbe</h4></div>
                </div>
                <div class="video-item">
                    <video controls>
                        <source src="assets/testimonials/ICT4PWD.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="video-caption"><h4>Using mobile phones as assistive devices</h4></div>
                </div>
                  <div class="video-item">
                    <video controls>
                        <source src="assets/testimonials/ICT4PWD2.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="video-caption"><h4>Using mobile phones as assistive devices</h4></div>
                </div>
            </div>
   
   
    </div>
  </div>
</div>

@endsection
