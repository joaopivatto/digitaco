<?php

session_start();

header('Content-Type: application/json');

use dao\UsersDAO;
use dto\UsersDTO;

//Lê tudo o que está na request http
$arquivo = file_get_contents('php://input');
$conteudo = json_decode($arquivo, true);

if (!is_array($conteudo)) {
    //http_response_code(400);
    echo json_encode([
        //response
    ]);
    exit;
}

if (empty($conteudo['email']) || empty($conteudo['password'])) {
    //http_response_code(400);
    echo json_encode([
        //
    ]);
    exit;
}

$email = (string) $conteudo['email'];
$password = (string) $conteudo['password'];

$user = UsersDAO::authenticate($email, $password);

if ($user) {
    // Cria sessão
    $_SESSION['userId'] = $user->id;
    $_SESSION['userName'] = $user->name;

    // Usa DTO para saída (esconder a senha)
    $userDTO = new UsersDTO($user);
    // user $userDTO->toArray()

    //http_response_code(200);
    echo json_encode([
        // response
    ]);
    exit;
} else {
    //http_response_code(401);
    echo json_encode([
        // response
    ]);
    exit;
}