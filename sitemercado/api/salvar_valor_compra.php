<?php
// api/salvar_valor_compra.php
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

$dados = json_decode(file_get_contents('php://input'), true);
$lista_id = $dados['lista_id'] ?? 0;
$valor = $dados['valor'] ?? null;

if (!$lista_id) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID da lista inválido.']);
    exit;
}

// Atualiza o valor na tabela Listas
$stmt = $conn->prepare("UPDATE Listas SET valor_total = ? WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("dii", $valor, $lista_id, $_SESSION['usuario_id']);

if ($stmt->execute()) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Valor salvo com sucesso!']);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar valor.']);
}

$stmt->close();
$conn->close();
?>