<?php

use Illuminate\Support\Str;

if (! function_exists('throttleKey')) {
    /**
     * throttleKey
     */
    function throttleKey(string $input): string
    {
        return Str::transliterate(Str::lower($input));
    }
}
