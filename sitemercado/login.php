<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Cadastro - ListaFácil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container-auth">
        
        <form id="form-login">
            <h3 style="color: var(--cor-primaria);">
                <i class="fas fa-user-circle"></i> Login
            </h3>
            <div style="position: relative;">
                <input type="email" id="login-email" placeholder="Seu e-mail" required>
            </div>
            <div style="position: relative;">
                <input type="password" id="login-senha" placeholder="Sua senha" required>
            </div>
            
            <button type="submit" class="btn btn-primario">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
            <p id="msg-login" class="msg-feedback"></p>
            
            <div class="auth-toggle-link">
                Não tem uma conta? <a id="btn-mostrar-cadastro">Cadastre-se</a>
            </div>
        </form>
        
        <form id="form-cadastro" style="display: none;">
            <h3 style="color: var(--cor-texto-suave);">
                <i class="fas fa-user-plus"></i> Criar Conta
            </h3>
            <input type="text" id="cadastro-nome" placeholder="Seu nome completo" required>
            <input type="email" id="cadastro-email" placeholder="Seu melhor e-mail" required>
            <input type="password" id="cadastro-senha" placeholder="Crie uma senha forte" required>
            
            <button type="submit" class="btn btn-secundario">
                <i class="fas fa-check"></i> Cadastrar
            </button>
            <p id="msg-cadastro" class="msg-feedback"></p>

            <div class="auth-toggle-link">
                Já tem uma conta? <a id="btn-mostrar-login">Faça login</a>
            </div>
        </form>
    </div>
    
    <script>
        const formLogin = document.getElementById('form-login');
        const formCadastro = document.getElementById('form-cadastro');
        const btnMostrarCadastro = document.getElementById('btn-mostrar-cadastro');
        const btnMostrarLogin = document.getElementById('btn-mostrar-login');

        btnMostrarCadastro.addEventListener('click', (e) => {
            e.preventDefault();
            formLogin.style.display = 'none';
            formCadastro.style.display = 'flex';
        });

        btnMostrarLogin.addEventListener('click', (e) => {
            e.preventDefault();
            formLogin.style.display = 'flex';
            formCadastro.style.display = 'none';
        });

        formCadastro.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msgEl = document.getElementById('msg-cadastro');
            msgEl.textContent = 'Processando...';
            msgEl.className = 'msg-feedback';
            
            try {
                const response = await fetch('api/registrar.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        nome: document.getElementById('cadastro-nome').value,
                        email: document.getElementById('cadastro-email').value,
                        senha: document.getElementById('cadastro-senha').value
                    })
                });
                const data = await response.json();
                
                msgEl.textContent = data.mensagem;
                if(data.sucesso) {
                    msgEl.className = 'msg-feedback msg-sucesso';
                    setTimeout(() => {
                        formLogin.style.display = 'flex';
                        formCadastro.style.display = 'none';
                        document.getElementById('login-email').value = document.getElementById('cadastro-email').value;
                        msgEl.textContent = '';
                    }, 1500);
                } else {
                    msgEl.className = 'msg-feedback msg-erro';
                }
            } catch (err) {
                msgEl.textContent = "Erro de conexão.";
                msgEl.className = 'msg-feedback msg-erro';
            }
        });

        formLogin.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msgEl = document.getElementById('msg-login');
            msgEl.textContent = 'Autenticando...';
            msgEl.className = 'msg-feedback';
            
            try {
                const response = await fetch('api/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        email: document.getElementById('login-email').value,
                        senha: document.getElementById('login-senha').value
                    })
                });
                const data = await response.json();

                if (data.sucesso) {
                    window.location.href = 'index.php';
                } else {
                    msgEl.textContent = data.mensagem;
                    msgEl.className = 'msg-feedback msg-erro';
                }
            } catch (err) {
                msgEl.textContent = "Erro de conexão.";
                msgEl.className = 'msg-feedback msg-erro';
            }
        });
    </script>
</body>
</html>