const toast = document.getElementById("toast");

function mostrarToast(msg, cor = "#2ecc71") {
  toast.style.background = cor;
  toast.innerText = msg;
  toast.style.display = "block";
  setTimeout(() => toast.style.display = "none", 3000);
}

/* CADASTRO */
const formCadastro = document.getElementById("formCadastro");
if (formCadastro) {
  formCadastro.addEventListener("submit", e => {
    e.preventDefault();

    const nome = document.getElementById("nome").value;
    const email = document.getElementById("email").value;
    const senha = document.getElementById("senha").value;
    const confirmar = document.getElementById("confirmar").value;

    if (senha !== confirmar) {
      mostrarToast("Senhas não conferem ❌", "#e74c3c");
      return;
    }

    let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

    if (usuarios.some(u => u.email === email)) {
      mostrarToast("Email já cadastrado ❌", "#e74c3c");
      return;
    }

    usuarios.push({ nome, email, senha });
    localStorage.setItem("usuarios", JSON.stringify(usuarios));

    mostrarToast("Cadastro realizado ✅");
    setTimeout(() => window.location.href = "index.html", 1500);
  });
}

/* LOGIN */
const formLogin = document.getElementById("formLogin");
if (formLogin) {
  formLogin.addEventListener("submit", e => {
    e.preventDefault();

    const email = document.getElementById("email").value;
    const senha = document.getElementById("senha").value;

    let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

    const usuario = usuarios.find(u => u.email === email && u.senha === senha);

    if (!usuario) {
      mostrarToast("Login inválido ❌", "#e74c3c");
      return;
    }

    localStorage.setItem("usuarioLogado", JSON.stringify(usuario));
    mostrarToast("Login realizado ✅");

    setTimeout(() => window.location.href = "app.html", 1000);
  });
}
