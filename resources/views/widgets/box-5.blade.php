{{-- in your layout/app.css (or <head>) --}}
<style>

   @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');
   @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');

    body {
    font-family: 'Montserrat', sans-serif;
     
  }
  .card-modern {
    border: none;
    border-left: 5px solid transparent;    /* ← 5px left border */
    border-radius: 10px;                     /* ← smaller radius */
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition:
      box-shadow 0.3s ease,
      transform   0.3s ease,
      border-left-color 0.3s ease;
    overflow: hidden;  /* keep the radius */
  }
  /* Title (h4) */
  .card-modern .card-title {
    font-size: 1.75rem;       /* bootstrap h4 default */
    font-weight: 600;         /* semi-bold */
    line-height: 24px;        /* exactly 16px */
    margin: 0;
    font-family: "Raleway", sans-serif;
  }
  .card-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.15);
  }
 
  /* padding & spacing helpers */
  .card-modern .card-body {
    padding: 2rem 2rem ;    /* uniform padding */
    display: flex;
    flex-direction: column;
    gap: 0.8rem;     /* consistent vertical spacing */
    text-transform: capitalize;
  }
  /* left‐border colors for variants */
  .card-variant-primary { border-left-color: var(--bs-primary); }
  .card-variant-danger  { border-left-color: var(--bs-danger);  }
</style>

{{-- in your Blade component --}}
<?php
  $title     = $title     ?? 'Title';
  $style     = $style     ?? 'success';        // success → primary
  $number    = $number    ?? '0.00';
  $sub_title = $sub_title ?? 'Sub-titles';
  $link      = $link      ?? 'javascript:;';

  $is_dark = isset($is_dark) ? (bool)$is_dark : true;
  $variant = $style === 'danger' ? 'danger' : 'primary';

  $bg     = $is_dark ? "bg-{$variant}"    : '';
  $text   = $is_dark ? 'text-white'        : "text-{$variant}";
  $text2  = $is_dark ? 'text-white'        : 'text-dark';

  $ariaLabel = "{$title}: {$number} {$sub_title}.";
?>
<a href="{{ $link }}"
   class="card card-modern card-variant-{{ $variant }} {{ $bg }} mb-4 mb-md-5"
   aria-label="{{ $ariaLabel }}">
    <div class="card-body">

        {{-- Title --}}
        <p class="h4 {{ $text }} m-0 card-title">
            {{ $title }}
        </p>

        {{-- Number --}}
        <p class="h3 text-right {{ $text2 }} m-0">
            {{ $number }}
        </p>

        {{-- Subtitle --}}
        <p class="{{ $text2 }} m-0">
            {{ $sub_title }}
        </p>
    </div>
</a>
