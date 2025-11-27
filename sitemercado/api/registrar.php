<?php
// api/registrar.php
include 'conexao.php';

// Pega os dados enviados pelo frontend (em formato JSON)
$dados = json_decode(file_get_contents('php://input'), true);

if (!$dados || !isset($dados['nome']) || !isset($dados['email']) || !isset($dados['senha'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos.']);
    exit;
}

$nome = $dados['nome'];
$email = $dados['email'];
$senha = $dados['senha'];

// Criptografar a senha (MUITO IMPORTANTE)
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Usar prepared statements para evitar SQL Injection
$stmt = $conn->prepare("INSERT INTO Usuarios (nome, email, senha_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $senha_hash);

if ($stmt->execute()) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Usuário cadastrado com sucesso!']);
} else {
    // Verifica se é erro de email duplicado
    if ($conn->errno == 1062) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Este e-mail já está cadastrado.']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar: ' . $stmt->error]);
    }
}

$stmt->close();
$conn->close();
?>