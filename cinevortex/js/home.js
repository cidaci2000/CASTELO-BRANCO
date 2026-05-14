// js/home.js (parte relevante para adicionar filme)

// Função para adicionar filme
async function adicionarFilme() {
    const titulo = document.getElementById('novoTitulo').value.trim();
    const ano = document.getElementById('novoAno').value.trim();
    const descricao = document.getElementById('novaDescricao').value.trim();
    const genero = document.getElementById('novoGenero').value.trim();
    const urlImagem = document.getElementById('novaImagem').value.trim();
    
    if (!titulo || !ano) {
        alert('Preencha o título e o ano do filme!');
        return;
    }
    
    const filmeData = {
        titulo: titulo,
        ano: parseInt(ano),
        descricao: descricao || 'Sem descrição',
        genero: genero || 'Geral',
        url_imagem: urlImagem || 'https://via.placeholder.com/300x450?text=Sem+Poster'
    };
    
    console.log('Enviando dados:', filmeData);
    
    try {
        const response = await fetch('api/adicionar_filme.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(filmeData)
        });
        
        const result = await response.json();
        console.log('Resposta:', result);
        
        if (result.success) {
            alert('✅ Filme adicionado com sucesso!');
            fecharModalAdicionar();
            carregarFilmes(); // Recarregar lista de filmes
            limparFormularioAdicionar();
        } else {
            alert('❌ Erro: ' + result.message);
        }
    } catch (error) {
        console.error('Erro na requisição:', error);
        alert('Erro ao conectar com o servidor: ' + error.message);
    }
}

function limparFormularioAdicionar() {
    document.getElementById('novoTitulo').value = '';
    document.getElementById('novoAno').value = '';
    document.getElementById('novaDescricao').value = '';
    document.getElementById('novoGenero').value = '';
    document.getElementById('novaImagem').value = '';
}

function fecharModalAdicionar() {
    const modal = document.getElementById('modalAdicionar');
    if (modal) {
        modal.style.display = 'none';
    }
    limparFormularioAdicionar();
}

function abrirModalAdicionar() {
    const modal = document.getElementById('modalAdicionar');
    if (modal) {
        modal.style.display = 'flex';
    }
    limparFormularioAdicionar();
}