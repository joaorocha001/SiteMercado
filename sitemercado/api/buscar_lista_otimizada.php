<?php
// api/buscar_lista_otimizada.php
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}

$lista_id = isset($_GET['lista_id']) ? intval($_GET['lista_id']) : 0;
$supermercado_id = isset($_GET['supermercado_id']) ? intval($_GET['supermercado_id']) : 0;

if ($lista_id == 0 || $supermercado_id == 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'IDs inválidos.']);
    exit;
}

// 1. Buscar dados da LISTA (Incluindo o novo valor_total)
$queryLista = "SELECT nome_lista, valor_total FROM Listas WHERE id = ?";
$stmtLista = $conn->prepare($queryLista);
$stmtLista->bind_param("i", $lista_id);
$stmtLista->execute();
$resLista = $stmtLista->get_result();
$dadosLista = $resLista->fetch_assoc();
$stmtLista->close();

// 2. Buscar ITENS (Query original)
$queryItens = "
    SELECT 
        idl.id,
        idl.comprado,
        idl.quantidade,
        idl.nome_item_personalizado,
        im.nome_item AS nome_item_mestre,
        cat.nome_categoria,
        cor.numero_corredor,
        cor.nome_secao
    FROM ItensDaLista AS idl
    JOIN Listas AS l ON idl.lista_id = l.id
    LEFT JOIN ItensMestre AS im ON idl.item_mestre_id = im.id
    LEFT JOIN Categorias AS cat ON idl.categoria_id = cat.id 
    LEFT JOIN MapeamentoCorredores AS map ON cat.id = map.categoria_id
    LEFT JOIN Corredores AS cor ON map.corredor_id = cor.id
    WHERE l.id = ? AND (cor.supermercado_id = ? OR cor.supermercado_id IS NULL)
    ORDER BY cor.numero_corredor ASC, cat.nome_categoria ASC;
";

$stmt = $conn->prepare($queryItens);
$stmt->bind_param("ii", $lista_id, $supermercado_id);
$stmt->execute();
$resultado = $stmt->get_result();
$itens = $resultado->fetch_all(MYSQLI_ASSOC); 

// Retorna tudo junto
echo json_encode([
    'sucesso' => true, 
    'itens' => $itens,
    'info_lista' => $dadosLista // Envia o valor_total aqui
]);

$stmt->close();
$conn->close();
?>