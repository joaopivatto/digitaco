<?php

session_start();
// GET /leagues/points-weekly?id={id}

require_once __DIR__ . '/../../dao/LeaguesDAO.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';

use backend\dao\LeaguesDAO;
use dto\MessageResponseDTO;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$id = $_GET['id'] ?? null;
$userId = $_SESSION['userId'] ?? null;

if ($userId === null) {
    http_response_code(401);
    $response = new MessageResponseDTO("Não autorizado!", 401);
    echo json_encode($response->jsonSerialize());
    return;
}

try {
    $list = LeaguesDAO::getWeekRating($id);
    http_response_code($list->getStatusCode());
    echo json_encode($list->jsonSerialize());
} catch (Throwable $e) {
    http_response_code(500);
    $response = new MessageResponseDTO($e->getMessage(), 500);
    echo json_encode($response->jsonSerialize());
}