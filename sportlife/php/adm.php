<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Administrador | SportLife</title>
<style>
    :root {
        --primary-purple: #4f46e5;
        --bg-black: #0a0a0f;
        --card-gray: rgba(255, 255, 255, 0.03);
        --border: rgba(79, 70, 229, 0.15);
        --text-muted: #94a3b8;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
        background-color: var(--bg-black);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .glow {
        position: fixed;
        width: 400px;
        height: 400px;
        background: #3730a3;
        filter: blur(150px);
        border-radius: 50%;
        opacity: 0.1;
        z-index: -1;
    }

    .card {
        background: var(--card-gray);
        backdrop-filter: blur(20px);
        border: 1px solid var(--border);
        padding: 40px 30px;
        border-radius: 20px;
        width: 100%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }

    h1 {
        font-family: 'Bebas Neue', sans-serif;
        font-size: 3rem;
        margin-bottom: 15px;
        background: linear-gradient(to bottom, #fff, var(--primary-purple));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .input-group {
        margin: 15px 0;
        text-align: left;
    }

    .input-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 5px;
    }

    input {
        width: 100%;
        padding: 14px 18px;
        border-radius: 4px;
        border: 1px solid var(--border);
        background: rgba(255,255,255,0.02);
        color: white;
        font-size: 0.95rem;
        outline: none;
        transition: 0.3s cubic-bezier(0.4,0,0.2,1);
    }

    input:focus {
        border-color: var(--primary-purple);
        background: rgba(79,70,229,0.08);
        box-shadow: 0 0 15px rgba(79,70,229,0.2);
    }

    button {
        width: 100%;
        padding: 16px;
        margin-top: 20px;
        background: var(--primary-purple);
        border: none;
        border-radius: 4px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        transition: 0.4s;
        box-shadow: 0 4px 15px rgba(79,70,229,0.3);
    }

    button:hover {
        background: white;
        color: black;
        transform: translateY(-3px);
    }

    .note {
        margin-top: 15px;
        font-size: 0.8rem;
        color: var(--text-muted);
        text-align: center;
    }

    @media (max-width: 480px){
        .card {
            padding: 30px 20px;
        }

        h1 {
            font-size: 2.5rem;
        }
    }
</style>
</head>
<body>
<div class="glow" style="top:-100px; left:-100px;"></div>
<div class="glow" style="bottom:0; right:0;"></div>

<div class="card">
    <h1>Login Admin</h1>
    <form id="loginForm">
        <div class="input-group">
            <label for="username">Usuário</label>
            <input type="text" id="username" placeholder="Digite seu usuário" required>
        </div>
        <div class="input-group">
            <label for="password">Senha</label>
            <input type="password" id="password" placeholder="••••••••" required>
        </div>
        <button type="submit">Acessar</button>
    </form>
    <div class="note">
        Somente administradores podem acessar todas as opções da plataforma.
    </div>
</div>

<script>
const loginForm = document.getElementById('loginForm');

loginForm.addEventListener('submit', function(e){
    e.preventDefault();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();

    // Validação de administrador
    if(username === 'admin' && password === 'admin123'){
        localStorage.setItem('tipoUsuario', 'admin'); // salva como admin
        window.location.href = 'inicial.html'; // redireciona para home
    } else {
        alert('Usuário ou senha incorretos. Somente administradores podem acessar.');
    }
});
</script>
</body>
</html>