<?php
// api/buscar_listas.php
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}
$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT id, nome_lista, data_criacao FROM Listas WHERE usuario_id = ? ORDER BY data_criacao DESC LIMIT 10");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$listas = $resultado->fetch_all(MYSQLI_ASSOC);

echo json_encode(['sucesso' => true, 'listas' => $listas]);

$stmt->close();
$conn->close();
?>