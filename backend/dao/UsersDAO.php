<?php

namespace backend\dao;

use backend\config\Database;
use backend\model\Users;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Users.php';

class UsersDAO
{
    public static function create(string $name, string $email, string $password): bool
    {
        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param("sss", $name, $email, $hash);
        $result = $sql->execute();
        $conn->close();
        return $result;
    }

    public static function updatePassword(string $email, string $newPassword): bool
    {
        $conn = Database::connect();

        if (self::validateExistentEmail($email))
        {
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = $conn->prepare("UPDATE users SET password = ? where email = ?");
            $sql->bind_param("ss", $hash, $email);
            $conn->close();
            return $sql->execute();
        } else {
            $conn->close();
            return false;
        }
    }

    public static function validateExistentEmail($email)
    {
        $conn = Database::connect();

        //Verificação se e-mail existe
        $validate = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $validate->bind_param("s", $email);
        $validate->execute();
        $resultValidation = $validate->get_result();

        if ($resultValidation->num_rows === 0)
        {
            $conn->close();
            return false;
        }

        $conn->close();
        return true;
    }

    public static function validateExistentUser(int $id): bool
    {
        $conn = Database::connect();

        //Verificação se user existe
        $validate = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $validate->bind_param("i", $id);
        $validate->execute();
        $resultValidation = $validate->get_result();

        if ($resultValidation->num_rows === 0)
        {
            $conn->close();
            return false;
        }

        $conn->close();
        return true;
    }

    public static function authenticate(string $email, string $password): ?Users {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM users WHERE email=?");
        $sql->bind_param("s",$email);
        $sql->execute();
        $res = $sql->get_result();
        if ($res && $row=$res->fetch_assoc()) {
            if (password_verify($password,$row['password'])) {
                $conn->close();
                return new Users($row['id'],$row['name'],$row['email'],$row['password']);
            }
        }
        $conn->close();
        return null;
    }
}