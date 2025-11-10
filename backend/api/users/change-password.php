<?php

require_once __DIR__ . '/../../dao/UsersDAO.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';

use backend\dao\UsersDAO;
use dto\MessageResponseDTO;

header('Content-Type: application/json');

$arquivo = file_get_contents('php://input');
$conteudo = json_decode($arquivo, true);

if (empty($conteudo['email']) || empty($conteudo['password']) || empty($conteudo['confirmPassword']))
{
    $response = new MessageResponseDTO("Campos Inválidos!", 422);
    http_response_code($response->getStatusCode());
    echo json_encode($response->jsonSerialize());
    exit;
}

if ($conteudo['password'] !== $conteudo['confirmPassword'])
{
    $response = new MessageResponseDTO("Senhas diferentes!", 400);
    http_response_code($response->getStatusCode());
    echo json_encode($response->jsonSerialize());
    exit;
}

$email = (string) $conteudo['email'];
$password = (string) $conteudo['password'];

try {
    $change = UsersDAO::updatePassword($email, $password);
    http_response_code($change->getStatusCode());
    echo json_encode($change->jsonSerialize());
} catch (Throwable $e)
{
    http_response_code(500);
    $response = new MessageResponseDTO($e->getMessage(), 500);
    echo json_encode($response->jsonSerialize());
}