// Este script deve ser incluído em TODAS as páginas do site para carrinho

const CART_STORAGE_KEY = 'flwrs_shopping_cart';

// Produtos disponíveis (mantenha o mesmo catálogo em todas as páginas)
const productsCatalog = {
    'flor1': { id: 'flor1', name: 'Buquê "Sol e Mel"', price: 99.90, icon: '🌻' },
    'flor2': { id: 'flor2', name: 'Arranjo "minimalismo verde"', price: 112.00, icon: '🌱' },
    'flor3': { id: 'flor3', name: 'Cesta "Kit Afeto"', price: 189.90, icon: '💮' },
    'flor4': { id: 'flor4', name: 'Buquê "Rosas suaves"', price: 129.90, icon: '🌹' },
    'flor5': { id: 'flor5', name: 'Buquê "Campos de Verão"', price: 415.00, icon: '🌿' },
    'flor6': { id: 'flor6', name: 'Recado "Recado Florido"', price: 79.90, icon: '💌' }
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

// Adicionar produto ao carrinho (pode ser chamado de qualquer página)
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

// Remover produto
function removeFromCartGlobal(productId) {
    let cart = loadCart();
    cart = cart.filter(item => item.productId !== productId);
    saveCart(cart);
    updateCartCountDisplay();
}

// Atualizar quantidade
function updateCartQuantity(productId, newQuantity) {
    if (newQuantity <= 0) {
        removeFromCartGlobal(productId);
        return;
    }
    
    let cart = loadCart();
    const item = cart.find(item => item.productId === productId);
    if (item) {
        item.quantity = newQuantity;
        saveCart(cart);
        updateCartCountDisplay();
    }
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
    // Remove toast existente
    const existingToast = document.querySelector('.cart-toast-global');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = 'cart-toast-global';
    toast.innerHTML = `<i class="fas fa-flower"></i> ${message}`;
    document.body.appendChild(toast);
    
    // Adicionar estilos inline para o toast (caso não tenha CSS)
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

// Adicionar estilos de animação se não existirem
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
            }
        `;
        document.head.appendChild(style);
    }
}

// Inicializar o carrinho na página atual
function initGlobalCart() {
    addToastStyles();
    updateCartCountDisplay();
    
    // Observar mudanças no localStorage (para sincronizar entre abas)
    window.addEventListener('storage', (e) => {
        if (e.key === CART_STORAGE_KEY) {
            updateCartCountDisplay();
        }
    });
}

// Executar quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGlobalCart);
} else {
    initGlobalCart();
}