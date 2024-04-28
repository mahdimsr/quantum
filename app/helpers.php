<?php

if (!function_exists('img')){

    function img(string $name): string
    {
        return asset('img/' . $name);
    }
}
