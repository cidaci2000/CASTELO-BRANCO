document.querySelectorAll('.filtro-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('ativo'));
        this.classList.add('ativo');
        alert('(filtro simulado — em um site real, os produtos seriam filtrados)');
      });
    });

    // Interação para favoritar (só visual)
    document.querySelectorAll('.btn-favoritar').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const icon = this.querySelector('.material-symbols-outlined');
        if (icon.textContent === 'favorite') {
          icon.textContent = 'favorite';
          this.style.color = '#c06f8b';
          alert('❤️ adicionado aos favoritos (simulação)');
        } else {
          icon.textContent = 'favorite';
          this.style.color = '#b2a19b';
        }
      });
    });