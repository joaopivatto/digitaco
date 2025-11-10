<?php

namespace backend\dao;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../dto/MessageResponseDTO.php';
require_once __DIR__ . '/../dto/users/UsersDTO.php';

use backend\config\Database;
use dto\MessageResponseDTO;
use dto\users\UsersDTO;

class UsersDAO
{
    public static function create(string $name, string $email, string $password): MessageResponseDTO
    {
        if (self::validateExistentEmail($email)) {
            return new MessageResponseDTO("Email já está em uso!", 409);
        }

        $conn = Database::connect();
        $sql = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param("sss", $name, $email, $hash);
        $sql->execute();

        Database::close();
        return new MessageResponseDTO("Usuário criado com sucesso!", 201);
    }

    public static function updatePassword(string $email, string $newPassword): MessageResponseDTO
    {
        if (self::validateExistentEmail($email))
        {
            $conn = Database::connect();
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = $conn->prepare("UPDATE users SET password = ? where email = ?");
            $sql->bind_param("ss", $hash, $email);
            $sql->execute();
            Database::close();

            return new MessageResponseDTO("Senha alterada com sucesso!", 200);
        }
        return new MessageResponseDTO("Email não existe!", 400);
    }

    public static function validateExistentEmail(string $email): bool
    {
        $conn = Database::connect();

        //Verificação se e-mail existe
        $validate = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $validate->bind_param("s", $email);
        $validate->execute();
        $resultValidation = $validate->get_result();

        Database::close();
        return $resultValidation->num_rows !== 0;
    }

    public static function validateExistentUser(int $id): bool
    {
        $conn = Database::connect();

        //Verificação se user existe
        $validate = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $validate->bind_param("i", $id);
        $validate->execute();
        $resultValidation = $validate->get_result();

        Database::close();
        return $resultValidation->num_rows !== 0;
    }

    public static function authenticate(string $email, string $password): MessageResponseDTO {
        $conn = Database::connect();
        $sql = $conn->prepare("SELECT * FROM users WHERE email=?");
        $sql->bind_param("s",$email);
        $sql->execute();
        $res = $sql->get_result();
        if ($res && $row=$res->fetch_assoc()) {
            if (password_verify($password,$row['password'])) {
                Database::close();
                return new UsersDTO(
                    "Login realizado com sucesso!", 200 ,
                    $row['id'],$row['name'],$row['email']);
            }
        }
        Database::close();
        return new MessageResponseDTO("Credenciais Inválidas!", 401);
    }
}