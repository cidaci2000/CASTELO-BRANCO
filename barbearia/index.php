<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login - Barbearia Elite</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
  <h1>💈 Barbearia Elite</h1>
  <p>Entrar</p>
</header>

<main>
  <form id="formLogin">
    <h2>Login</h2>

    <input type="email" id="email" placeholder="Email" required>
    <input type="password" id="senha" placeholder="Senha" required>

    <button type="submit">Entrar</button>

    <p>Não tem conta? <a href="cadastro.php">Cadastrar</a></p>
  </form>
</main>

<div id="toast"></div>

<script src="jss/script.js"></script>
</body>
</html>
