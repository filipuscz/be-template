<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class LanguageService
{
    /**
     * Get all translations for a specific locale.
     */
    public function getTranslations(string $locale): array
    {
        $path = lang_path($locale);

        if (! File::exists($path) && ! File::exists(lang_path("{$locale}.json"))) {
            throw new \Exception('Language not found');
        }

        $translations = [];

        // Load PHP array translations
        if (File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $name = $file->getFilenameWithoutExtension();
                    $translations[$name] = require $file->getPathname();
                }
            }
        }

        // Load JSON translations if they exist
        $jsonPath = lang_path("{$locale}.json");
        if (File::exists($jsonPath)) {
            $jsonTranslations = json_decode(File::get($jsonPath), true);
            if (is_array($jsonTranslations)) {
                $translations['__json'] = $jsonTranslations;
            }
        }

        return $translations;
    }
}
