<?php

session_start();
validateSession();
// GET /leagues/included

require_once __DIR__ . '/../../dao/LeaguesDAO.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';

use backend\dao\LeaguesDAO;
use dto\MessageResponseDTO;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$includedId = $_SESSION['userId'] ?? null;

if ($includedId === null) {
    http_response_code(401);
    $response = new MessageResponseDTO("Não autorizado!", 401);
    echo json_encode($response->jsonSerialize());
    return;
}

try {
    $list = LeaguesDAO::findAllByIncludedId($includedId);
    http_response_code($list->getStatusCode());
    echo json_encode($list->jsonSerialize());
} catch (Throwable $e) {
    http_response_code(500);
    $response = new MessageResponseDTO($e->getMessage(), 500);
    echo json_encode($response->jsonSerialize());
}