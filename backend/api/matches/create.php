<?php

require_once __DIR__ . '/../../dao/MatchesDAO.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';
require_once __DIR__ . '/../../utils/validate_session.php';

validateSession();
// POST /matches

use backend\dao\MatchesDAO;
use dto\MessageResponseDTO;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$arquivo = file_get_contents("php://input");
$conteudo = json_decode($arquivo, true);

$points = $conteudo['points'] ?? '';
$words = $conteudo['words'] ?? '';
$leagueId = $conteudo['leagueId'] ?? null;
$userId = $_SESSION['userId'] ?? null;

if (empty($points) || empty($words)) {
    http_response_code(422);
    $response = new MessageResponseDTO("Campos Inválidos!", 422);
    echo json_encode($response->jsonSerialize());
    return;
}

try {
    $created = MatchesDAO::create($points, $words, $leagueId, $userId);
    http_response_code($created->getStatusCode());
    echo json_encode($created->jsonSerialize());
} catch (Throwable $e) {
    http_response_code(500);
    $response = new MessageResponseDTO($e->getMessage(), 500);
    echo json_encode($response->jsonSerialize());
}