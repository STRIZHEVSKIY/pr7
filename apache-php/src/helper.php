<?php
class DatabaseConfig
{
    public const HOST = 'db';
    public const USERS_TABLE = 'users';
    public const NAME_COLUMN = 'name';
    public const USER = 'user';
    public const PASSWORD = 'password';
    public const DATABASE = 'appDb';
    public const GOODS_TABLE = 'goods';
    public const ID_COLUMN = 'ID';
    public const TITLE_COLUMN = 'title';
}

class DatabaseConnection
{
    public static function open(): mysqli
    {
        return new mysqli(
            DatabaseConfig::HOST,
            DatabaseConfig::USER,
            DatabaseConfig::PASSWORD,
            DatabaseConfig::DATABASE
        );
    }
}