// ===== SCRIPT DO CARRINHO (deve vir PRIMEIRO) =====
const CART_STORAGE_KEY = 'flwrs_shopping_cart';

// Produtos disponíveis (catálogo completo)
const productsCatalog = {
    'flor1': { id: 'flor1', name: 'Buquê "Sol e Mel"', price: 99.90, icon: '🌻' },
    'flor2': { id: 'flor2', name: 'Arranjo "minimalismo verde"', price: 112.00, icon: '🌱' },
    'flor3': { id: 'flor3', name: 'Cesta "Kit Afeto"', price: 189.90, icon: '💮' },
    'flor4': { id: 'flor4', name: 'Buquê "Rosas suaves"', price: 129.90, icon: '🌹' },
    'flor5': { id: 'flor5', name: 'Buquê "Campos de Verão"', price: 415.00, icon: '🌿' }
};

// Mapeamento dos produtos da página com os IDs do catálogo
const productMapping = {
    'campos de verão': 'flor1',
    'rosas suaves': 'flor2',
    'minimalismo verde': 'flor3',
    'kit afeto': 'flor4',
    'sol e mel': 'flor5',
    'recado florido': 'flor1'  // usando flor1 como exemplo
};

// Carregar carrinho do localStorage
function loadCart() {
    const stored = localStorage.getItem(CART_STORAGE_KEY);
    if (stored) {
        try {
            return JSON.parse(stored);
        } catch (e) {
            return [];
        }
    }
    return [];
}

// Salvar carrinho
function saveCart(cart) {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
}

// Adicionar produto ao carrinho
function addToCartGlobal(productId, quantity = 1) {
    const product = productsCatalog[productId];
    if (!product) return false;
    
    let cart = loadCart();
    const existingIndex = cart.findIndex(item => item.productId === productId);
    
    if (existingIndex !== -1) {
        cart[existingIndex].quantity += quantity;
    } else {
        cart.push({ 
            productId, 
            quantity, 
            product: {
                id: product.id,
                name: product.name,
                price: product.price,
                icon: product.icon
            }
        });
    }
    
    saveCart(cart);
    updateCartCountDisplay();
    showCartToast(`${product.name} adicionado ao carrinho 💐`);
    return true;
}

// Obter total de itens no carrinho
function getCartTotalItems() {
    const cart = loadCart();
    return cart.reduce((sum, item) => sum + item.quantity, 0);
}

// Atualizar o contador na navbar
function updateCartCountDisplay() {
    const totalItems = getCartTotalItems();
    const badge = document.getElementById('cartCountBadge');
    if (badge) {
        badge.textContent = totalItems;
        
        // Animação sutil quando atualiza
        badge.style.transform = 'scale(1.2)';
        setTimeout(() => {
            badge.style.transform = 'scale(1)';
        }, 200);
    }
}

// Toast notification
function showCartToast(message) {
    const existingToast = document.querySelector('.cart-toast-global');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = 'cart-toast-global';
    toast.innerHTML = `<i class="fas fa-flower"></i> ${message}`;
    document.body.appendChild(toast);
    
    toast.style.position = 'fixed';
    toast.style.bottom = '20px';
    toast.style.right = '20px';
    toast.style.backgroundColor = '#4f4a45e6';
    toast.style.backdropFilter = 'blur(8px)';
    toast.style.color = 'white';
    toast.style.padding = '12px 20px';
    toast.style.borderRadius = '50px';
    toast.style.fontSize = '0.85rem';
    toast.style.zIndex = '1100';
    toast.style.fontFamily = "'Inter', sans-serif";
    toast.style.boxShadow = '0 6px 18px rgba(0,0,0,0.1)';
    toast.style.animation = 'toastFadeIn 0.3s ease';
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

// Adicionar estilos de animação
function addToastStyles() {
    if (!document.querySelector('#cartToastStyles')) {
        const style = document.createElement('style');
        style.id = 'cartToastStyles';
        style.textContent = `
            @keyframes toastFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .cart-toast-global {
                animation: toastFadeIn 0.3s ease;
                font-family: 'Inter', sans-serif;
            }
        `;
        document.head.appendChild(style);
    }
}

// ===== FUNÇÕES ESPECÍFICAS DA PÁGINA DE PRODUTOS =====

// Função para adicionar produto ao carrinho a partir do nome do produto
function addProductToCart(productName) {
    // Normalizar o nome do produto (remover acentos, lowercase)
    const normalizedName = productName.toLowerCase().trim();
    
    // Mapear o nome do produto para o ID
    let productId = null;
    
    // Verificar no mapeamento
    if (productMapping[normalizedName]) {
        productId = productMapping[normalizedName];
    } else {
        // Tentar encontrar por similaridade
        for (const [key, value] of Object.entries(productMapping)) {
            if (normalizedName.includes(key) || key.includes(normalizedName)) {
                productId = value;
                break;
            }
        }
    }
    
    if (productId) {
        addToCartGlobal(productId, 1);
    } else {
        console.log('Produto não encontrado:', productName);
        showCartToast(`Produto "${productName}" adicionado ao carrinho! 🌸`);
    }
}

// Função para configurar os botões "comprar"
function setupBuyButtons() {
    const buyButtons = document.querySelectorAll('.btn-comprar');
    
    buyButtons.forEach((button, index) => {
        // Encontrar o card do produto
        const productCard = button.closest('.produto-card');
        if (productCard) {
            // Pegar o nome do produto
            const productNameElement = productCard.querySelector('.produto-nome');
            if (productNameElement) {
                const productName = productNameElement.textContent;
                
                // Remover eventos anteriores (se houver) e adicionar novo
                button.removeEventListener('click', handleBuyClick);
                button.addEventListener('click', handleBuyClick);
                
                function handleBuyClick(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    addProductToCart(productName);
                    
                    // Feedback visual no botão
                    const originalText = button.textContent;
                    button.textContent = '✓ adicionado!';
                    button.style.background = '#b2e4b3';
                    
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.style.background = '#deef6e';
                    }, 1500);
                }
            }
        }
    });
}

// Configurar filtros
function setupFilters() {
    document.querySelectorAll('.filtro-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('ativo'));
            this.classList.add('ativo');
            showCartToast('🔍 Filtro aplicado: ' + this.textContent);
        });
    });
}

// Configurar favoritos
function setupFavorites() {
    document.querySelectorAll('.btn-favoritar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const icon = this.querySelector('.material-symbols-outlined');
            
            if (icon.textContent === 'favorite') {
                icon.textContent = 'favorite';
                this.style.color = '#c06f8b';
                showCartToast('❤️ Adicionado aos favoritos!');
            } else {
                icon.textContent = 'favorite';
                this.style.color = '#b2a19b';
            }
        });
    });
}

// Inicializar a página
function initProdutosPage() {
    addToastStyles();
    updateCartCountDisplay();
    setupBuyButtons();
    setupFilters();
    setupFavorites();
    
    // Observar mudanças no localStorage (sincronizar entre abas)
    window.addEventListener('storage', (e) => {
        if (e.key === CART_STORAGE_KEY) {
            updateCartCountDisplay();
        }
    });
}

// Inicializar quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initProdutosPage);
} else {
    initProdutosPage();
}