{{-- in your layout/app.css (or <head>) --}}
<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');
  @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');

  body {
    font-family: 'Montserrat', sans-serif;
  }

  .card-modern {
    display: flex;
    flex-direction: column;
    height: 100%;
    border: none;
    border-left: 4px solid transparent;    /* slightly thinner */
    border-radius: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition:
      box-shadow 0.3s ease,
      transform   0.3s ease,
      border-left-color 0.3s ease;
    overflow: hidden;
  }

  .card-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.15);
  }

  .card-modern .card-body {
    padding: 1rem;                   /* smaller padding */
    display: flex;
    flex-direction: column;
    flex: 1;
    justify-content: space-between;
    gap: 0.5rem;                     /* tighter gap */
    text-transform: capitalize;
  }

  .card-modern .card-title {
    font-size: 1.5rem;               /* smaller title */
    font-weight: 600;
    line-height: 1.2;
    margin: 0;
    font-family: "Raleway", sans-serif;
  }

  .card-modern .card-body .h3 {
    font-size: 1.75rem !important;   /* override to a smaller number */
    margin: 0;
  }

  .card-variant-primary { border-left-color: var(--bs-primary); }
  .card-variant-danger  { border-left-color: var(--bs-danger);  }
</style>

{{-- in your Blade component --}}
<?php
  $title     = $title     ?? 'Title';
  $style     = $style     ?? 'success';
  $number    = $number    ?? '0.00';
  $sub_title = $sub_title ?? 'Sub-titles';
  $link      = $link      ?? 'javascript:;';

  $is_dark = isset($is_dark) ? (bool)$is_dark : true;
  $variant = $style === 'danger' ? 'danger' : 'primary';

  $bg     = $is_dark ? "bg-{$variant}" : '';
  $text   = $is_dark ? 'text-white'   : "text-{$variant}";
  $text2  = $is_dark ? 'text-white'   : 'text-dark';

  $ariaLabel = "{$title}: {$number} {$sub_title}.";
?>
<a href="{{ $link }}"
   class="card card-modern card-variant-{{ $variant }} {{ $bg }} text-decoration-none h-100 mb-4 mb-md-5"
   aria-label="{{ $ariaLabel }}">
  <div class="card-body">
    {{-- Title --}}
    <p class="h4 {{ $text }} card-title">
      {{ $title }}
    </p>

    {{-- Number --}}
    <p class="h3 text-right text-bold {{ $text2 }}">
      {{ $number }}
    </p>

    {{-- Subtitle --}}
    <p class="{{ $text2 }}">
      {{ $sub_title }}
    </p>
  </div>
</a>
