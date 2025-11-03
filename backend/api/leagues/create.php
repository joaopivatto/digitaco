<?php

session_start();
// POST /leagues

require_once __DIR__ . '/../../dao/LeaguesDAO.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';

use backend\dao\LeaguesDAO;
use dto\MessageResponseDTO;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$arquivo = file_get_contents("php://input");
$conteudo = json_decode($arquivo, true);

$name = $conteudo['name'] ?? '';
$password = $conteudo['password'] ?? '';
$creatorId = $_SESSION['userId'] ?? null;

if ($creatorId === null) {
    http_response_code(401);
    $response = new MessageResponseDTO("Não autorizado!", 401);
    echo json_encode($response->jsonSerialize());
    return;
}

if (empty($name) || empty($password)) {
    http_response_code(422);
    $response = new MessageResponseDTO("Campos Inválidos!", 422);
    echo json_encode($response->jsonSerialize());
    return;
}

try {
    $created = LeaguesDAO::create($name, $password, $creatorId);
    http_response_code($created->getStatusCode());
    echo json_encode($created->jsonSerialize());
} catch (Throwable $e) {
    http_response_code(500);
    $response = new MessageResponseDTO($e->getMessage(), 500);
    echo json_encode($response->jsonSerialize());
}