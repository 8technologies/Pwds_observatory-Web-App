@extends('layouts.layout-main')

@section('main-content')
    <!-- Breadcrumb -->
    <nav class="container mt-5   pt-5" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url('') }}"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('disabilities') }}">Disabilities</a>
            </li>
            {{-- <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($disability->title, 20) }}</li> --}}
        </ol>
    </nav>

     <section class="container mb-5 pt-md-4">
            <div class="row">
                @foreach ($disabilities as $disability)
                    <!-- Item -->
                    <div class="col-sm-4 h-auto pb-3">
                        <article class="card border-0 shadow-sm h-100 mx-2">
                            <div class="  position-relative bg-position-center bg-repeat-0 bg-size-cover"
                                style="background-image: url({{ url('storage/' . $disability->photo) }}); min-height: 15rem;">
                            </div>
                            <div class="card-body pb-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <a href="{{ url('disabilities/' . $disability->id) }}"
                                        class="badge fs-sm text-nav bg-secondary text-decoration-none">{{ $disability->name }}</a>
                                    <span class="fs-sm text-muted">{{ $disability->people()->count() }}</span>
                                </div>
                                <h3 class="h5 mb-0">
                                    <a href="{{ url('disabilities/' . $disability->id) }}" title="{{ $disability->name }}">
                                        {!! Str::limit($disability->description, 60) !!}</a>
                                </h3>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <!-- Pagination bootstrap -->
            <div class="row">
                {!! $disabilities->links() !!}
            </div>

        </div>
    </section>

@endsection
