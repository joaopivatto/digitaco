<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../model/Users.php';
require_once __DIR__ . '/../../dao/UsersDAO.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';

use backend\dao\UsersDAO;
use dto\MessageResponseDTO;

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
    http_response_code($created->getStatusCode());
    echo json_encode($created->jsonSerialize());
} catch (Throwable $e) {
    $response = new MessageResponseDTO($e->getMessage(), 500);
    http_response_code(500);
    echo json_encode($response->jsonSerialize());
}