<?php
require_once 'methods.php';
require_once '../helper.php';

class Goods
{
    public function read(array $params): string
    {
        return read('goods', array_keys($params), array_values($params));
    }

    public function insert(array $params): string
    {
        return insert('goods', array_keys($params), array_values($params));
    }

    public function update(int $id, array $params): string
    {
        return update('goods', $id, array_keys($params), array_values($params));
    }

    public function delete(int $id): string
    {
        return delete('goods', $id);
    }
}

class RequestHandler
{
    private $goods;

    public function __construct(Goods $goods)
    {
        $this->goods = $goods;
    }

    public function handleRequest(): void
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->handleGet();
                break;
            case 'POST':
                $this->handlePost();
                break;
            case 'PATCH':
                $this->handlePatch();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
            default:
                http_response_code(405);
                echo "Method Not Allowed";
                break;
        }
    }

    private function handleGet(): void
    {
        $params = array_filter($_GET, "Validator::filterGoodsGetPatch", ARRAY_FILTER_USE_KEY);
        if (count($params) > 3) {
            http_response_code(400);
            echo "Неверный запрос";
            return;
        }
        echo $this->goods->read($params);
    }

    private function handlePost(): void
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $params = array_filter($body, "Validator::filterGoodsPost", ARRAY_FILTER_USE_KEY);
        if (count($params) != 3) {
            http_response_code(400);
            echo "Плохой запрос";
            return;
        }
        echo $this->goods->insert($params);
        http_response_code(201);
        echo "Товар успешно создан!!";
    }

    private function handlePatch(): void
    {
        $body = json_decode(file_get_contents('php://input'), true);
        $params = array_filter($body, "Validator::filterGoodsGetPatch", ARRAY_FILTER_USE_KEY);
        if (!array_key_exists('id', $params) || count($params) == 0 || count($params) > 3) {
            http_response_code(400);
            echo "Неверный запрос";
            return;
        }
        echo $this->goods->update($params['id'], $params);
        echo "Товар с ID " . $params['id'] . " успешно обновлен";
    }

    private function handleDelete(): void
    {
        $id = $_GET['id'];
        echo $this->goods->delete($id);
        http_response_code(200);
        echo "Товар с ID " . $id . " успешно удален";
    }
}

class Validator
{
    public static function filterGoodsGetPatch($key): bool
    {
        return in_array($key, ['id', 'title', 'cost', 'description']);
    }

    public static function filterGoodsPost($key): bool
    {
        return in_array($key, ['title', 'cost', 'description']);
    }
}


$goods = new Goods();
$requestHandler = new RequestHandler($goods);
$requestHandler->handleRequest();
