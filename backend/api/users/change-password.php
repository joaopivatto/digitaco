<?php

use backend\dao\UsersDAO;

require_once __DIR__ . '/../../dao/UsersDAO.php';

header('Content-Type: application/json');

$arquivo = file_get_contents('php://input');
$conteudo = json_decode($arquivo, true);

if (empty($conteudo['email']) || empty($conteudo['password']) || empty($conteudo['confirmPassword']))
{
    http_response_code(422);
    echo json_encode([
        "message" => "Campos inválidos"
    ]);
    exit;
}

if ($conteudo['password'] !== $conteudo['confirmPassword'])
{
    http_response_code(400);
    echo json_encode([
        "message" => "Senhas diferentes!"
    ]);
    exit;
}

$email = (string) $conteudo['email'];
$password = (string) $conteudo['password'];

$change = UsersDAO::updatePassword($email, $password);

try {
    if ($change) {
        http_response_code(200);
        echo json_encode(["message" => "Senha alterada com sucesso!"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Email não existe!"]);
    }
} catch (Throwable $e)
{
    http_response_code(500);
    echo json_encode(["error" => "Erro interno no servidor.", "details" => $e->getMessage()]);
}