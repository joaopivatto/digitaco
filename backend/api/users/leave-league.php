<?php

use backend\dao\LeaguesDAO;

require_once __DIR__ . "/../../dao/LeaguesDAO.php";
require_once __DIR__ . '/../../utils/validate_session.php';

validateSession();
header('Content-Type: application/json');

$arquivo = file_get_contents('php://input');
$conteudo = json_decode($arquivo, true);

$idLeague = $_GET['leagueId'];

if (empty($idLeague))
{
    http_response_code(422);
    echo json_encode([
        "message" => "Campos inválidos"
    ]);
    exit;
}

$delete = LeaguesDAO::deleteUserLeague($idLeague);

try {
    if (!$delete['success'])
    {
        switch ($delete['reason'])
        {
            case "league_not_found":
                http_response_code(404);
                echo json_encode(["message" => "Liga não encontrada!"]);
                break;
            case "user_not_already_in_league":
                http_response_code(409);
                echo json_encode(["message" => "Usuário não está na liga!"]);
                break;
        }
    } else {
        http_response_code(200);
        echo json_encode(["message" => "Você saiu da liga: " . $delete['leagueName'] . "!"]);
    }
} catch (Throwable $e)
{
    http_response_code(500);
    echo json_encode(["error" => "Erro interno no servidor.", "details" => $e->getMessage()]);
}