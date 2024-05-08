@extends('layouts.layout-main')
@section('main-content')
    <?php
    use App\Models\PostCategory;
    use App\Models\NewsPost;
    use App\Models\Utils;
    if (!isset($header_style)) {
        $header_style = 11;
    }
    ?>

    <!-- Hero section with layer parallax gfx -->
    <section class="position-relative py-5 " style="margin-top: 4.5rem;">

        <!-- Gradient BG -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-primary opacity-10"></div>

        <!-- Content -->
        <div class="container position-relative zindex-2 py-lg-4">
            <div class="row">
                <div class="col-lg-5 d-flex flex-column pt-lg-4 pt-xl-5">
                    <h5 class="h4 my-2">Welcome!</h5>
                    <h1 class="display-3 mb-4"> <span class="text-primary">ICT</span> for Persons With Disabilities</h1>


                    <p class="fs-lg mb-5"><b>NUDIPU</b> in collaboration with UCC, 8Tech Consults and other stakeholders.!
                        join hands together to put in place for you this System that will help you, Enhance your Knowledge
                        Management, ICT Adoption, Digital
                        Skills, And Access To E-Services For Persons With Disabilities.!</p>

                    {{-- <!-- Desktop form -->
                    <form class="d-none d-sm-flex mb-5">
                        <div class="input-group d-block d-sm-flex input-group-lg me-3">
                            <input type="text" class="form-control w-50" placeholder="Search...">
                            <select class="form-select w-50">
                                <option value="Software Testing">News</option>
                                <option value="Software Engineering">Events</option>
                                <option value="Network & Security">Job opportunities</option>
                                <option value="Mobile Development">Products & services</option>
                                <option value="" selected disabled>Service providers</option>
                                <option value="Web Development">Counselors</option>
                                <option value="Game Development">Institutions</option>
                                <option value="Programming">Innovations</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-icon btn-primary btn-lg">
                            <i class="bx bx-search"></i>
                        </button>
                    </form>

                    <!-- Mobile form -->
                    <form class="d-sm-none mb-5">
                        <input type="text" class="form-control form-control-lg mb-2" placeholder="Search courses...">
                        <select class="form-select form-select-lg mb-2">
                            <option value="" selected disabled>Categories</option>
                            <option value="Web Development">Web Development</option>
                            <option value="Mobile Development">Mobile Development</option>
                            <option value="Programming">Programming</option>
                            <option value="Game Development">Game Development</option>
                            <option value="Software Testing">Software Testing</option>
                            <option value="Software Engineering">Software Engineering</option>
                            <option value="Network & Security">Network &amp; Security</option>
                        </select>
                        <button type="submit" class="btn btn-icon btn-primary btn-lg w-100 d-sm-none">
                            <i class="bx bx-search"></i>
                        </button>
                    </form> --}}
                    <div class="d-flex align-items-center mt-auto mb-3 mb-lg-0 pb-4 pb-lg-0 pb-xl-5">
                        <div class="d-flex me-3">
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle ms-n3"
                                style="width: 60px; height: 60px;">
                                <img src="storage/images/u-3.png" class="rounded-circle" width="57" alt="Avatar">
                            </div>
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle ms-n3"
                                style="width: 60px; height: 60px;">
                                <img src="storage/images/u-7.png" class="rounded-circle" width="57" alt="Avatar">
                            </div>
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle ms-n3"
                                style="width: 60px; height: 60px;">
                                <img src="storage/images/u-8.png" class="rounded-circle" width="57" alt="Avatar">
                            </div>
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle ms-n3"
                                style="width: 60px; height: 60px;">
                                <img src="storage/images/u-9.png" class="rounded-circle" width="57" alt="Avatar">
                            </div>
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-circle ms-n3"
                                style="width: 60px; height: 60px;">
                                <img src="storage/images/u-10.png" class="rounded-circle" width="57" alt="Avatar">
                            </div>
                        </div>
                        <span class="fs-sm"><span class="text-primary fw-semibold">3,257+</span> people are already with
                            us!</span> <small>&nbsp;<a href="{{ admin_url() }}">Join Now</a></small>
                    </div>
                </div>
                <div class="col-lg-7">

                    <!-- Parallax gfx -->
                    <div class="parallax mx-auto me-lg-0" style="max-width: 648px;">
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

    <!-- Feature section (App) -->
    <section class="container pb-5 mb-md-2 mb-lg-4 mb-xl-5 py-5">
        <div class="row align-items-center pt-2 pb-3">

            <!-- Text -->
            <div class="col-md-6 col-xl-5 text-center text-md-start mb-5 mb-md-0">
                <h2 class="h1 pb-2 pb-lg-3">About ICT for Persons With Disabilities.</h2>
                <p class="pb-2 mb-4 mb-lg-5">Uganda Communications Commission (UCC), through The Uganda Communications
                    Universal Service and Access Fund (UCUSAF), which is a Universal Service Fund (USF) for communications
                    in Uganda, launched a call for business plan proposals to establish collaboration on the implementation
                    of key activities under a general thematic area of addressing digital inclusiveness of Persons with
                    Disabilities.</p>
                <hr>
                <div class="d-flex justify-content-center justify-content-md-between pt-4 pt-lg-5">
                    <div class="mx-3 mx-md-0">
                        <div class="display-3 text-dark mb-1">{{ $female_percentage }}%</div>
                        <span>Females - with disabilities</span>
                    </div>
                    <div class="mx-3 mx-md-0">
                        <div class="display-3 text-dark mb-1">{{ $male_percentage }}%</div>
                        <span>Males - with disabilities</span>
                    </div>
                </div>
            </div>

            <!-- Parallax gfx -->
            <div class="col-md-6 offset-xl-1">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </section>



    <!-- Features -->
    <section id="features" class="  mt-n2 mt-sm-0 mb-2 mb-md-4 mb-lg-5 bg-secondary py-5">
        <div class="container">
            <div class="row pb-xl-3">

                <!-- Parallax gfx -->
                <div class="col-lg-4 d-none d-lg-block">
                    <div class="position-relative" style="max-width: 348px;">
                        <img src="assets/img/landing/app-showcase/features/phone.png" alt="Phone">
                        <div class="rellax position-absolute top-0" data-rellax-vertical-scroll-axis="x"
                            data-rellax-horizontal-speed="0.75" data-disable-parallax-down="lg"
                            style="max-width: 348px; right: -2.75rem;">
                            <img src="assets/img/landing/app-showcase/features/card.png" alt="Card">
                        </div>
                    </div>
                </div>

                <!-- Feature list -->
                <div class="col-lg-8">
                    <h2 class="h1 text-center text-md-start mb-4">Services and Benefits</h2>
                    <p class="fs-lg text-muted text-center text-md-start mb-4 mb-xl-5">
                        ICT for Persons With Disabilities - Digital observatory is a system built a variety of solutions to
                        simply day-to-day life of a person with disability or people who give care to persons with
                        disabilites as listed below.
                    </p>
                    <div class="row row-cols-1 row-cols-sm-2 pt-2 pt-sm-3 pt-xl-2">

                        <!-- Item -->
                        <div class="col pb-2 pb-xl-0 mb-4 mb-xl-5">
                            <div class="d-flex align-items-start pe-xl-3">
                                <div class="d-table bg-secondary rounded-3 flex-shrink-0 p-3 mb-3">
                                    <img src="assets/img/form.png" class="img-fluid" style="width: 4rem" alt="Icon">
                                </div>
                                <div class="ps-4 ps-sm-3 ps-md-4">
                                    <h3 class="h5 pb-1 mb-2">Persons With Disabilities - Profiling</h3>
                                    <p class="mb-0">To register a people with disabilities to the Uganda National
                                        Database of persons with disabilities.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Item -->
                        <div class="col pb-2 pb-xl-0 mb-4 mb-xl-5">
                            <div class="d-flex align-items-start ps-xl-3">
                                <div class="d-table bg-secondary rounded-3 flex-shrink-0 p-3 mb-3">
                                    <img src="assets/img/jobs.png" class="img-fluid" style="width: 4rem" alt="Icon">
                                </div>
                                <div class="ps-4 ps-sm-3 ps-md-4">
                                    <h3 class="h5 pb-1 mb-2">Jobs and Opportunities</h3>
                                    <p class="mb-0">Browse job opportunities in Uganda that are suitable for persons with
                                        disabilites.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="col pb-2 pb-xl-0 mb-4 mb-xl-5">
                            <div class="d-flex align-items-start pe-xl-3">
                                <div class="d-table bg-secondary rounded-3 flex-shrink-0 p-3 mb-3">
                                    <img src="assets/img/shop.png" class="img-fluid" style="width: 4rem" alt="Icon">
                                </div>
                                <div class="ps-4 ps-sm-3 ps-md-4">
                                    <h3 class="h5 pb-1 mb-2">Shop</h3>
                                    <p class="mb-0">Buy or Sell your products and services that can help persons with
                                        disabilites in their day-to-day life.',
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="col pb-2 pb-xl-0 mb-4 mb-xl-5">
                            <div class="d-flex align-items-start ps-xl-3">
                                <div class="d-table bg-secondary rounded-3 flex-shrink-0 p-3 mb-3">
                                    <img src="assets/img/counselors.png" class="img-fluid" style="width: 4rem"
                                        alt="Icon">
                                </div>
                                <div class="ps-4 ps-sm-3 ps-md-4">
                                    <h3 class="h5 pb-1 mb-2">Counseling services</h3>
                                    <p class="mb-0">Browse, meet and talk counselors across different parts of Uganda.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="col pb-2 pb-xl-0 mb-4 mb-xl-5">
                            <div class="d-flex align-items-start pe-xl-3">
                                <div class="d-table bg-secondary rounded-3 flex-shrink-0 p-3 mb-3">
                                    <img src="assets/img/news.png" class="img-fluid" style="width: 4rem" alt="Icon">
                                </div>
                                <div class="ps-4 ps-sm-3 ps-md-4">
                                    <h3 class="h5 pb-1 mb-2">News</h3>
                                    <p class="mb-0">Stay updated with latest news based on persons with disabilities.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Item -->
                        <div class="col pb-2 pb-xl-0 mb-4 mb-xl-5">
                            <div class="d-flex align-items-start ps-xl-3">
                                <div class="d-table bg-secondary rounded-3 flex-shrink-0 p-3 mb-3">
                                    <img src="assets/img/landing/app-showcase/features/happy.svg" style="width: 4rem"
                                        alt="Icon">
                                </div>
                                <div class="ps-4 ps-sm-3 ps-md-4">
                                    <h3 class="h5 pb-1 mb-2">So much more </h3>
                                    <p class="mb-0">And much more services such as Events, Institutions, Associations,
                                        Innovations and Testimonials for persons with disabilities.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- How it works (Steps + Video) -->
    <section class="container">
        <div class="text-center pb-4 pb-md-0 mb-2 mb-md-5 mx-auto" style="max-width: 530px;">
            <h2 class="h1">How Does It Work?</h2>
            <p class="mb-0">This platform is not purposely for only persons with disabilities but also caregivers,
                service providers, counselors, institutions and anyone who need to contribute to livelihood of persons with
                disabilities (well-wishers). Below are steps how to use the platform.</p>
        </div>

        <!-- Steps -->
        <div class="steps steps-sm steps-horizontal-md steps-center pb-5 mb-md-2 mb-lg-3">
            <div class="step">
                <div class="step-number">
                    <div class="step-number-inner">1</div>
                </div>
                <div class="step-body">
                    <h3 class="h4 mb-3">Registration </h3>
                    <p class="mb-0">Press the register button to create your account username and password.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">
                    <div class="step-number-inner">2</div>
                </div>
                <div class="step-body">
                    <h3 class="h4 mb-3">Profiling</h3>
                    <p class="mb-0">Complete your profile to clarify the purpose
                        and the role that will play into this System.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">
                    <div class="step-number-inner">3</div>
                </div>
                <div class="step-body">
                    <h3 class="h4 mb-3">Identity verification</h3>
                    <p class="mb-0">We will then manually review your profile information if it is correct.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">
                    <div class="step-number-inner">4</div>
                </div>
                <div class="step-body">
                    <h3 class="h4 mb-3">Enjoy!</h3>
                    <p cl ass="mb-0">Once we aprove your profile, you will then be able to access all system features!
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- Feature section (App) -->
    <section class=" pb-5 mb-md-2 mb-lg-4 mb-xl-5 bg-secondary py-5">
        <div class="container">


            <div class="row align-items-center pt-2 pb-3">

                <!-- Parallax gfx -->
                <div class="col-md-5">
                    <canvas id="topDistricts"></canvas>
                </div>

                <!-- Text -->
                <div class="col-md-7  text-center text-md-start mb-5 mb-md-0">
                    <h2 class="h1 pb-2 pb-lg-3">TOP 5 Districts with Persons With Disabilities.</h2>
                    <p class="pb-2 mb-4 mb-lg-5">Uganda Communications Commission (UCC), through The Uganda Communications
                        Universal Service and Access Fund (UCUSAF), which is a Universal Service Fund (USF) for
                        communications
                        in Uganda, launched a call for business plan proposals to establish collaboration on the
                        implementation
                        of key activities under a general thematic area of addressing digital inclusiveness of Persons with
                        Disabilities.</p>
                    <hr>
                    <div class="row  pt-4 pt-lg-5">

                        @foreach ($districts_count as $district)
                            <div class="col mx-3 mx-md-0">
                                <div class="display-3 text-dark mb-1">
                                    {{ round(($district->total / $districts_count->sum('total')) * 100), 2 }}%</div>
                                <span>{{ $district->district }}</span>
                            </div>
                        @endforeach

                        {{-- <div class="col mx-3 mx-md-0">
                            <div class="display-3 text-dark mb-1">70%</div>
                            <span>Kampala</span>
                        </div>
                        <div class="col mx-3 mx-md-0">
                            <div class="display-3 text-dark mb-1">12%</div>
                            <span>Mbarara</span>
                        </div>
                        <div class="col mx-3 mx-md-0">
                            <div class="display-3 text-dark mb-1">10%</div>
                            <span>Kasese</span>
                        </div>
                        <div class="col mx-3 mx-md-0">
                            <div class="display-3 text-dark mb-1">4%</div>
                            <span>Jinja</span>
                        </div>
                        <div class="col mx-3 mx-md-0">
                            <div class="display-3 text-dark mb-1">3%</div>
                            <span>Arua</span>
                        </div> --}}
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- Feature section (App) -->
    <section class="container pb-5 mb-md-2 mb-lg-4 mb-xl-5 py-5">
        <div class="row align-items-center pt-2 pb-3">



            <div class="col-md-6  text-center text-md-start mb-5 mb-md-0">
                <h2 class="h1 pb-2 pb-lg-3">Registered Persons With Disabilities - in past 12 Months</h2>
                <p class="pb-2 mb-4 mb-lg-5">Uganda Communications Commission (UCC), through The Uganda Communications
                    Universal Service and Access Fund (UCUSAF), which is a Universal Service Fund (USF) for
                    communications
                    in Uganda, launched a call for business plan proposals to establish collaboration on the
                    implementation
                    of key activities under a general thematic area of addressing digital inclusiveness of Persons with
                    Disabilities.</p>

            </div>

            <!-- Parallax gfx -->
            <div class="col-md-6 ">
                <canvas id="months"></canvas>
            </div>

        </div>
    </section>
@endsection



@section('bellow-footer')
    <script>
        const ctx = document.getElementById('myChart');
        const data = {
            labels: @json(
                $persons_by_disability->pluck('count', 'name')->map(function ($count, $name) {
                        return "$name - $count";
                    })->values()),
            datasets: [{
                label: 'Disability Count',
                data: @json($persons_by_disability->pluck('count')),
                backgroundColor: [
                    '#8EFCDF',
                    '#F43DE3',
                    '#F6DE5C',
                    '#7D57F8',
                    '#431B02',
                    '#23A2E9',
                    '#34F1B7',
                    '#868686',
                    '#C71C5D',
                    '#D0B1FD',
                ],
                hoverOffset: 4
            }]
        };
        const config = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
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
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Registered Persons With disabilities in Uganda  - By Disability categories'
                    }
                }
            },
        };


        new Chart(ctx, config);
        new Chart(document.getElementById('topDistricts'), {
            type: 'pie',
            data: {
                labels: @json($districts_count->pluck('district')),
                datasets: [{
                    label: 'Count',
                    data: @json($districts_count->pluck('total')),
                    backgroundColor: [
                        '#23A2E9',
                        '#F43DE3',
                        '#7D57F8',
                        '#34F1B7',
                        '#F6DE5C',
                        '#C71C5D',
                        '#431B02',
                        '#370133',
                        '#868686',
                        '#D0B1FD',
                        '#8EFCDF',
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'TOP 5 Distticts with Persons With Disabilities'
                    }
                }
            },
        });

        @php

        @endphp


        new Chart(document.getElementById('months'), {
            type: 'bar',
            data: {
                labels: [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December',
                ],
                datasets: [{
                    label: 'Registered per month',
                    data: [309, 53, 108, 461, 520, 127, 132, 55, 181, 301, 121, 654],
                    backgroundColor: [
                        '#23A2E9',
                        '#F43DE3',
                        '#7D57F8',
                        '#34F1B7',
                        '#F6DE5C',
                        '#C71C5D',
                        '#431B02',
                        '#370133',
                        '#868686',
                        '#D0B1FD',
                        '#8EFCDF',
                    ],
                    hoverOffset: 0
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Registered Persons With Disabilities - in past 12 Months'
                    }
                }
            },
        });
    </script>
@endsection
