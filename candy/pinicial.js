// Variável para verificar se o usuário está logado
let logado = false;

// Função para quando o botão de login for clicado
document.getElementById("loginBtn").onclick = () => {
  logado = true;
  alert("Login realizado 💗");
};

// Função para quando o botão de carrinho for clicado
document.getElementById("cartBtn").onclick = () => {
  if (!logado) {
    alert("Faça login para acessar as Receitas 🍰");
  } else {
    alert("Abrindo Receitas 🛒");
  }
};

// Função para os botões "Ver Receita"
let botoes = document.querySelectorAll(".card button");
botoes.forEach(btn => {
  btn.onclick = () => {
    if (!logado) {
      alert("Faça login para adicionar receitas as Receitas 🍰");
    } else {
      alert("Receita adicionada a Receitas  🛒");
    }
  };
});
