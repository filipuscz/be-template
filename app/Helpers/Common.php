<?php

use Illuminate\Support\Str;

if(!function_exists('throttleKey')) {
    /**
     * throttleKey
     *
     * @param string $input
     * @return string
     */
    function throttleKey(string $input): string {
        return Str::transliterate(Str::lower($input));
    }
}