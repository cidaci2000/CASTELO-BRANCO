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

    // (opcional) prevenir submit para exemplo
    document.querySelector('form')?.addEventListener('submit', (e) => {
      e.preventDefault();
      alert('(demonstração) — em um site real, você seria autenticado.');
    });