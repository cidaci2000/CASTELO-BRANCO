'use strict';

document.addEventListener('DOMContentLoaded', () => {
    // Detecta qual formulário está na página
    const loginForm    = document.getElementById('loginForm');
    const cadastroForm = document.getElementById('cadastroForm');

    if (loginForm)    initLogin(loginForm);
    if (cadastroForm) initCadastro(cadastroForm);
});

/* =========================================================
   Helpers
   ========================================================= */
function showMsg(tipo, msg, tempo = 5000) {
    const err = document.getElementById('errorMessage');
    const suc = document.getElementById('successMessage');
    if (!err || !suc) return;

    if (tipo === 'erro') {
        err.textContent = msg;
        err.style.display = 'block';
        suc.style.display = 'none';
        setTimeout(() => { err.style.display = 'none'; }, tempo);
    } else {
        suc.textContent = msg;
        suc.style.display = 'block';
        err.style.display = 'none';
    }
}

async function postJSON(url, data) {
    const r = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    });
    return r.json();
}

/* =========================================================
   LOGIN
   ========================================================= */
function initLogin(form) {
    form.addEventListener('submit', async e => {
        e.preventDefault();

        const email = document.getElementById('email').value.trim();
        const senha = document.getElementById('senha').value;

        if (!email || !senha) {
            return showMsg('erro', 'Preencha todos os campos.');
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            return showMsg('erro', 'Digite um e-mail válido.');
        }

        const btn = form.querySelector('button[type="submit"]');
        const orig = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Entrando...';

        try {
            const j = await postJSON('api/login.php', { email, senha });
            if (j.success) {
                showMsg('sucesso', j.message || 'Login realizado!');
                setTimeout(() => { window.location.href = j.redirect || 'home.php'; }, 1200);
            } else {
                showMsg('erro', j.message || 'Erro ao entrar.');
            }
        } catch (err) {
            console.error(err);
            showMsg('erro', 'Erro ao conectar com o servidor.');
        } finally {
            btn.disabled = false;
            btn.textContent = orig;
        }
    });
}

/* =========================================================
   CADASTRO
   ========================================================= */
function initCadastro(form) {
    form.addEventListener('submit', async e => {
        e.preventDefault();

        const nome     = document.getElementById('nome').value.trim();
        const email    = document.getElementById('email').value.trim();
        const senha    = document.getElementById('senha').value;
        const confirma = document.getElementById('confirmar_senha').value;

        if (!nome || !email || !senha) {
            return showMsg('erro', 'Todos os campos são obrigatórios.');
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            return showMsg('erro', 'Digite um e-mail válido.');
        }
        if (senha.length < 6) {
            return showMsg('erro', 'A senha deve ter no mínimo 6 caracteres.');
        }
        if (senha !== confirma) {
            return showMsg('erro', 'As senhas não coincidem.');
        }

        const btn = form.querySelector('button[type="submit"]');
        const orig = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Cadastrando...';

        try {
            const j = await postJSON('api/cadastrar.php', {
                nome, email, senha, confirmar_senha: confirma
            });
            if (j.success) {
                showMsg('sucesso', 'Conta criada! Redirecionando para o login...');
                form.reset();
                setTimeout(() => { window.location.href = 'login.php'; }, 1600);
            } else {
                showMsg('erro', j.message || 'Erro ao cadastrar.');
            }
        } catch (err) {
            console.error(err);
            showMsg('erro', 'Erro ao conectar com o servidor.');
        } finally {
            btn.disabled = false;
            btn.textContent = orig;
        }
    });
}