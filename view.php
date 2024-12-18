<?php

require_once 'helper.php';

class CityDetail
{
    private $db;

    public function __construct()
    {
        $this->db = $this->openDatabaseConnection();
    }

    private function openDatabaseConnection(): mysqli
    {
        return DatabaseConnection::open();
    }

    public function getCityById($id): ?array
    {
        $query = "SELECT * FROM " . DatabaseConfig::GOODS_TABLE . " WHERE " . DatabaseConfig::ID_COLUMN . " = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_assoc() : null;
    }

    public function render($id): void
    {
        $city = $this->getCityById($id);

        echo '<!DOCTYPE html>';
        echo '<html lang="en">';
        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '    <title>City Detail</title>';
        echo '</head>';
        echo '<body>';
        echo '    <h1>City Details</h1>';

        if ($city) {
            echo '<h2>' . htmlspecialchars($city[DatabaseConfig::TITLE_COLUMN]) . '</h2>';
            echo '<p>' . htmlspecialchars($city['description']) . '</p>';
            echo '<p>Температура: ' . htmlspecialchars($city['temperature']) . ' Градусов</p>';
        } else {
            echo '<p>City not found.</p>';
        }

        echo '    <a href="index.php">Back to list</a>';
        echo '</body>';
        echo '</html>';
    }
}

// Get the city ID from the URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $cityDetail = new CityDetail();
    $cityDetail->render($id);
} else {
    echo 'Invalid city ID.';
}
