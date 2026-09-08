// Validação simples de formulário de login
document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    alert(`Login realizado com: ${email}`);
});

// Validação simples de formulário de cadastro
document.getElementById('register-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    alert(`Cadastro realizado com: ${name} (${email})`);
});