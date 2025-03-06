<?php
$style = 'width:32px; height:32px;';
if (isset($size) && $size == 'medium') {
    $style = 'width:48px; height:48px;';
}
if (isset($size) && $size == 'large') {
    $style = 'width:64; height:64;';
}
?>

<img {{ $attributes }} style="{{$style}}" class="rounded-circle border">