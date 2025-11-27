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
    <title>Visualizar Lista - ListaFácil</title>
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
        <h2 id="nome-lista-titulo" style="margin-bottom: 1.5rem; color: var(--cor-primaria);">
            <i class="fas fa-spinner fa-spin"></i> Carregando...
        </h2>
        
        <form id="form-otimizar" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--cor-borda);">
            <h4 style="margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-magic" style="color: purple;"></i> Otimizar Rota
            </h4>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <select id="select-supermercado" required>
                        <option value="">Carregando mercados...</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primario">
                    <i class="fas fa-map-marked-alt"></i> Gerar Mapa
                </button>
            </div>
            <p style="margin-top: 0.5rem; font-size: 0.85rem; color: #666;">
                <i class="fas fa-info-circle"></i> Escolha um mercado para organizar seus itens por corredores.
            </p>
        </form>

        <hr style="margin: 2rem 0; border: 0; border-top: 1px solid var(--cor-borda);">

        <h3><i class="fas fa-layer-group"></i> Itens na Lista</h3>
        <div id="itens-lista-container" style="margin-top: 1rem;">
            </div>
    </div>

    <script>
        const params = new URLSearchParams(window.location.search);
        const listaId = params.get('lista_id');
        const nomeLista = params.get('nome_lista');

        const tituloEl = document.getElementById('nome-lista-titulo');
        const selectMercado = document.getElementById('select-supermercado');
        const containerItens = document.getElementById('itens-lista-container');

        window.onload = async () => {
            if (!listaId) {
                tituloEl.textContent = 'Erro: Lista não encontrada.';
                return;
            }
            
            tituloEl.innerHTML = `<i class="fas fa-clipboard-list"></i> ${nomeLista || 'Minha Lista'}`;

            const promessaMercados = carregarMercados();
            const promessaItens = carregarItens();
            
            await Promise.all([promessaMercados, promessaItens]);
        };

        async function carregarMercados() {
            try {
                const response = await fetch('api/buscar_supermercados.php');
                const data = await response.json();
                if (data.sucesso) {
                    selectMercado.innerHTML = '<option value="">-- Selecione um mercado --</option>';
                    data.supermercados.forEach(mercado => {
                        selectMercado.innerHTML += `<option value="${mercado.id}">${mercado.nome}</option>`;
                    });
                } else {
                    selectMercado.innerHTML = '<option value="">Erro ao carregar</option>';
                }
            } catch (e) { console.error(e); }
        }

        async function carregarItens() {
            try {
                const response = await fetch(`api/buscar_itens_lista.php?lista_id=${listaId}`);
                const data = await response.json();
                
                if (data.sucesso && data.itens.length > 0) {
                    containerItens.innerHTML = ''; 
                    data.itens.forEach(item => {
                        const nome = item.nome_item_mestre || item.nome_item_personalizado;
                        const cat = item.nome_categoria || 'Sem Categoria';
                        
                        // Layout melhorado igual ao criar-lista
                        containerItens.innerHTML += `
                            <div class="item-adicionado" style="background: white;">
                                <div class="item-info">
                                    <strong>${nome}</strong>
                                    <small><i class="fas fa-tag" style="font-size:0.8rem; margin-right:4px;"></i> ${cat}</small>
                                </div>
                                <span style="background: var(--cor-fundo); padding: 5px 10px; border-radius: 4px; font-weight: bold;">
                                    x${item.quantidade}
                                </span>
                            </div>
                        `;
                    });
                } else {
                    containerItens.innerHTML = `
                        <p style="text-align: center; color: #888; padding: 2rem;">
                            <i class="fas fa-box-open" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                            Nenhum item encontrado nesta lista.
                        </p>`;
                }
            } catch (e) { console.error(e); }
        }

        document.getElementById('form-otimizar').addEventListener('submit', (e) => {
            e.preventDefault();
            const supermercadoId = selectMercado.value;
            if (supermercadoId) {
                window.location.href = `lista-pronta.php?lista_id=${listaId}&supermercado_id=${supermercadoId}`;
            } else {
                alert('Por favor, selecione um supermercado.');
            }
        });

        document.getElementById('btn-logout').addEventListener('click', async (e) => {
            e.preventDefault();
            await fetch('api/logout.php');
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>