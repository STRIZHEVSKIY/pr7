<?php
require_once '../helper.php';

class DatabaseOperations
{
    public static function read(string $table, array $columns = [], array $values = []): string
    {
        $query = "SELECT * FROM $table";

        if (!empty($columns)) {
            $conditions = [];
            for ($i = 0; $i < count($columns); $i++) {
                $conditions[] = "$columns[$i] = '" . self::escape($values[$i]) . "'";
            }
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $mysqli = self::openConnection();
        $result = $mysqli->query($query);
        $mysqli->close();

        return json_encode($result->fetch_all(MYSQLI_ASSOC));
    }

    public static function delete(string $table, $id): bool
    {
        $query = "DELETE FROM $table WHERE " . DatabaseConfig::ID_COLUMN . " = '" . self::escape($id) . "'";
        $mysqli = self::openConnection();
        $result = $mysqli->query($query);
        $mysqli->close();

        return $result;
    }

    public static function update(string $table, $id, array $columns, array $values): bool
    {
        $setClause = [];
        for ($i = 0; $i < count($columns); $i++) {
            $setClause[] = "$columns[$i] = '" . self::escape($values[$i]) . "'";
        }

        $query = "UPDATE $table SET " . implode(", ", $setClause) .
                 " WHERE " . DatabaseConfig::ID_COLUMN . " = '" . self::escape($id) . "'";

        $mysqli = self::openConnection();
        $result = $mysqli->query($query);
        $mysqli->close();

        return $result;
    }

    public static function insert(string $table, array $columns, array $values): bool
    {
        $columnsList = implode(", ", $columns);
        $valuesList = implode(", ", array_map(fn($value) => is_string($value) ? "'" . self::escape($value) . "'" : $value, $values));

        $query = "INSERT INTO $table ($columnsList) VALUES ($valuesList)";

        $mysqli = self::openConnection();
        $result = $mysqli->query($query);
        $mysqli->close();

        return $result;
    }

    private static function openConnection(): mysqli
    {
        return openMysqli();
    }

    private static function escape($value): string
    {
        $mysqli = self::openConnection();
        $escapedValue = $mysqli->real_escape_string($value);
        $mysqli->close();
        return $escapedValue;
    }
}
