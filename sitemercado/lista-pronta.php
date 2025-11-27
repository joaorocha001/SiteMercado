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
    <title>Sua Lista Otimizada</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="app-header">
        <a href="index.php" class="logo">
            <i class="fas fa-shopping-basket"></i> ListaFácil
        </a>
        <nav>
            <a href="index.php"><i class="fas fa-list"></i> Minhas Listas</a>
            <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a>
            <a href="#" id="btn-logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </nav>
    </header>

    <div class="container layout-lista-pronta">
        <main id="lista-principal">
            <h2 id="titulo-lista" style="margin-bottom: 1rem; color: var(--cor-primaria);">
                <i class="fas fa-clipboard-check"></i> Carregando...
            </h2>
            
            <div id="lista-container">
                </div>

            <div class="checkout-area">
                <h3 class="checkout-title">
                    <i class="fas fa-receipt"></i> Valor da Compra
                </h3>
                
                <div class="input-group-modern">
                    <span class="input-prefix">R$</span>
                    <input type="number" id="valor-total" placeholder="0,00" step="0.01">
                    <button onclick="salvarValor()" class="btn btn-primario" id="btn-salvar-valor">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
                <p id="msg-valor" class="msg-feedback"></p>
            </div>
        </main>

        <aside id="mapa-lateral">
            <h3 style="margin-bottom: 1rem;"><i class="fas fa-map-marked-alt"></i> Mapa do Mercado</h3>
            <img id="imagem-mapa" src="" alt="Mapa indisponível">
            <p style="margin-top:0.5rem; font-size:0.9rem; color:#666; text-align:center;">
                <i class="fas fa-info-circle"></i> Siga os corredores na ordem indicada.
            </p>
        </aside>
    </div>
    
    <script>
        let listaIdGlobal = null;

        window.onload = async () => {
            const params = new URLSearchParams(window.location.search);
            const listaId = params.get('lista_id');
            const supermercadoId = params.get('supermercado_id');
            const container = document.getElementById('lista-container');
            
            listaIdGlobal = listaId;

            if (!listaId || !supermercadoId) {
                container.innerHTML = '<p class="msg-feedback msg-erro">IDs inválidos.</p>';
                return;
            }

            try {
                const response = await fetch(`api/buscar_lista_otimizada.php?lista_id=${listaId}&supermercado_id=${supermercadoId}`);
                const data = await response.json();

                if (!data.sucesso) {
                    if(data.mensagem === 'Usuário não autenticado.') window.location.href = 'login.php';
                    container.innerHTML = `<p class="msg-feedback msg-erro">${data.mensagem}</p>`;
                    return;
                }

                // Preencher Info
                const infoLista = data.info_lista;
                document.getElementById('titulo-lista').innerHTML = `<i class="fas fa-clipboard-list"></i> ${infoLista.nome_lista}`;
                document.getElementById('imagem-mapa').src = `mapas/mercado-${supermercadoId}.png`;
                
                if (infoLista.valor_total) {
                    document.getElementById('valor-total').value = infoLista.valor_total;
                }

                // Renderizar Itens
                const itensOrdenados = data.itens;
                container.innerHTML = '';
                
                let secaoAtual = '';
                let htmlSecao = '';

                itensOrdenados.forEach(item => {
                    const nomeItem = item.nome_item_mestre || item.nome_item_personalizado;
                    const nomeSecao = item.nome_secao || 'Outros';
                    const numCorredor = item.numero_corredor ? `(Corredor ${item.numero_corredor})` : '';
                    const checked = item.comprado == 1 ? 'checked' : '';

                    if (nomeSecao !== secaoAtual) {
                        if (htmlSecao !== '') container.innerHTML += htmlSecao + '</div>';
                        secaoAtual = nomeSecao;
                        // Ícone dinâmico baseado na seção (opcional, aqui uso um genérico para simplificar)
                        htmlSecao = `<div class="secao-corredor">
                                        <h3><i class="fas fa-map-pin"></i> ${secaoAtual} <small>${numCorredor}</small></h3>`;
                    }
                    
                    htmlSecao += `<label class="item-lista">
                                    <input type="checkbox" ${checked}>
                                    <span>${nomeItem} <strong style="color:var(--cor-primaria)">x${item.quantidade}</strong></span>
                                 </label>`;
                });
                
                if (htmlSecao !== '') container.innerHTML += htmlSecao + '</div>';

            } catch (err) {
                console.error(err);
                container.innerHTML = '<p class="msg-feedback msg-erro">Erro ao carregar lista.</p>';
            }
        };

        async function salvarValor() {
            const inputValor = document.getElementById('valor-total');
            const msgEl = document.getElementById('msg-valor');
            const btn = document.getElementById('btn-salvar-valor');
            const valor = inputValor.value;

            if(valor === '') return;

            // Animação de loading no botão
            const conteudoOriginal = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            try {
                const response = await fetch('api/salvar_valor_compra.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ lista_id: listaIdGlobal, valor: valor })
                });
                const data = await response.json();

                if(data.sucesso) {
                    msgEl.innerText = "Valor salvo com sucesso!";
                    msgEl.className = "msg-feedback msg-sucesso";
                    
                    // Ícone de sucesso
                    btn.innerHTML = '<i class="fas fa-check"></i> Salvo';
                    btn.style.backgroundColor = 'var(--cor-sucesso)';
                    
                    setTimeout(() => { 
                        btn.innerHTML = conteudoOriginal; 
                        btn.style.backgroundColor = '';
                        btn.disabled = false;
                        msgEl.innerText = ""; 
                    }, 2500);
                } else {
                    throw new Error(data.mensagem);
                }
            } catch (error) {
                msgEl.innerText = "Erro ao salvar.";
                msgEl.className = "msg-feedback msg-erro";
                btn.innerHTML = conteudoOriginal;
                btn.disabled = false;
            }
        }

        document.getElementById('btn-logout').addEventListener('click', async (e) => {
            e.preventDefault();
            await fetch('api/logout.php');
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>