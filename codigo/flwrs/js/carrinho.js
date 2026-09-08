// Catálogo de produtos
const productsCatalog = {
    'flor1': { id: 'flor1', name: 'Buquê "Sol e Mel"', price: 99.90, icon: '🌻' },
    'flor2': { id: 'flor2', name: 'Arranjo "minimalismo verde"', price: 112.00, icon: '🌱' },
    'flor3': { id: 'flor3', name: 'Cesta "Kit Afeto"', price: 189.90, icon: '💮' },
    'flor4': { id: 'flor4', name: 'Buquê "Rosas suaves"', price: 129.90, icon: '🌹' },
    'flor5': { id: 'flor5', name: 'Buquê "Campos de Verão"', price: 415.00, icon: '🌿' },
    'flor6': { id: 'flor6', name: 'Recado "Recado Florido"', price: 79.90, icon: '💌' }
    
};

const CART_STORAGE_KEY = 'flwrs_shopping_cart';
let cartItems = [];

// Carregar carrinho do localStorage
function loadCartFromStorage() {
    const stored = localStorage.getItem(CART_STORAGE_KEY);
    if (stored) {
        try {
            cartItems = JSON.parse(stored);
            if (!Array.isArray(cartItems)) cartItems = [];
        } catch (e) {
            cartItems = [];
        }
    } else {
        // Carrinho inicial com 2 itens de exemplo
        cartItems = [
            { productId: 'flor1', quantity: 1, product: productsCatalog['flor1'] },
            { productId: 'flor3', quantity: 2, product: productsCatalog['flor3'] }
        ];
        saveCartToStorage();
    }
    
    // Validar e atualizar referências
    cartItems = cartItems.filter(item => productsCatalog[item.productId]);
    cartItems.forEach(item => {
        if (productsCatalog[item.productId]) {
            item.product = productsCatalog[item.productId];
        }
    });
    saveCartToStorage();
}

function saveCartToStorage() {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartItems));
}

// Atualizar contador do carrinho na navbar
function updateCartCounters() {
    const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountNav = document.getElementById('cartCountNav');
    if (cartCountNav) cartCountNav.innerText = totalItems;
}

// Toast notification
function showToastMessage(text) {
    const existingToast = document.querySelector('.toast-msg');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = 'toast-msg';
    toast.innerHTML = `<i class="fas fa-leaf" style="margin-right: 8px;"></i> ${text}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 2300);
}

// Adicionar produto ao carrinho
function addToCart(productId, quantity = 1) {
    const product = productsCatalog[productId];
    if (!product) return false;
    
    const existingIndex = cartItems.findIndex(item => item.productId === productId);
    if (existingIndex !== -1) {
        cartItems[existingIndex].quantity += quantity;
    } else {
        cartItems.push({ productId, quantity, product: product });
    }
    
    saveCartToStorage();
    renderCartPage();
    updateCartCounters();
    showToastMessage(`${product.name} foi adicionado ao carrinho 💐`);
    return true;
}

// Remover item do carrinho
function removeCartItem(productId) {
    const item = cartItems.find(i => i.productId === productId);
    if (item) showToastMessage(`${item.product.name} removido do carrinho.`);
    cartItems = cartItems.filter(item => item.productId !== productId);
    saveCartToStorage();
    renderCartPage();
    updateCartCounters();
}

// Atualizar quantidade
function updateQuantity(productId, newQuantity) {
    if (newQuantity <= 0) {
        removeCartItem(productId);
        return;
    }
    const item = cartItems.find(i => i.productId === productId);
    if (item) {
        item.quantity = newQuantity;
        saveCartToStorage();
        renderCartPage();
        updateCartCounters();
    }
}

// Calcular subtotal
function getCartSubtotal() {
    return cartItems.reduce((sum, item) => sum + (item.product.price * item.quantity), 0);
}

// Renderizar página do carrinho
function renderCartPage() {
    const container = document.getElementById('cartContainer');
    if (!container) return;
    
    if (cartItems.length === 0) {
        container.innerHTML = `
            <div class="empty-cart">
                <i class="fas fa-shopping-basket"></i>
                <h3 style="font-weight: 300; margin-bottom: 0.5rem;">Seu carrinho está vazio</h3>
                <p style="color:#b2a19b;">Que tal escolher flores especiais?</p>
                <div class="btn-shop" id="emptyShopBtn"><i class="fas fa-flower"></i> Explorar arranjos</div>
            </div>
        `;
        
        const emptyBtn = document.getElementById('emptyShopBtn');
        if (emptyBtn) {
            emptyBtn.addEventListener('click', () => {
                showToastMessage("✨ Navegue pelos nossos buquês encantadores");
            });
        }
        return;
    }
    
    let itemsHtml = `<div class="cart-items">`;
    cartItems.forEach(item => {
        const prod = item.product;
        const totalItem = prod.price * item.quantity;
        itemsHtml += `
            <div class="cart-item" data-id="${item.productId}">
                <div class="item-img">
                    <i class="fas fa-seedling"></i> ${prod.icon}
                </div>
                <div class="item-details">
                    <h3>${prod.name}</h3>
                    <div class="item-price">R$ ${prod.price.toFixed(2)}</div>
                </div>
                <div class="item-quantity">
                    <button class="qty-btn decrement" data-id="${item.productId}">−</button>
                    <span class="item-qty-num">${item.quantity}</span>
                    <button class="qty-btn increment" data-id="${item.productId}">+</button>
                </div>
                <div class="item-total">R$ ${totalItem.toFixed(2)}</div>
                <button class="remove-item" data-id="${item.productId}"><i class="fas fa-trash-alt"></i></button>
            </div>
        `;
    });
    itemsHtml += `</div>`;
    
    const subtotal = getCartSubtotal();
    const frete = 19.90;
    const total = subtotal + frete;
    
    const summaryHtml = `
        <div class="cart-summary">
            <div class="summary-title">resumo do pedido</div>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>R$ ${subtotal.toFixed(2)}</span>
            </div>
            <div class="summary-row">
                <span>Frete (entregas afetivas)</span>
                <span>R$ ${frete.toFixed(2)}</span>
            </div>
            <div class="summary-row summary-total">
                <span><strong>Total</strong></span>
                <span><strong>R$ ${total.toFixed(2)}</strong></span>
            </div>
            <button id="finalizeCheckoutBtn" class="checkout-btn"><i class="fas fa-heart"></i> Finalizar compra</button>
            <div style="font-size:0.7rem; text-align:center; margin-top:1rem; color:#b2a19b;">pague com pix ou cartão</div>
        </div>
    `;
    
    container.innerHTML = `<div class="cart-grid">${itemsHtml}${summaryHtml}</div>`;
    
    // Adicionar eventos dos botões
    document.querySelectorAll('.increment').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = btn.getAttribute('data-id');
            const item = cartItems.find(i => i.productId === id);
            if (item) updateQuantity(id, item.quantity + 1);
        });
    });
    
    document.querySelectorAll('.decrement').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = btn.getAttribute('data-id');
            const item = cartItems.find(i => i.productId === id);
            if (item && item.quantity > 1) updateQuantity(id, item.quantity - 1);
            else if (item && item.quantity === 1) removeCartItem(id);
        });
    });
    
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = btn.getAttribute('data-id');
            removeCartItem(id);
        });
    });
    
    const checkoutBtn = document.getElementById('finalizeCheckoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            if (cartItems.length === 0) {
                showToastMessage("Seu carrinho está vazio, adicione flores especiais :)");
            } else {
                showToastMessage("✨ Compra finalizada! Em breve seu bouquet chegará. Obrigado por florescer conosco.");
            }
        });
    }
}

// Adicionar seção de recomendações
function addRecommendations() {
    const container = document.getElementById('cartContainer');
    if (!container) return;
    
    const existingReco = document.getElementById('extraReco');
    if (existingReco) existingReco.remove();
    
    const recommendHtml = `
        <div id="extraReco" class="recommend-section">
            <div class="recommend-flex">
                <div class="recommend-title">
                    <i class="fas fa-flower" style="color:#b4849a;"></i>
                    <strong style="font-weight:400;">Presenteie mais amor:</strong> Adicione um buquê extra
                </div>
                <div class="recommend-buttons">
                    <button id="suggestFlor2" class="card-link">🌿 minimalismo verde + R$112,00</button>
                    <button id="suggestFlor4" class="card-link">🌹 rosas suaves + R$129,90</button>
                    <button id="suggestFlor1" class="card-link">🌻 sol e mel + R$99,90</button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('afterend', recommendHtml);
    
    document.getElementById('suggestFlor2')?.addEventListener('click', () => addToCart('flor2', 1));
    document.getElementById('suggestFlor4')?.addEventListener('click', () => addToCart('flor4', 1));
    document.getElementById('suggestFlor1')?.addEventListener('click', () => addToCart('flor1', 1));
}

// Inicializar
function init() {
    loadCartFromStorage();
    renderCartPage();
    updateCartCounters();
    addRecommendations();
    
    // Eventos da navbar
    const cartNavIcon = document.getElementById('cartIconNav');
    if (cartNavIcon) {
        cartNavIcon.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
            renderCartPage();
            showToastMessage("Carrinho atualizado 💐");
        });
    }
    
    const navHome = document.getElementById('navHome');
    const navShop = document.getElementById('navShop');
    const navSobre = document.getElementById('navSobre');
    const breadHome = document.getElementById('breadHome');
    
    if (navHome) {
        navHome.addEventListener('click', (e) => {
            e.preventDefault();
            showToastMessage("🏠 Página inicial: veja nossos arranjos especiais.");
        });
    }
    
    if (navShop) {
        navShop.addEventListener('click', (e) => {
            e.preventDefault();
            const sampleIds = ['flor1', 'flor2', 'flor4'];
            const randomId = sampleIds[Math.floor(Math.random() * sampleIds.length)];
            addToCart(randomId, 1);
        });
    }
    
    if (navSobre) {
        navSobre.addEventListener('click', (e) => {
            e.preventDefault();
            showToastMessage("🌸 Flwrs: flores que contam histórias, entregas com afeto.");
        });
    }
    
    if (breadHome) {
        breadHome.addEventListener('click', (e) => {
            e.preventDefault();
            showToastMessage("Volte à página inicial para conhecer nossas coleções.");
        });
    }
}

// Iniciar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', init);