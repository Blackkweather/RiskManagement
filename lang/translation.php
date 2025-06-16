<?php
class Translation {
    private static $lang = 'fr';
    private static $translations = [];

    public static function load($lang) {
        self::$lang = $lang;
        $file = __DIR__ . "/$lang.php";
        if (file_exists($file)) {
            self::$translations = include $file;
        } else {
            self::$translations = [];
        }
    }

    public static function translate($key) {
        return self::$translations[$key] ?? $key;
    }
}

// Load default language
Translation::load('fr');

function __($key) {
    return Translation::translate($key);
}
?>
