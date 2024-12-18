<?php
class FileUploadHandler
{
    private string $uploadDir;

    public function __construct(string $uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function handleUpload(array $file): void
    {
        $uploadFilePath = $this->uploadDir . basename($file['name']);

        echo '<pre>';
        setlocale(LC_ALL, 'en_US.UTF-8');

        $tempFile = $file['tmp_name'];
        $content = $this->readFileContent($tempFile);

        if (!$this->isPdf($content)) {
            echo "Вы попытались загрузить не PDF файл";
        } else {
            if ($this->moveFile($tempFile, $uploadFilePath)) {
                echo "Файл был успешно загружен.\n";
            } else {
                echo "Возможная атака с помощью файловой загрузки!\n";
            }
        }

        echo '</pre>';
    }

    private function readFileContent(string $filePath): string
    {
        $handle = fopen($filePath, 'rb');
        $content = fread($handle, filesize($filePath));
        fclose($handle);

        return $content;
    }

    private function isPdf(string $content): bool
    {
        return str_contains($content, '%PDF');
    }

    private function moveFile(string $tempFile, string $destination): bool
    {
        return move_uploaded_file($tempFile, $destination);
    }
}


$uploadHandler = new FileUploadHandler('/var/www/html-dynamic/pdf/files/');
$uploadHandler->handleUpload($_FILES['userfile']);
?>
<a href="files.php">К списку</a>