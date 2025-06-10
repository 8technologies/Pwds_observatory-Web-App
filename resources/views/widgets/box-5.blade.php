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
               -webkit-line-clamp: 2;
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
