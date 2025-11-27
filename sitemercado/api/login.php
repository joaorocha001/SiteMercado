<?php
// api/login.php
include 'conexao.php';

$dados = json_decode(file_get_contents('php://input'), true);

if (!$dados || !isset($dados['email']) || !isset($dados['senha'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos.']);
    exit;
}

$email = $dados['email'];
$senha_recebida = $dados['senha'];

// Buscar o usuário pelo email
$stmt = $conn->prepare("SELECT id, nome, senha_hash FROM Usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 1) {
    $usuario = $resultado->fetch_assoc();
    
    // Verificar a senha
    if (password_verify($senha_recebida, $usuario['senha_hash'])) {
        
        // Senha correta! Iniciar a sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        
        echo json_encode([
            'sucesso' => true, 
            'mensagem' => 'Login bem-sucedido!',
            'usuario_nome' => $usuario['nome']
        ]);
        
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Senha incorreta.']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não encontrado.']);
}

$stmt->close();
$conn->close();
?>