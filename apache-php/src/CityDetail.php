<?php
require_once 'helper.php';

class CityDetail
{
    private $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::open();
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

        // Передача данных в шаблон
        if ($city) {
            include 'city_detail_template.php';
        } else {
            echo '<p>City not found.</p>';
        }
    }
}


if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; 
    $cityDetail = new CityDetail();
    $cityDetail->render($id);
} else {
    echo 'Invalid city ID.';
}
?>
