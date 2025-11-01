<?php

namespace config;

use mysqli;

class Database
{
    private static $host = 'localhost';
    private static $db_name = 'digitaco';
    private static $username = 'root';
    private static $password = '{senha do seu banco aqui}';
    private static $conn;

    public static function connect()
    {
        if (!self::$conn)
        {
            self::$conn = new mysqli(self::$host, self::$username, self::$password, self::$db_name);
            if (self::$conn->connect_error)
            {
                die(json_encode(["error" => "Conexão falhou: " . self::$conn->connect_error]));
            }
        }
        return self::$conn;
    }
}