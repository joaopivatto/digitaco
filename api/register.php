<?php

use dao\UsersDAO;


require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Users.php';
require_once __DIR__ . '/../dao/UsersDAO.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$arquivo = file_get_contents("php://input");
$conteudo = json_decode($arquivo, true);

$name = trim($conteudo['name']);
$email = trim($conteudo['email']);
$senha = $conteudo['password'];

try {
    $created = UsersDAO::create($name, $email, $senha);

    if ($created) {
        http_response_code(201);
        echo json_encode(["message" => "Usuário criado com sucesso!"]);
    } else {
        http_response_code(409);
        echo json_encode(["error" => "E-mail já cadastrado."]);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro interno no servidor.", "details" => $e->getMessage()]);
}