<?php
// api/buscar_itens_lista.php
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}
$usuario_id = $_SESSION['usuario_id'];
$lista_id = isset($_GET['lista_id']) ? intval($_GET['lista_id']) : 0;

// Consulta para buscar os itens da lista, verificando se o usuário é o dono
$stmt = $conn->prepare("
    SELECT 
        idl.id,
        idl.quantidade,
        idl.nome_item_personalizado,
        im.nome_item AS nome_item_mestre,
        cat.nome_categoria
    FROM ItensDaLista AS idl
    JOIN Listas AS l ON idl.lista_id = l.id
    LEFT JOIN ItensMestre AS im ON idl.item_mestre_id = im.id
    LEFT JOIN Categorias AS cat ON idl.categoria_id = cat.id
    WHERE l.id = ? AND l.usuario_id = ?
");
$stmt->bind_param("ii", $lista_id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$itens = $resultado->fetch_all(MYSQLI_ASSOC);

if ($itens) {
    echo json_encode(['sucesso' => true, 'itens' => $itens]);
} else {
    // Pode não achar itens ou o usuário não ser o dono
    echo json_encode(['sucesso' => false, 'mensagem' => 'Lista não encontrada ou vazia.']);
}

$stmt->close();
$conn->close();
?>