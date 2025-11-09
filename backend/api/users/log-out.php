<?php

session_start();
session_destroy();

require_once __DIR__ . '/../../dto/MessageResponseDTO.php';

use dto\MessageResponseDTO;

$response = new MessageResponseDTO("Logout realizado com sucesso!", 200);
http_response_code($response->getStatusCode());
echo json_encode($response->jsonSerialize());