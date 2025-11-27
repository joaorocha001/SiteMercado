<?php
// api/gerenciar_perfil.php
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}
$usuario_id = $_SESSION['usuario_id'];
$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
$dados = json_decode(file_get_contents('php://input'), true);

switch ($acao) {
    // --- LER DADOS (READ) ---
    case 'buscar':
        $stmt = $conn->prepare("SELECT nome, email FROM Usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        echo json_encode(['sucesso' => true, 'usuario' => $usuario]);
        $stmt->close();
        break;

    // --- ATUALIZAR DADOS (UPDATE) ---
    case 'atualizar':
        $nome = $dados['nome'];
        $email = $dados['email'];
        
        $stmt = $conn->prepare("UPDATE Usuarios SET nome = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nome, $email, $usuario_id);
        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Perfil atualizado com sucesso!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar (e-mail pode já estar em uso).']);
        }
        $stmt->close();
        break;

    // --- MUDAR SENHA (UPDATE) ---
    case 'mudar_senha':
        $senha_atual = $dados['atual'];
        $senha_nova = $dados['nova'];
        
        // 1. Buscar hash da senha atual
        $stmt_busca = $conn->prepare("SELECT senha_hash FROM Usuarios WHERE id = ?");
        $stmt_busca->bind_param("i", $usuario_id);
        $stmt_busca->execute();
        $resultado = $stmt_busca->get_result();
        $usuario = $resultado->fetch_assoc();
        
        // 2. Verificar se a senha atual bate
        if (password_verify($senha_atual, $usuario['senha_hash'])) {
            // 3. Se bate, criar novo hash e atualizar
            $novo_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
            $stmt_update = $conn->prepare("UPDATE Usuarios SET senha_hash = ? WHERE id = ?");
            $stmt_update->bind_param("si", $novo_hash, $usuario_id);
            $stmt_update->execute();
            echo json_encode(['sucesso' => true, 'mensagem' => 'Senha alterada com sucesso!']);
            $stmt_update->close();
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'A senha atual está incorreta.']);
        }
        $stmt_busca->close();
        break;

    // --- DELETAR CONTA (DELETE) ---
    case 'deletar':
        $stmt = $conn->prepare("DELETE FROM Usuarios WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        if ($stmt->execute()) {
            session_destroy();
            echo json_encode(['sucesso' => true, 'mensagem' => 'Conta apagada.']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao apagar a conta.']);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação desconhecida.']);
        break;
}

$conn->close();
?>