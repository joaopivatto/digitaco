<?php

require_once __DIR__ . '/../../dao/LeaguesDAO.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';
require_once __DIR__ . '/../../utils/validate_session.php';

validateSession();
// GET /leagues?name={name}


use backend\dao\LeaguesDAO;
use dto\MessageResponseDTO;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$name = $_GET['name'] ?? null;
$userId = $_SESSION['userId'] ?? null;

try {
    $list = LeaguesDAO::findAllByName($userId, $name);
    http_response_code($list->getStatusCode());
    echo json_encode($list->jsonSerialize());
} catch (Throwable $e) {
    http_response_code(500);
    $response = new MessageResponseDTO($e->getMessage(), 500);
    echo json_encode($response->jsonSerialize());
}