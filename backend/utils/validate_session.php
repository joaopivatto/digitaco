<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../dto/MessageResponseDTO.php';

use dto\MessageResponseDTO;

function validateSession() {
    if (!isset($_SESSION['userId']))
    {
        $response = new MessageResponseDTO("Acesso negado. Usuário não autenticado!", 401);
        http_response_code($response->getStatusCode());
        echo json_encode($response->jsonSerialize());
        exit;
    }
}