@extends('layouts.layout-main')
@section('main-content')
    <!-- Hero -->
    <section class="position-relative pt-5">

        <!-- Background -->
        <div class="position-absolute top-0 start-0 w-100 bg-position-bottom-center bg-size-cover bg-repeat-0"
            style="background-image: url(assets/img/about/hero-bg.svg);">
            <div class="d-lg-none" style="height: 960px;"></div>
            <div class="d-none d-lg-block" style="height: 768px;"></div>
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
                            <li class="breadcrumb-item active" aria-current="page">Our team</li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>
    </section>


    <!-- Team -->
    <section class="container  my-md-3 ">
        <h1 class="h1 text-center pt-1 pb-3 mb-3 mb-lg-4">Our Team</h1>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">




            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/Jude.png" class="rounded-circle -3" alt="Prof Jude Lubega">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Prof. Jude Lubega</h4>
                        <span>CEO Eight Tech Consults </span>
                        <p class="fs-sm mb-0">Eight Tech Consults </p>
                    </div>
                </div>
            </div>

            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/Esther.jpg" class="rounded-circle -3" alt="Kyozira Esther">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Ms. Kyozira Esther</h4>
                        <span>CEO NUDIPU and Project Lead </span>
                        <p class="fs-sm mb-0">NUDIPU</p>
                    </div>
                </div>
            </div>


            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/Drake.jpg" class="rounded-circle -3" alt="Dr. Drake Patrick Mirembe">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Dr. Drake Patrick Mirembe </h4>
                        <span>Project Implementation Lead </span>
                        <p class="fs-sm mb-0">Eight Tech Consults </p>
                    </div>
                </div>
            </div>

            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/Baker.png" class="rounded-circle -3" alt="CPA Baker Ssekiito">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">CPA Baker Ssekiito</h4>
                        <span>Project Manager </span>
                        <p class="fs-sm mb-0">Eight Tech Consults</p>
                    </div>
                </div>
            </div>

            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/Dennis.jpg" class="rounded-circle -3" alt="Mr. Dennis Mpamizo ">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Mr. Dennis Mpamizo </h4>
                        <span>Director Finance </span>
                        <p class="fs-sm mb-0">NUDIPU</p>
                    </div>
                </div>
            </div>
            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/Ronald.jpg" class="rounded-circle -3" alt="Mr. Luyima Ronald ">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Mr. Luyima Ronald </h4>
                        <span>Project Coordinator </span>
                        <p class="fs-sm mb-0">NUDIPU</p>
                    </div>
                </div>
            </div>

            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/JP.jpg" class="rounded-circle -3" alt="Mr. Ategeka John Paul ">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Mr. Ategeka John Paul </h4>
                        <span>Project Coordinator </span>
                        <p class="fs-sm mb-0">Eight Tech Consults</p>
                    </div>
                </div>
            </div>

            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/Betty.jpg" class="rounded-circle -3" alt="Ms. Namagembe Betty ">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Ms. Namagembe Betty</h4>
                        <span>Lead Research </span>
                        <p class="fs-sm mb-0">Eight Tech Consults</p>
                    </div>
                </div>
            </div>


            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/fiona.png" class="rounded-circle -3" alt="Ms. Fiona Nambongo">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Ms. Fiona Nambongo</h4>
                        <span>Lead report writing </span>
                        <p class="fs-sm mb-0">Eight Tech Consults</p>
                    </div>
                </div>
            </div>

            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/isaac.png" class="rounded-circle -3" alt="Mr. Mbabazi Isaac">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Mr. Mbabazi Isaac</h4>
                        <span>Lead Digital skilling </span>
                        <p class="fs-sm mb-0">Eight Tech Consults</p>
                    </div>
                </div>
            </div>

            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/Angel.png" class="rounded-circle -3" alt="Ms. Nagaba Angel ">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Ms. Nagaba Angel </h4>
                        <span>Lead system development </span>
                        <p class="fs-sm mb-0">Eight Tech Consults</p>
                    </div>
                </div>
            </div>
            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/John.jpg" class="rounded-circle -3" alt="Mr. Ninsiima John Chris">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h4 class="fs-lg fw-semibold pt-1 mb-2">Mr. Ninsiima John Chris </h4>
                        <span>Director programs </span>
                        <p class="fs-sm mb-0">NUDIPU</p>
                    </div>
                </div>
            </div>


            <!-- Item -->
            <div class="col">
                <div class="card card-hover border-0 bg-transparent">
                    <div class="position-relative">
                        <img src="/assets/images/team/Syda.jpg" class="rounded-circle -3"
                            alt="Ms. Namuganza Syda Debuni">
                        <div
                            class="card-img-overlay d-flex flex-column align-items-center justify-content-center rounded-3">
                            <span class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25 rounded-3"></span>
                            <div class="position-relative d-flex zindex-2">
                                <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-sm bg-white me-2">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-twitter btn-sm bg-white me-2">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-secondary btn-linkedin btn-sm bg-white">
                                    <i class="bx bxl-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center p-3">
                        <h3 class="fs-lg fw-semibold pt-1 mb-2">Ms. Namuganza Syda Debuni</h3>
                        <span>Finance officer </span>
                        <p class="fs-sm mb-0">NUDIPU</p>
                    </div>
                </div>
            </div>


        </div>
    </section>


    <!-- Contact CTA -->
    <section class="container my-2 my-md-5">
        <div class="card border-0 bg-gradient-primary">
            <div class="card-body p-md-5 p-4 bg-size-cover"
                style="background-image: url(assets/img/landing/digital-agency/contact-bg.png);">
                <div class="py-md-5 py-4 text-center">
                    <h3 class="h4 fw-normal text-light opacity-75">Want to work with us? Let's talk</h3>
                    <a href="mailto:info@ict4personswithdisabilities.org"
                        class="display-6 text-light">info@ict4personswithdisabilities.org</a>
                    <div class="pt-md-5 pt-4 pb-md-2">
                        <a href="mailto:info@ict4personswithdisabilities.org" class="btn btn-lg btn-light">Contact us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>



    </main>
@endsection
