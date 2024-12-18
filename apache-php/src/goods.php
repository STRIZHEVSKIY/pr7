<?php
require_once 'helper.php';

class WeatherPage
{
    private $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::open();
    }

    public function fetchWeatherData(): array
    {
        $query = "SELECT * FROM " . DatabaseConfig::GOODS_TABLE;
        $result = $this->db->query($query);

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function render(): void
    {
        $weatherData = $this->fetchWeatherData();

        // Подключаем HTML шаблон
        include 'weather_template.php';
    }
}

$page = new WeatherPage();
$page->render();
?>
