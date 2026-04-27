function togglePasswordVisibility(inputId, button) {
      const input = document.getElementById(inputId);
      const icon = button.querySelector('.material-symbols-outlined');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
      } else {
        input.type = 'password';
        icon.textContent = 'visibility';
      }
    }

    // Validação de senhas (permanece)
    (function() {
      const form = document.querySelector('form');
      form.addEventListener('submit', function(e) {
        const senha = document.getElementById('senha');
        const confirma = document.getElementById('confirma');
        if (senha.value !== confirma.value) {
          e.preventDefault();
          alert('As senhas não coincidem — por favor, verifique.');
        }
      });
    })();