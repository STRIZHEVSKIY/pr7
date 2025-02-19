<?php
session_start();

require_once __DIR__ . '/UserPreferences.php'; // Убедитесь, что путь к файлу правильный

// Создание экземпляра UserPreferences
$userPreferences = new UserPreferences();

// Устанавливаем тему в зависимости от предпочтений пользователя
$themeStyleSheet = $userPreferences->getTheme() === 'dark' ? 'css/dark_theme.css' : 'css/light_theme.css';

// Устанавливаем язык в зависимости от предпочтений пользователя
$lang = $userPreferences->getLanguage();
?>
<html lang="EN">
<head>
    <meta charset="UTF-8">
    <title>Адаптив</title>
    <link href="<?php echo $themeStyleSheet;?>" rel="stylesheet" id="theme-link">
</head>
<body>
<?php
// Загружаем соответствующий файл с языковыми данными
if ($lang == "ru"):
    include "lang/ru/page.php";
else:
    include "lang/en/page.php";
endif;
?>

<script>
    const THEME_BTN = document.querySelector(".theme-toggle");
    const STYLESHEET_LINK = document.querySelector("#theme-link");
    const LANG_BTN = document.querySelector(".lang-toggle");
    const NAME_FIELD = document.querySelector("#name_field");
    const SET_NAME_BTN = document.querySelector(".set-name-button");
    const NAME_SPAN = document.querySelector("#name_span");

    let currentTheme = getCookie("theme");
    let currentLanguage = getCookie("lang");
    let username = getCookie("name");

    if (currentTheme === "dark") {
        STYLESHEET_LINK.href = "css/dark_theme.css";
    } else {
        STYLESHEET_LINK.href = "css/light_theme.css";
    }

    if (username !== undefined) {
        NAME_SPAN.innerHTML = username;
    }

    THEME_BTN.addEventListener("click", function () {
        if (currentTheme === "light") {
            currentTheme = "dark";
            STYLESHEET_LINK.href = "css/dark_theme.css";
        } else {
            currentTheme = "light";
            STYLESHEET_LINK.href = "css/light_theme.css";
        }
        setCookie("theme", currentTheme);
    });

    LANG_BTN.addEventListener("click", function () {
        if (currentLanguage === "ru") {
            currentLanguage = "en";
        } else {
            currentLanguage = "ru";
        }
        setCookie("lang", currentLanguage);
        location.reload();
    });

    SET_NAME_BTN.addEventListener("click", function () {
        let new_username = NAME_FIELD.value;
        NAME_FIELD.value = '';
        if (new_username !== "") {
            username = new_username;
            NAME_SPAN.innerHTML = username;
            setCookie("name", new_username);
        }
    });

    function getCookie(name) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    function setCookie(name, value, options = {}) {
        options = {
            path: '/',
            ...options
        };

        if (options.expires instanceof Date) {
            options.expires = options.expires.toUTCString();
        }

        let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

        for (let optionKey in options) {
            updatedCookie += "; " + optionKey;
            let optionValue = options[optionKey];
            if (optionValue !== true) {
                updatedCookie += "=" + optionValue;
            }
        }

        document.cookie = updatedCookie;
    }
</script>
</body>
</html>
