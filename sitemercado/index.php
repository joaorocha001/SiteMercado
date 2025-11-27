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
    <title>Minhas Listas - ListaFácil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <header class="app-header">
        <a href="index.php" class="logo"><i class="fas fa-shopping-basket"></i> ListaFácil</a>
        <nav>
            <a href="index.php" class="active"><i class="fas fa-list"></i> Minhas Listas</a>
            <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a>
            <a href="#" id="btn-logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </nav>
    </header>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h2 id="saudacao"><i class="fas fa-folder-open"></i> Minhas Listas</h2>
            <a href="criar-lista.php" class="btn btn-primario">
                <i class="fas fa-plus"></i> Criar Nova Lista
            </a>
        </div>
        
        <hr style="margin: 1.5rem 0; border: 0; border-top: 1px solid var(--cor-borda);">

        <section id="listas-antigas">
            <div id="container-listas" style="margin-top: 1rem; display:flex; flex-direction: column; gap: 0.8rem;">
                <p style="text-align: center; color: #777;"><i class="fas fa-spinner fa-spin"></i> Carregando listas...</p>
            </div>
        </section>
    </div>

    <script>
        window.onload = async () => {
            try {
                const response = await fetch('api/buscar_listas.php');
                const data = await response.json();
                const container = document.getElementById('container-listas');

                if (data.sucesso) {
                    if (data.listas.length > 0) {
                        container.innerHTML = ''; 
                        data.listas.forEach(lista => {
                            const dataFormatada = new Date(lista.data_criacao).toLocaleDateString('pt-BR');
                            // Valor formatado (se existir)
                            const valorTexto = lista.valor_total 
                                ? `<span style="color:var(--cor-sucesso); font-weight:bold; float:right;">R$ ${parseFloat(lista.valor_total).toFixed(2).replace('.', ',')}</span>` 
                                : '';

                            container.innerHTML += `
                                <a href="ver-lista.php?lista_id=${lista.id}&nome_lista=${encodeURIComponent(lista.nome_lista)}" class="card-lista">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <h4><i class="fas fa-clipboard-list" style="margin-right:8px; color:var(--cor-primaria);"></i> ${lista.nome_lista}</h4>
                                        <i class="fas fa-chevron-right" style="color: #ccc;"></i>
                                    </div>
                                    <div style="margin-top: 5px; color: #666; font-size: 0.9rem;">
                                        <i class="far fa-calendar-alt"></i> ${dataFormatada}
                                        ${valorTexto}
                                    </div>
                                </a>
                            `;
                        });
                    } else {
                        container.innerHTML = `
                            <div style="text-align: center; padding: 3rem; background: #f9f9f9; border-radius: 8px; color: #888;">
                                <i class="far fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                                <p>Você ainda não tem nenhuma lista.</p>
                                <p style="font-size: 0.9rem;">Clique em "Criar Nova Lista" para começar!</p>
                            </div>
                        `;
                    }
                } else {
                    window.location.href = 'login.php';
                }
            } catch (err) {
                console.error(err);
            }
        };

        document.getElementById('btn-logout').addEventListener('click', async (e) => {
            e.preventDefault();
            await fetch('api/logout.php');
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>