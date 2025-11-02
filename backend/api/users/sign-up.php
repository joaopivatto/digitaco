<?php

use backend\dao\UsersDAO;

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../model/Users.php';
require_once __DIR__ . '/../../dao/UsersDAO.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$arquivo = file_get_contents("php://input");
$conteudo = json_decode($arquivo, true);

$name = trim($conteudo['name']);
$email = trim($conteudo['email']);
$password = $conteudo['password'];

try {
    $created = UsersDAO::create($name, $email, $password);

    if ($created) {
        http_response_code(201);
        echo json_encode([
            "message" => "Usuário criado com sucesso!",
            "name" => $name,
            "email" => $email
        ]);
    }
} catch (Throwable $e) {
    if (str_contains($e->getMessage(), 'Duplicate entry'))
    {
        http_response_code(409);
        echo json_encode([
            "message" => "E-mail já está em uso!"
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Erro interno no servidor.", "details" => $e->getMessage()]);
    }
}