<?php
// api/buscar_categorias.php
include 'conexao.php';

// Apenas para garantir que está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

$resultado = $conn->query("SELECT id, nome_categoria FROM Categorias ORDER BY nome_categoria ASC");
$categorias = $resultado->fetch_all(MYSQLI_ASSOC);

echo json_encode(['sucesso' => true, 'categorias' => $categorias]);

$conn->close();
?>