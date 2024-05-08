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
</style>

<div class="container">
    <h3 class="all-jobs">View available jobs</h3>
    @foreach ($jobs as $job)
        <div class="card job-card">
            <h4>{{ $job->title }}</h4>
            <p><span>Location: </span>{{ $job->location }}</p>
            <p><span>Type: </span>{{ $job->type }}</p>
            <p><span>Created Date: </span>{{ $job->created_at->format('Y-m-d') }}</p>
            <p><span>Deadline: </span>{{ $job->deadline }}</p>

            <div id="short_{{ $job->id }}" class="text">
                {!! Str::limit($job->description, 400) !!}
                <span onclick="expandText('{{ $job->id }}')" class="read-more">...........Read More</span>
            </div>

            <div id="full_{{ $job->id }}" class="text full-text" style="display: none;">
                {!! $job->description !!}
                <span onclick="collapseText('{{ $job->id }}')" class="read-less">........Read Less</span>
            </div>
        </div>
        <hr>
    @endforeach
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
