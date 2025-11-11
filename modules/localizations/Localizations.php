<?php
class Localizations extends Trongate {

    private array $translationsCache = [];

    /**
     * Maps a string of locales to their respective languages.
     *
     * @var array
     */
    public const LOCALE_MAPPINGS = [
        'da' => 'da_DK',
        'en' => 'en_US'
    ];

    public function _translator(string $language = 'da')
    {
        $controller_basename = basename(str_replace('\\', '/', debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['file']));
        $controller_classname = str_replace('.php', '', $controller_basename);
        $module = strtolower($controller_classname);

        $locale = $this->compose_locale($language);

        $translationsMap = $this->loadTranslations($module, $locale);

        return function (string $key, ?string $default = null) use($translationsMap): string
        {
            if ($default === null) {
                $default = $key;
            }

            if (empty($key)) {
                return $default;
            }

            return $translationsMap[$key] ?? $default;
        };
    }

    private function loadTranslations(string $module, string $locale): array
    {
        $cacheKey = $module . '|' . $locale;

        if (isset($this->translationsCache[$cacheKey])) {
            return $this->translationsCache[$cacheKey];
        }

        $module = strtolower($module);
        $localeVariants = array_unique([
            $locale,
            strtolower($locale),
            str_replace('_', '-', $locale),
            str_replace('_', '', $locale),
        ]);

        $paths = [];
        $moduleTranslationRoot = APPPATH . 'modules/' . $module . '/translations/';
        $sharedTranslationRoot = APPPATH . 'modules/localizations/translations/' . $module . '/';
        $sharedFlatRoot = APPPATH . 'modules/localizations/translations/';

        foreach ($localeVariants as $variant) {
            $paths[] = $moduleTranslationRoot . $variant . '.php';
            $paths[] = $moduleTranslationRoot . $variant . '/';

            $paths[] = $sharedTranslationRoot . $variant . '.php';
            $paths[] = $sharedTranslationRoot . $variant . '/';

            $paths[] = $sharedFlatRoot . $module . '.' . $variant . '.php';
            $paths[] = $sharedFlatRoot . $module . '.' . $variant . '/';
        }

        $translations = [];

        foreach ($paths as $path) {
            if (is_dir($path)) {
                $files = glob($path . '*.php') ?: [];
                sort($files);

                foreach ($files as $file) {
                    $fileTranslations = include $file;

                    if (is_array($fileTranslations) && $fileTranslations !== []) {
                        $translations = array_replace($translations, $fileTranslations);
                    }
                }

                continue;
            }

            if (!is_file($path)) {
                continue;
            }

            $fileTranslations = include $path;

            if (is_array($fileTranslations) && $fileTranslations !== []) {
                $translations = array_replace($translations, $fileTranslations);
            }
        }

        return $this->translationsCache[$cacheKey] = $translations;
    }

    private function compose_locale(string $language): ?string
    {
        if (isset(self::LOCALE_MAPPINGS[$language])) {
            return self::LOCALE_MAPPINGS[$language];
        }

        $locale = Locale::composeLocale([
            'language' => Locale::getPrimaryLanguage($language),
            'script' => Locale::getScript($language),
            'region' => Locale::getRegion($language),
        ]);

        return $locale ?: null;
    }
}