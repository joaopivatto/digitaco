<?php

session_start();
validateSession();
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../model/Users.php';
require_once __DIR__ . '/../../dao/UsersDAO.php';
require_once __DIR__ . '/../../dto/users/UsersDTO.php';

use backend\dao\UsersDAO;
use dto\users\UsersDTO;

//Lê tudo o que está na request http
$arquivo = file_get_contents('php://input');
$conteudo = json_decode($arquivo, true);

if (empty($conteudo['email']) || empty($conteudo['password'])) {
    http_response_code(422);
    echo json_encode([
        "message" => "Campos Inválidos!"
    ]);
    exit;
}

$email = (string) $conteudo['email'];
$password = (string) $conteudo['password'];

$user = UsersDAO::authenticate($email, $password);

try {
    if ($user) {
        $_SESSION['userId'] = $user->id;
        $_SESSION['userName'] = $user->name;

        $userDTO = new UsersDTO($user, 'Login realizado com sucesso!', 200);

        echo json_encode($userDTO->toArray());
        exit;
    } else {
        http_response_code(401);
        echo json_encode([
            "message" => "Credenciais inválidas!"
        ]);
        exit;
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erro interno no servidor.", "details" => $e->getMessage()]);
}