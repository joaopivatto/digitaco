<?php

session_start();
header('Content-Type: application/json');

function valiateSession()
{
    if (!isset($_SESSION['userId']))
    {
        http_response_code(401);
        echo json_encode(["success"=>false,"error"=>"Acesso negado. Usuário não autenticado."]);
        exit;
    }
}
