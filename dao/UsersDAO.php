<?php

namespace dao;

use Database;
use http\Client\Curl\User;
use model\Users;

class UsersDAO
{
    public static function create(string $name, string $email, string $password): bool
    {
        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param("sss", $name, $email, $hash);
        return $sql->execute();
    }

    public static function authenticate(string $email, string $password): ?User {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM users WHERE email=?");
        $sql->bind_param("s",$email);
        $sql->execute();
        $res = $sql->get_result();
        if ($res && $row=$res->fetch_assoc()) {
            if (password_verify($password,$row['password'])) {
                return new Users($row['id'],$row['name'],$row['email'],$row['password']);
            }
        }
        return null;
    }
}