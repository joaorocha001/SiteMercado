<?php
// api/logout.php
// Incluir conexao.php apenas para iniciar a sessão existente
include 'conexao.php';

// Destruir todos os dados da sessão
session_destroy();

// Retornar um JSON de sucesso (o frontend fará o redirecionamento)
echo json_encode(['sucesso' => true, 'mensagem' => 'Logout realizado.']);
?>