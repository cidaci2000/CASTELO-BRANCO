// js/filmes.js
document.addEventListener('DOMContentLoaded', function() {
    // Filtro de categorias
    const categoryBtns = document.querySelectorAll('.category-btn');
    const movieCards = document.querySelectorAll('.movie-card');
    
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active de todos
            categoryBtns.forEach(b => b.classList.remove('active'));
            // Adiciona active no clicado
            this.classList.add('active');
            
            const category = this.textContent;
            
            // Aqui você pode implementar o filtro real dos filmes
            console.log(`Filtrando por: ${category}`);
        });
    });
    
    // Newsletter form
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            if (email) {
                alert(`Obrigado pela inscrição! Você receberá novidades em ${email}`);
                this.reset();
            }
        });
    }
    
    // Botões assistir
    const watchButtons = document.querySelectorAll('.btn-watch');
    watchButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const movieTitle = this.closest('.movie-card').querySelector('h3').textContent;
            alert(`Reproduzindo: ${movieTitle}\n(Implemente a lógica de reprodução aqui)`);
        });
    });
    
    // Animações suaves
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observar elementos para animação
    document.querySelectorAll('.movie-card, .upcoming-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });
});