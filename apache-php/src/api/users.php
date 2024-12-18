<?php
require_once 'methods.php';
require_once '../helper.php';

class UserController
{
    public function handleRequest(): void
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->handleGetRequest();
                break;
            case 'POST':
                $this->handlePostRequest();
                break;
            case 'PATCH':
                $this->handlePatchRequest();
                break;
            case 'DELETE':
                $this->handleDeleteRequest();
                break;
            default:
                http_response_code(405);
                echo "Метод не поддерживается";
        }
    }

    private function handleGetRequest(): void
    {
        $params = array_filter($_GET, [self::class, 'filterUserGet'], ARRAY_FILTER_USE_KEY);
        if (count($params) > 2) {
            http_response_code(400);
            echo "Неверный запрос";
            return;
        }
        echo read(DatabaseConfig::USERS_TABLE, array_keys($params), array_values($params));
    }

    private function handlePostRequest(): void
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $params = array_filter($body, [self::class, 'filterUserPost'], ARRAY_FILTER_USE_KEY);
        if (count($params) != 2) {
            http_response_code(400);
            echo "Неверный запрос";
            return;
        }
        $params['password'] = base64_encode($params['password']);
        echo insert(DatabaseConfig::USERS_TABLE, array_keys($params), array_values($params));
        http_response_code(201);
        echo "Пользователь успешно создан!!!";
    }

    private function handlePatchRequest(): void
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $params = array_filter($body, [self::class, 'filterUserPatch'], ARRAY_FILTER_USE_KEY);
        if (!array_key_exists('id', $params) || count($params) == 0 || count($params) > 3) {
            http_response_code(400);
            echo "Неверный запрос";
            return;
        }
        if (array_key_exists('password', $params)) {
            $params['password'] = base64_encode($params['password']);
        }
        echo update(DatabaseConfig::USERS_TABLE, $params['id'], array_keys($params), array_values($params));
        echo "Пользователь с ID " . $params['id'] . " успешно обновлен";
    }

    private function handleDeleteRequest(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo "ID пользователя не указан";
            return;
        }
        echo delete(DatabaseConfig::GOODS_TABLE, $id);
        http_response_code(200);
        echo "Пользователь с ID " . $id . " успешно удален";
    }

    private static function filterUserGet($key): bool
    {
        return $key == 'id' || $key == 'name';
    }

    private static function filterUserPatch($key): bool
    {
        return $key == 'id' || $key == 'name' || $key == 'password';
    }

    private static function filterUserPost($key): bool
    {
        return $key == 'name' || $key == 'password';
    }
}

$controller = new UserController();
$controller->handleRequest();