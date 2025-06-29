@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            {{ session('success') }}
        </div>
        <span class="close" style="cursor: pointer; font-size: 1.5rem; margin-top: -0.5rem" aria-hidden="true">&times;</span>
    </div>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            {{ session('error') }}
        </div>
        <span class="close" style="cursor: pointer; font-size: 1.5rem; margin-top: -0.5rem" aria-hidden="true">&times;</span>
    </div>
</div>
@endif

<script>
$(document).ready(function() {
    // Handle close icon click
    $('.alert .close').on('click', function() {
        $(this).closest('.alert').fadeOut(300, function() {
            $(this).remove();
        });
    });
    
    
    setTimeout(function() {
        $('.alert').fadeOut(300, function() {
            $(this).remove();
        });
    }, 6000);
});
</script>