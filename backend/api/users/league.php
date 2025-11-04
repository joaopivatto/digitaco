<?php

use backend\dao\LeagueDAO;

require_once __DIR__ . '/../../dao/LeagueDAO.php';

session_start();
header('Content-Type: application/json');

$arquivo = file_get_contents('php://input');
$conteudo = json_decode($arquivo, true);

$idLeague = $_GET['leagueId'];
$password = $conteudo['password'];

if (empty($idLeague) || empty($password))
{
    http_response_code(422);
    echo json_encode([
        "message" => "Campos inválidos"
    ]);
    exit;
}

$insert = LeagueDAO::insertUserLeague($idLeague, $password);

try {
    if (!$insert['success'])
    {
        switch ($insert['reason'])
        {
            case "league_not_found":
                http_response_code(404);
                echo json_encode(["message" => "Liga não encontrada!"]);
                break;
            case "user_already_in_league":
                http_response_code(409);
                echo json_encode(["message" => "Usuário já está na liga!"]);
                break;
            case "league_password_incorrect":
                http_response_code(401);
                echo json_encode(["message" => "Senha da liga incorreta!"]);
                break;
        }
    } else {
        http_response_code(200);
        echo json_encode(["message" => "Bem-vindo à liga" . $insert['leagueName'] . "!"]);
    }
} catch (Throwable $e)
{
    http_response_code(500);
    echo json_encode(["error" => "Erro interno no servidor.", "details" => $e->getMessage()]);
}