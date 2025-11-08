<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../model/Users.php';
require_once __DIR__ . '/../../dao/UsersDAO.php';
require_once __DIR__ . '/../../dto/users/UsersDTO.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';

use backend\dao\UsersDAO;
use dto\MessageResponseDTO;
use dto\users\UsersDTO;

session_start();

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

try {
    $user = UsersDAO::authenticate($email, $password);
    if ($user instanceof UsersDTO) {
        $_SESSION['userId'] =  $user->getId();
        $_SESSION['userName'] = $user->getName();
    }
    http_response_code($user->getStatusCode());
    echo json_encode($user->jsonSerialize());
} catch (Throwable $e) {
    $response = new MessageResponseDTO($e->getMessage(), 500);
    http_response_code(500);
    echo json_encode($response->jsonSerialize());
}