const form = document.getElementById("formAgendamento");
const lista = document.getElementById("listaAgendamentos");
const toast = document.getElementById("toast");
const dataInput = document.getElementById("data");

dataInput.min = new Date().toISOString().split("T")[0];

let agendamentos = JSON.parse(localStorage.getItem("agendamentos")) || [];

function salvar() {
  localStorage.setItem("agendamentos", JSON.stringify(agendamentos));
}

function mostrarToast(msg, cor = "#2ecc71") {
  toast.style.background = cor;
  toast.innerText = msg;
  toast.style.display = "block";
  setTimeout(() => toast.style.display = "none", 3000);
}

function renderizar() {
  lista.innerHTML = "";

  agendamentos.forEach((ag, index) => {
    const li = document.createElement("li");
    li.innerHTML = `
      <strong>${ag.nome}</strong><br>
      ${ag.servico} • ${ag.barbeiro}<br>
      📅 ${ag.data} ⏰ ${ag.hora}
      <button onclick="cancelar(${index})">Cancelar</button>
    `;
    lista.appendChild(li);
  });
}

function cancelar(index) {
  agendamentos.splice(index, 1);
  salvar();
  renderizar();
  mostrarToast("Agendamento cancelado ❌", "#e74c3c");
}

function logout() {
  localStorage.removeItem("usuarioLogado");
  window.location.href = "index.html";
}

form.addEventListener("submit", e => {
  e.preventDefault();

  const nome = document.getElementById("nome").value;
  const telefone = document.getElementById("telefone").value;
  const servico = document.getElementById("servico").value;
  const barbeiro = document.getElementById("barbeiro").value;
  const data = document.getElementById("data").value;
  const hora = document.getElementById("hora").value;

  const ocupado = agendamentos.some(
    a => a.data === data && a.hora === hora && a.barbeiro === barbeiro
  );

  if (ocupado) {
    mostrarToast("Horário já ocupado ❌", "#e74c3c");
    return;
  }

  agendamentos.push({ nome, telefone, servico, barbeiro, data, hora });
  salvar();
  renderizar();
  form.reset();

  mostrarToast("Agendamento realizado ✅");
});

renderizar();
