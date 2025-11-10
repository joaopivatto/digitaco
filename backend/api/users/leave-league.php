<?php

require_once __DIR__ . "/../../dao/LeagueUserDAO.php";
require_once __DIR__ . '/../../utils/validate_session.php';
require_once __DIR__ . '/../../dto/MessageResponseDTO.php';

use backend\dao\LeagueUserDAO;
use dto\MessageResponseDTO;

validateSession();
header('Content-Type: application/json');

$arquivo = file_get_contents('php://input');
$conteudo = json_decode($arquivo, true);

$idLeague = $_GET['leagueId'];

if (empty($idLeague))
{
    $response = new MessageResponseDTO("Campos Inválidos!", 422);
    echo json_encode($response->jsonSerialize());
    exit;
}


try {
    $delete = LeagueUserDAO::deleteLeagueUser($idLeague);
    http_response_code($delete->getStatusCode());
    echo json_encode($delete->jsonSerialize());
} catch (Throwable $e)
{
    http_response_code(500);
    $response = new MessageResponseDTO($e->getMessage(), 500);
    echo json_encode($response->jsonSerialize());
}