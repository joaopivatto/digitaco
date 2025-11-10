<?php

require_once __DIR__ . '/../../dao/LeagueLanguagesDAO.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';
require_once __DIR__ . '/../../utils/validate_session.php';

validateSession();
// DELETE /leagues/delete-language

use backend\dao\LeagueLanguagesDAO;
use dto\MessageResponseDTO;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$leagueId = $_GET['leagueId'] ?? '';
$language = $_GET['language'] ?? '';
$user = $_SESSION['userId'];

if (empty($leagueId) || empty($language)) {
    http_response_code(422);
    $response = new MessageResponseDTO("Campos Inválidos!", 422);
    echo json_encode($response->jsonSerialize());
    return;
}

try {
    $deleted = LeagueLanguagesDAO::deleteLanguage($leagueId, $language);
    http_response_code($deleted->getStatusCode());
    echo json_encode($deleted->jsonSerialize());
} catch (Throwable $e) {
    http_response_code(500);
    $response = new MessageResponseDTO($e->getMessage(), 500);
    echo json_encode($response->jsonSerialize());
}