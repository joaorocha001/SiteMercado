<?php
// api/buscar_supermercados.php
include 'conexao.php';

// Apenas para garantir que está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

$resultado = $conn->query("SELECT id, nome FROM Supermercados ORDER BY nome ASC");
$supermercados = $resultado->fetch_all(MYSQLI_ASSOC);

echo json_encode(['sucesso' => true, 'supermercados' => $supermercados]);

$conn->close();
?>