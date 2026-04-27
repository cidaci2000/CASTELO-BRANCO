function toggleFaq(element) {
      const faqItem = element.closest('.faq-item');
      faqItem.classList.toggle('expandido');
    }

    // função para filtrar por categoria (simulação visual)
    function filtrarCategoria(categoria, botao) {
      // atualizar botão ativo
      document.querySelectorAll('.categoria-btn').forEach(btn => {
        btn.classList.remove('ativo');
      });
      botao.classList.add('ativo');

      // mostrar/esconder itens (simples para demonstração)
      const itens = document.querySelectorAll('.faq-item');
      if (categoria === 'todos') {
        itens.forEach(item => item.style.display = 'block');
      } else {
        itens.forEach(item => {
          if (item.dataset.categoria === categoria) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      }
    }

    // função de busca simples (filtra pelo texto da pergunta)
    function filtrarPerguntas(texto) {
      const termo = texto.toLowerCase();
      const itens = document.querySelectorAll('.faq-item');
      
      itens.forEach(item => {
        const pergunta = item.querySelector('.faq-pergunta h3').innerText.toLowerCase();
        if (pergunta.includes(termo) || termo === '') {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    }

    // manter a categoria "todos" ativa ao buscar
    document.querySelector('.faq-busca input').addEventListener('keyup', function() {
      document.querySelectorAll('.categoria-btn').forEach(btn => {
        btn.classList.remove('ativo');
      });
      document.querySelector('.categoria-btn[onclick*="todos"]').classList.add('ativo');
    });

    // abrir primeiro item por padrão (opcional)
    window.addEventListener('load', function() {
      // primeiro item aberto para exemplo
      const primeiroItem = document.querySelector('.faq-item');
      if (primeiroItem) {
        primeiroItem.classList.add('expandido');
      }
    });