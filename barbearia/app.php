<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Agendamento - Barbearia Elite</title>
  <link rel="stylesheet" href="css/style.css">

  <!-- PROTEÇÃO DE LOGIN -->
  <script>
    if (!localStorage.getItem("usuarioLogado")) {
      window.location.href = "index.html";
    }
  </script>
</head>
<body>

<header>
  <h1>💈 Barbearia Elite</h1>
  <p>Agendamento de Horários</p>
</header>

<main>
  <!-- FORMULÁRIO -->
  <form id="formAgendamento">
    <h2>Novo Agendamento</h2>

    <input type="text" id="nome" placeholder="Nome do cliente" required>
    <input type="tel" id="telefone" placeholder="Telefone" required>

    <select id="servico" required>
      <option value="">Serviço</option>
      <option>Corte</option>
      <option>Barba</option>
      <option>Corte + Barba</option>
    </select>

    <select id="barbeiro" required>
      <option value="">Barbeiro</option>
      <option>João</option>
      <option>Carlos</option>
    </select>

    <input type="date" id="data" required>
    <input type="time" id="hora" required>

    <button type="submit">Agendar</button>

    <button type="button" onclick="logout()" style="margin-top:10px;background:#555;color:#fff">
      Sair
    </button>
  </form>

  <!-- AGENDA -->
  <section class="agenda">
    <h2>📅 Agenda</h2>
    <ul id="listaAgendamentos"></ul>
  </section>
</main>

<div id="toast"></div>

<script src="jss/app.js"></script>
</body>
</html>
