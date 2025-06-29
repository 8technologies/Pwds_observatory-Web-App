{{-- @php
    $title     = $title     ?? 'Title';
    $style     = $style     ?? 'primary';
    $number    = $number    ?? '0.00';
    $sub_title = $sub_title ?? 'Sub-titles';
    $link      = $link      ?? 'javascript:;';
    $is_dark   = isset($is_dark) ? (bool)$is_dark : true;

    $bgClass = $is_dark ? 'bg-light' : 'bg-white';
    $textDark = 'text-dark';
    $textAccent = match($style) {
      'danger'  => 'text-danger',
      'warning' => 'text-warning',
      'info'    => 'text-info',
      default   => 'text-primary',
    };
    $accentColor = match($style) {
      'danger'  => '#dc3545',
      'warning' => '#ffc107',
      'info'    => '#17a2b8',
      default   => '#28a745',
    };

    $ariaLabel = "{$title}: {$number} {$sub_title}.";
@endphp

<style>
 .cards-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;  /* the only spacing between cards */
    margin: 0;    /* no extra outer margin */
    }
  .cards-wrapper .card-analytics {
    margin: 0;    /* fully controlled by the gap above */
    }
  .card-analytics {
    /* spacing between cards */
    margin: 0.5rem;

    /* fixed square size */
    --card-size: 18rem;
    position: relative;
    width: var(--card-size);
    height: var(--card-size);

    border-radius: .5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    overflow: hidden;

    display: flex;
    flex-direction: column;
    justify-content: space-between;

    padding: 1rem;
    transition: transform .2s, box-shadow .2s;
  }
  .card-analytics:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
  }
  .card-analytics::after {
    content: "";
    position: absolute;
    top: 0; right: 0;
    width: 6px;
    height: 100%;
    background: var(--accent);
  }

  /* larger title */
  .card-analytics .title {
    font-weight: 600;
    line-height: 1.2;
    font-size: 1.4rem;      /* ↑ was 1.1rem */
    margin-bottom: .5rem;
  }
  /* larger number */
  .card-analytics .number {
    font-size: 3rem;        /* ↑ was 2.5rem */
    font-weight: 700;
    text-align: right;
    margin: 0;
  }
  /* slightly bigger subtitle */
  .card-analytics .subtitle {
    font-size: 1rem;        /* ↑ was .9rem */
    opacity: .75;
    margin-top: .5rem;
  }
</style>

<div class="cards-wrapper">
<a href="{{ $link }}"
   class="card-analytics {{ $bgClass }} {{ $textDark }}"
   style="--accent: {{ $accentColor }};"
   aria-label="{{ $ariaLabel }}">
    <div>
      <p class="title {{ $textAccent }}">
        <span style="
          display: -webkit-box;
          -webkit-line-clamp: 2;
          -webkit-box-orient: vertical;
          overflow: hidden;
          text-overflow: ellipsis;
        ">{{ $title }}</span>
      </p>
    </div>

    <div>
      <p class="number {{ $textAccent }}">
        {{ $number }}
      </p>
      <p class="subtitle {{ $textDark }}">
        {{ $sub_title }}
      </p>
    </div>
</a>
</div> --}}



<?php

$title     = $title     ?? 'Title';
$style     = $style     ?? 'success';
$number    = $number    ?? '0.00';
$sub_title = $sub_title ?? 'Sub-titles';
$link      = $link      ?? 'javascript:;';

$is_dark = isset($is_dark) ? (bool)$is_dark : true;

$bg     = $is_dark ? 'bg-primary' : '';
$text   = $is_dark ? 'text-white' : 'text-primary';
$text2  = $is_dark ? 'text-white' : 'text-dark';
$border = $is_dark ? 'border-primary' : 'border-primary';

if ($style === 'danger') {
    $text   = 'text-white';
    $bg     = 'bg-danger';
    $text2  = 'text-white';
    $border = 'border-danger';
}

$ariaLabel = "{$title}: {$number} {$sub_title}.";

?>

<a href="{{ $link }}"
   class="card {{ $bg }} {{ $border }} mb-4 mb-md-5"
   aria-label="{{ $ariaLabel }}">
    <div class="card-body py-0">

        {{-- Title with 2-line clamp --}}
        <p class="h3 text-bold mb-2 mb-md-3 {{ $text }}"
           style="
               display: -webkit-box;
               /* -webkit-line-clamp: 2; */
               -webkit-box-orient: vertical;
               overflow: hidden;
               text-overflow: ellipsis;
               line-height: 1.4em;
               max-height: 2.8em;
           ">
            {{ $title }}
        </p>

        {{-- Number --}}
        <p class="m-0 text-right {{ $text2 }} h3" style="line-height: 3.2rem;">
            {{ $number }}
        </p>

        {{-- Subtitle --}}
        <p class="mt-4 {{ $text2 }}">
            {{ $sub_title }}
        </p>
    </div>
</a>