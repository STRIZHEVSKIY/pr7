<?php
class FileHandler
{
    private string $uploadDir;

    public function __construct(string $uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function handleUpload(array $file): void
    {
        $uploadFile = $this->uploadDir . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            echo "Файл успешно загружен: " . htmlspecialchars(basename($file['name'])) . "<br>";
        } else {
            echo "Ошибка загрузки файла.";
        }
    }

    public function handleDownload(string $fileName): void
    {
        $filePath = $this->uploadDir . basename($fileName);

        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            echo "Файл не найден.";
        }
    }

    public function listFiles(): array
    {
        $files = scandir($this->uploadDir);
        return array_filter($files, fn($file) => $file !== '.' && $file !== '..');
    }
}

// Usage
$fileHandler = new FileHandler('./files/');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['userfile'])) {
    $fileHandler->handleUpload($_FILES['userfile']);
}

if (isset($_GET['download'])) {
    $fileHandler->handleDownload($_GET['download']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Загрузчик PDF</title>
</head>
<body>

<form enctype="multipart/form-data" action="" method="POST">
    <div>
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
        <br>
        <label for="file_field">Отправить этот файл:</label>
        <br>
        <input id="file_field" name="userfile" type="file" accept=".pdf"/>
    </div>
    <br>
    <input type="submit" value="Отправить файл"/>
</form>

<?php
$files = $fileHandler->listFiles();
if (empty($files)) {
    echo "<h2>Нет загруженных файлов</h2>";
} else {
    echo "<h2>Загруженные файлы</h2>";
    foreach ($files as $file) {
        echo "<a href='?download=" . urlencode($file) . "'>Скачать " . htmlspecialchars($file) . "</a><br>";
    }
}
?>

</body>
</html>