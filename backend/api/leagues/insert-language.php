<?php

require_once __DIR__ . '/../../dao/LeagueLanguagesDAO.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';
require_once __DIR__ . '/../../utils/validate_session.php';

validateSession();
// POST /leagues/insert-language

use backend\dao\LeagueLanguagesDAO;
use dto\MessageResponseDTO;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$arquivo = file_get_contents("php://input");
$conteudo = json_decode($arquivo, true);

$leagueId = $conteudo['leagueId'] ?? '';
$language = $conteudo['language'] ?? '';
$user = $_SESSION['userId'];

if (empty($leagueId) || empty($language)) {
    http_response_code(422);
    $response = new MessageResponseDTO("Campos Inválidos!", 422);
    echo json_encode($response->jsonSerialize());
    return;
}

try {
    $created = LeagueLanguagesDAO::insertLanguage($leagueId, $language);
    http_response_code($created->getStatusCode());
    echo json_encode($created->jsonSerialize());
} catch (Throwable $e) {
    http_response_code(500);
    $response = new MessageResponseDTO($e->getMessage(), 500);
    echo json_encode($response->jsonSerialize());
}