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
    <title>Meu Perfil - ListaFácil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <header class="app-header">
        <a href="index.php" class="logo"><i class="fas fa-shopping-basket"></i> ListaFácil</a>
        <nav>
            <a href="index.php"><i class="fas fa-list"></i> Minhas Listas</a>
            <a href="perfil.php" class="active"><i class="fas fa-user"></i> Perfil</a>
            <a href="#" id="btn-logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </nav>
    </header>

    <div class="container">
        <h2 style="margin-bottom: 2rem;"><i class="fas fa-id-card"></i> Meu Perfil</h2>
        
        <form id="form-perfil">
            <h3><i class="fas fa-user-edit"></i> Alterar Dados</h3>
            <div style="margin-bottom: 0.5rem;">
                <label style="font-size: 0.9rem; color: #666;">Seu Nome:</label>
                <input type="text" id="perfil-nome" placeholder="Nome" required>
            </div>
            <div style="margin-bottom: 0.5rem;">
                <label style="font-size: 0.9rem; color: #666;">Seu E-mail:</label>
                <input type="email" id="perfil-email" placeholder="Email" required>
            </div>
            <button type="submit" class="btn btn-primario" style="margin-top: 0.5rem;">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
            <p id="msg-perfil" class="msg-feedback"></p>
        </form>

        <hr style="margin: 2.5rem 0; border: 0; border-top: 1px solid var(--cor-borda);">
        
        <form id="form-senha">
            <h3><i class="fas fa-lock"></i> Alterar Senha</h3>
            <input type="password" id="senha-atual" placeholder="Senha Atual" required>
            <input type="password" id="senha-nova" placeholder="Nova Senha" required>
            <input type="password" id="senha-confirma" placeholder="Confirmar Nova Senha" required>
            <button type="submit" class="btn btn-secundario" style="margin-top: 0.5rem;">
                <i class="fas fa-key"></i> Alterar Senha
            </button>
            <p id="msg-senha" class="msg-feedback"></p>
        </form>

        <hr style="margin: 2.5rem 0; border: 0; border-top: 1px solid var(--cor-borda);">
        
        <div style="background-color: #fff5f5; padding: 1.5rem; border-radius: 8px; border: 1px solid #f5c6cb;">
            <h3 style="color: var(--cor-erro);"><i class="fas fa-exclamation-triangle"></i> Zona de Perigo</h3>
            <p style="margin-bottom: 1rem; color: #721c24; font-size: 0.9rem;">
                Esta ação apagará sua conta e todas as suas listas permanentemente.
            </p>
            <button id="btn-deletar" class="btn btn-perigo">
                <i class="fas fa-trash-alt"></i> Apagar Minha Conta
            </button>
        </div>
    </div>
    
    <script>
        window.onload = async () => {
            const response = await fetch('api/gerenciar_perfil.php?acao=buscar');
            const data = await response.json();
            
            if (data.sucesso) {
                document.getElementById('perfil-nome').value = data.usuario.nome;
                document.getElementById('perfil-email').value = data.usuario.email;
            } else {
                window.location.href = 'login.php';
            }
        };

        document.getElementById('form-perfil').addEventListener('submit', async (e) => {
            e.preventDefault();
            const msgEl = document.getElementById('msg-perfil');
            msgEl.textContent = 'Salvando...';
            msgEl.className = 'msg-feedback';
            
            const response = await fetch('api/gerenciar_perfil.php?acao=atualizar', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    nome: document.getElementById('perfil-nome').value,
                    email: document.getElementById('perfil-email').value
                })
            });
            const data = await response.json();
            msgEl.textContent = data.mensagem;
            msgEl.className = data.sucesso ? 'msg-feedback msg-sucesso' : 'msg-feedback msg-erro';
        });

        document.getElementById('form-senha').addEventListener('submit', async (e) => {
            e.preventDefault();
            const msgEl = document.getElementById('msg-senha');
            const nova = document.getElementById('senha-nova').value;
            const confirma = document.getElementById('senha-confirma').value;

            if (nova !== confirma) {
                msgEl.textContent = 'As novas senhas não coincidem.';
                msgEl.className = 'msg-feedback msg-erro';
                return;
            }

            const response = await fetch('api/gerenciar_perfil.php?acao=mudar_senha', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    atual: document.getElementById('senha-atual').value,
                    nova: nova
                })
            });
            const data = await response.json();
            msgEl.textContent = data.mensagem;
            msgEl.className = data.sucesso ? 'msg-feedback msg-sucesso' : 'msg-feedback msg-erro';
            
            if (data.sucesso) {
                document.getElementById('form-senha').reset();
            }
        });

        document.getElementById('btn-deletar').addEventListener('click', async () => {
            if (confirm('TEM CERTEZA?\nEsta ação é irreversível e apagará todas as suas listas.')) {
                if (confirm('CONFIRMAÇÃO FINAL:\nRealmente deseja apagar sua conta?')) {
                    const response = await fetch('api/gerenciar_perfil.php?acao=deletar', { method: 'POST' });
                    const data = await response.json();
                    if (data.sucesso) {
                        alert('Conta apagada com sucesso.');
                        window.location.href = 'login.php';
                    } else {
                        alert(data.mensagem);
                    }
                }
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