function toggleFaq(element) {
    const faqItem = element.closest('.faq-item');
    // Alterna entre as classes 'aberto' e 'fechado'
    faqItem.classList.toggle('aberto');
}

// Função para filtrar por categoria
function filtrarCategoria(categoria, botao) {
    // Atualizar botão ativo
    document.querySelectorAll('.categoria-btn').forEach(btn => {
        btn.classList.remove('ativo');
    });
    botao.classList.add('ativo');

    // Mostrar/esconder itens
    const itens = document.querySelectorAll('.faq-item');
    if (categoria === 'todos') {
        itens.forEach(item => {
            item.style.display = 'block';
            // Remove a classe escondido se existir
            item.classList.remove('escondido');
        });
    } else {
        itens.forEach(item => {
            if (item.dataset.categoria === categoria) {
                item.style.display = 'block';
                item.classList.remove('escondido');
            } else {
                item.style.display = 'none';
                item.classList.add('escondido');
            }
        });
    }
}

// Função de busca simples
function filtrarPerguntas(texto) {
    const termo = texto.toLowerCase().trim();
    const itens = document.querySelectorAll('.faq-item');
    let temResultado = false;
    
    itens.forEach(item => {
        const pergunta = item.querySelector('.faq-pergunta h3').innerText.toLowerCase();
        if (pergunta.includes(termo) || termo === '') {
            item.style.display = 'block';
            item.classList.remove('escondido');
            temResultado = true;
        } else {
            item.style.display = 'none';
            item.classList.add('escondido');
        }
    });
}

// Inicialização quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    // Abrir o primeiro item por padrão
    const primeiroItem = document.querySelector('.faq-item');
    if (primeiroItem) {
        primeiroItem.classList.add('aberto');
    }
    
    // Adicionar evento de busca com debounce
    const buscaInput = document.querySelector('.faq-busca input');
    if (buscaInput) {
        buscaInput.addEventListener('keyup', function() {
            filtrarPerguntas(this.value);
            
            // Resetar botões de categoria
            document.querySelectorAll('.categoria-btn').forEach(btn => {
                btn.classList.remove('ativo');
            });
            const btnTodos = document.querySelector('.categoria-btn[onclick*="todos"]');
            if (btnTodos) {
                btnTodos.classList.add('ativo');
            }
        });
    }
});