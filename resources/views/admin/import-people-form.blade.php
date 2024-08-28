<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Upload File</h3>
    </div>
    <div class="box-body">
        <form method="POST" action="{{ url('/admin/import-people') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Select Excel File:</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Import</button>
        </form>
    </div>
</div>
