<?php

class UserPreferences {

    // Получить тему пользователя
    public function getTheme() {
        return isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light'; // по умолчанию тема светлая
    }

    // Установить тему пользователя
    public function setTheme($theme) {
        setcookie('theme', $theme, time() + 3600, '/');
    }

    // Получить язык пользователя
    public function getLanguage() {
        return isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'ru'; // по умолчанию русский
    }

    // Установить язык пользователя
    public function setLanguage($lang) {
        setcookie('lang', $lang, time() + 3600, '/');
    }
}
?>
