<?php

namespace backend\config;

use mysqli;

class Database
{
    const HOST = 'localhost';
    const DATABASE_NAME = 'digitaco';
    const USERNAME = 'root';
    const PASSWORD = '';
    private static $host = 'localhost';
    private static $db_name = 'digitaco';
    private static $username = 'root';
    private static $password = '';
    private static $conn;

    public static function connect($dataBaseName = null)
    {
        if (!self::$conn)
        {
            self::$conn = new mysqli(self::$host, self::$username, self::$password, $dataBaseName ?? self::$db_name);
            if (self::$conn->connect_error)
            {
                self::$conn->close();
                die(json_encode(["error" => "Conexão falhou: " . self::$conn->connect_error]));
            }
        }
        return self::$conn;
    }

    public static function close()
    {
        if (self::$conn)
        {
            self::$conn->close();
            self::$conn = null;
        }
    }
}