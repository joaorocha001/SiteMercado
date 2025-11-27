<?php
// api/criar_lista.php
include 'conexao.php';

// 1. Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit;
}
$usuario_id = $_SESSION['usuario_id'];

// 2. Pegar os dados do frontend
$dados = json_decode(file_get_contents('php://input'), true);

if (!$dados || !isset($dados['nome_lista']) || !isset($dados['itens']) || empty($dados['itens'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos ou lista vazia.']);
    exit;
}

$nome_lista = $dados['nome_lista'];
$itens = $dados['itens'];

// 3. Iniciar Transação
$conn->begin_transaction();

try {
    // 4. Criar a lista principal
    $stmt_lista = $conn->prepare("INSERT INTO Listas (usuario_id, nome_lista) VALUES (?, ?)");
    $stmt_lista->bind_param("is", $usuario_id, $nome_lista);
    $stmt_lista->execute();
    $lista_id = $conn->insert_id;
    
    // 5. Preparar o statement para inserir os itens (COM A COLUNA categoria_id)
    $stmt_item = $conn->prepare("INSERT INTO ItensDaLista (lista_id, item_mestre_id, nome_item_personalizado, quantidade, categoria_id) VALUES (?, ?, ?, ?, ?)");
    
    // 6. Loop para salvar cada item
    foreach ($itens as $item) {
        $nome_item_enviado = $item['nome'];
        $quantidade = $item['quantidade'];
        // AGORA RECEBEMOS O categoria_id DO FRONTEND
        $categoria_id = $item['categoria_id']; 
        
        $item_mestre_id = null;
        $nome_personalizado = null;

        // 7. Tentar achar o item no Mestre (opcional, mas bom para o futuro)
        $stmt_busca_mestre = $conn->prepare("SELECT id FROM ItensMestre WHERE nome_item = ?");
        $stmt_busca_mestre->bind_param("s", $nome_item_enviado);
        $stmt_busca_mestre->execute();
        $resultado_mestre = $stmt_busca_mestre->get_result();

        if ($resultado_mestre->num_rows > 0) {
            $item_mestre = $resultado_mestre->fetch_assoc();
            $item_mestre_id = $item_mestre['id'];
        } else {
            $nome_personalizado = $nome_item_enviado;
        }
        $stmt_busca_mestre->close();

        // 8. Inserir o item na tabela ItensDaLista
        // Note o "i" extra no bind_param para o categoria_id
        $stmt_item->bind_param("iissi", $lista_id, $item_mestre_id, $nome_personalizado, $quantidade, $categoria_id);
        $stmt_item->execute();
    }

    // 9. Se tudo deu certo, comitar
    $conn->commit();
    
    echo json_encode(['sucesso' => true, 'mensagem' => 'Lista salva com sucesso!', 'lista_id' => $lista_id]);

} catch (Exception $e) {
    // 10. Se algo deu errado, reverter
    $conn->rollback();
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar a lista: ' . $e->getMessage()]);
}

$stmt_lista->close();
$stmt_item->close();
$conn->close();
?>