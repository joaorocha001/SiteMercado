<?php
// api/conexao.php

$servidor = "localhost";
$usuario = "root"; // Usuário padrão do XAMPP
$senha = "";       // Senha padrão do XAMPP
$banco = "lista_mercado_db";

// Criar a conexão
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Checar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Define o charset para UTF-8 (evita problemas com acentos)
$conn->set_charset("utf8");

// Define o cabeçalho para retornar JSON (para nossas APIs)
header('Content-Type: application/json');

// Iniciar a sessão (para login)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>