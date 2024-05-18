<style>
    .job-card {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        font-family: "Roboto Mono", sans-serif;
        font-style: normal;
    }

    .text {
        margin: 0px 0;
    }

    .read-more,
    .read-less {
        color: skyblue;
        cursor: pointer;
    }

    .all-jobs {
        font-family: "Roboto Mono", sans-serif;
        font-style: normal;
        background-color: skyblue;
        padding: 10px;
        margin-bottom: 0px;
        font-size: 18px;
        font-weight: 700;
    }

    .main-container {
        background-color: #ffffff;
        margin-top: 2rem;
        margin-bottom: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);

    }

    #inner-container {
        background-color: #ffffff;
    }
</style>

<div class="main-container">
    <h3 class="all-jobs text-center">View available jobs</h3>
    <div class="container" id="inner-container">
        @foreach ($jobs as $job)
            <div class="card job-card mb-4 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">{{ $job->title }}</h4>
                    <p class="card-text"><span class="font-weight-bold">Location: </span>{{ $job->location }}</p>
                    <p class="card-text"><span class="font-weight-bold">Type: </span>{{ $job->type }}</p>
                    <p class="card-text"><span class="font-weight-bold">Created Date:
                        </span>{{ $job->created_at->format('Y-m-d') }}</p>
                    <p class="card-text"><span class="font-weight-bold">Deadline: </span>{{ $job->deadline }}</p>

                    <div id="short_{{ $job->id }}" class="text">
                        {!! Str::limit($job->description, 400) !!}
                        <a href="javascript:void(0);" onclick="expandText('{{ $job->id }}')"
                            class="text-primary font-weight-bold">Read More</a>
                    </div>

                    <div id="full_{{ $job->id }}" class="text full-text" style="display: none;">
                        {!! $job->description !!}
                        <a href="javascript:void(0);" onclick="collapseText('{{ $job->id }}')"
                            class="text-primary font-weight-bold">Read Less</a>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </div>
</div>

<script>
    function expandText(id) {
        var shortText = document.getElementById('short_' + id);
        var fullText = document.getElementById('full_' + id);
        shortText.style.display = 'none';
        fullText.style.display = 'block';
    }

    function collapseText(id) {
        var shortText = document.getElementById('short_' + id);
        var fullText = document.getElementById('full_' + id);
        shortText.style.display = 'block';
        fullText.style.display = 'none';
    }
</script>
