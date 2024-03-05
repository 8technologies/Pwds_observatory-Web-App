<?php
$color = isset($color) ? $color : 'green';
?><div
    style="
width: 100%;
border-color: {{ $color }};
border-left: solid .5rem red;
padding: 1.2rem;
background-color: white;
margin-top: 1rem;
margin-bottom: 2rem;
font-size: 16px;
">

    {{ $msg }}

    <b><a href="{{ $link }}">COMPLETE PROFILE</a></b>
</div>
