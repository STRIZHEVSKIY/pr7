<?php
// Подключение к базе данных
require_once '../helper.php';

// Открытие соединения с базой данных
$mysqli = DatabaseConnection::open();

// Получение данных пользователей
$query = 'SELECT * FROM ' . DatabaseConfig::USERS_TABLE;
$result = $mysqli->query($query);

// Проверка на наличие данных
$users = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Закрытие соединения с базой данных
$mysqli->close();

// Подключаем шаблон для отображения данных
include 'admin_template.php';
?>
