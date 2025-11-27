<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Lista</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="app-header">
        <a href="index.php" class="logo"><i class="fas fa-shopping-basket"></i> ListaFácil</a>
        <nav>
            <a href="index.php"><i class="fas fa-list"></i> Minhas Listas</a>
            <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a>
            <a href="#" id="btn-logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </nav>
    </header>

    <div class="container">
        <h2 style="margin-bottom: 1.5rem;"><i class="fas fa-plus-circle"></i> Criar Nova Lista</h2>
        
        <form id="form-salvar-lista">
            <div style="margin-bottom: 2rem;">
                <label for="nome-lista" style="font-weight: 500;">Nome da Lista:</label>
                <input type="text" id="nome-lista" placeholder="Ex: Compras do Mês" required>
            </div>
            
            <h3 style="border-bottom: 2px solid var(--cor-borda); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                <i class="fas fa-cart-plus"></i> Adicionar Itens
            </h3>
            
            <div id="add-item-controls">
                <input type="text" id="nome-item" placeholder="Item (ex: Arroz)">
                
                <select id="categoria-item">
                    <option value="">Carregando...</option>
                </select>
                
                <input type="number" id="qtde-item" placeholder="Qtd" value="1" min="1">
                
                <button type="button" id="btn-add-item" class="btn btn-secundario">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
            
            <div id="itens-lista-container">
                </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button type="submit" id="btn-salvar-lista" class="btn btn-primario">
                    <i class="fas fa-save"></i> Finalizar e Salvar
                </button>
            </div>
        </form>
        <p id="msg-salvar" class="msg-feedback" style="text-align: right;"></p>

    </div>
    
    <script>
        let itensDaLista = []; 
        let categoriasDisponiveis = []; 

        const selectCategoria = document.getElementById('categoria-item');
        const nomeItemEl = document.getElementById('nome-item');
        const qtdeItemEl = document.getElementById('qtde-item');

        window.onload = async () => {
            const response = await fetch('api/buscar_categorias.php');
            const data = await response.json();

            if (data.sucesso) {
                categoriasDisponiveis = data.categorias; 
                selectCategoria.innerHTML = '<option value="">-- Selecione a Categoria --</option>';
                data.categorias.forEach(cat => {
                    selectCategoria.innerHTML += `<option value="${cat.id}">${cat.nome_categoria}</option>`;
                });
            } else {
                if(data.mensagem === 'Usuário não autenticado.') window.location.href = 'login.php';
            }
        };

        document.getElementById('btn-add-item').addEventListener('click', () => {
            const nome = nomeItemEl.value.trim();
            const qtde = qtdeItemEl.value.trim();
            const categoriaId = selectCategoria.value;
            const categoriaEl = selectCategoria.options[selectCategoria.selectedIndex];
            
            if (!nome || !categoriaId) {
                alert('Preencha o nome do item e selecione uma categoria.');
                return;
            }

            const item = {
                id: Date.now(), 
                nome: nome,
                quantidade: qtde || '1',
                categoria_id: categoriaId,
                categoria_nome: categoriaEl.text
            };
            
            itensDaLista.push(item);
            renderizarItens();

            // Limpar campos e focar
            nomeItemEl.value = '';
            qtdeItemEl.value = '1';
            selectCategoria.value = ''; 
            nomeItemEl.focus();
        });

        function renderizarItens() {
            const container = document.getElementById('itens-lista-container');
            container.innerHTML = '';
            
            if (itensDaLista.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 8px; color: #6c757d;">
                        <i class="fas fa-basket-shopping" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                        <p>Sua lista está vazia. Adicione itens acima!</p>
                    </div>`;
                return;
            }
            
            itensDaLista.forEach(item => {
                container.innerHTML += `
                    <div class="item-adicionado">
                        <div class="item-info">
                            <strong>${item.nome}</strong>
                            <small>${item.categoria_nome}</small>
                        </div>
                        
                        <input type="number" value="${item.quantidade}" min="1"
                               style="width: 70px; text-align: center;"
                               onchange="atualizarQuantidade(${item.id}, this.value)">
                        
                        <div class="btn-icon-group">
                            <button type="button" class="btn-acao btn-editar" title="Editar" onclick="editarItem(${item.id})">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button type="button" class="btn-acao btn-remover" title="Remover" onclick="removerItem(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
        }

        function atualizarQuantidade(id, novaQuantidade) {
            const item = itensDaLista.find(i => i.id === id);
            if (item) item.quantidade = novaQuantidade;
        }

        function editarItem(id) {
            const itemIndex = itensDaLista.findIndex(i => i.id === id);
            if (itemIndex > -1) {
                const item = itensDaLista[itemIndex];
                nomeItemEl.value = item.nome;
                qtdeItemEl.value = item.quantidade;
                selectCategoria.value = item.categoria_id;
                
                itensDaLista.splice(itemIndex, 1);
                renderizarItens();
                nomeItemEl.focus();
            }
        }

        function removerItem(id) {
            itensDaLista = itensDaLista.filter(item => item.id !== id);
            renderizarItens();
        }
        
        document.getElementById('form-salvar-lista').addEventListener('submit', async (e) => {
            e.preventDefault();
            const msgEl = document.getElementById('msg-salvar');
            const btnSalvar = document.getElementById('btn-salvar-lista');
            
            if (itensDaLista.length === 0) {
                alert('Adicione pelo menos um item à lista.');
                return;
            }

            msgEl.textContent = 'Salvando...';
            btnSalvar.disabled = true;
            btnSalvar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';

            const response = await fetch('api/criar_lista.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    nome_lista: document.getElementById('nome-lista').value,
                    itens: itensDaLista.map(item => ({
                        nome: item.nome,
                        quantidade: item.quantidade,
                        categoria_id: item.categoria_id
                    })) 
                })
            });
            const data = await response.json();

            if (data.sucesso) {
                msgEl.textContent = 'Sucesso! Redirecionando...';
                msgEl.className = 'msg-feedback msg-sucesso';
                setTimeout(() => { window.location.href = 'index.php'; }, 1000);
            } else {
                msgEl.textContent = data.mensagem;
                msgEl.className = 'msg-feedback msg-erro';
                btnSalvar.disabled = false;
                btnSalvar.innerHTML = '<i class="fas fa-save"></i> Finalizar e Salvar';
            }
        });

        document.getElementById('btn-logout').addEventListener('click', async (e) => {
            e.preventDefault();
            await fetch('api/logout.php');
            window.location.href = 'login.php';
        });
        
        renderizarItens(); 
    </script>
</body>
</html>