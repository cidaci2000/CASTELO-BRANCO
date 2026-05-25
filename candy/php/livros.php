<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Livros - Livraria</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .formulario {
            padding: 40px;
        }

        .grupo-form {
            margin-bottom: 20px;
        }

        .grupo-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .grupo-form input,
        .grupo-form textarea,
        .grupo-form select {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .grupo-form input:focus,
        .grupo-form textarea:focus,
        .grupo-form select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .grupo-form textarea {
            resize: vertical;
            min-height: 100px;
        }

        .linha-duas-colunas {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .preview {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .preview h3 {
            margin-bottom: 15px;
            color: #667eea;
        }

        .livro-preview {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .livro-preview img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }

        .info-preview h4 {
            color: #333;
            margin-bottom: 5px;
        }

        .info-preview p {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .preco-preview {
            color: #28a745;
            font-weight: bold;
            font-size: 18px;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Cadastro de Livros</h1>
            <p>Adicione novos livros ao catálogo</p>
        </div>

        <div class="formulario">
            <div id="mensagem" class="alert"></div>

            <form id="formCadastroLivro">
                <div class="grupo-form">
                    <label for="titulo">Título do Livro *</label>
                    <input type="text" id="titulo" name="titulo" required placeholder="Ex: Doces Coloridos">
                </div>

                <div class="linha-duas-colunas">
                    <div class="grupo-form">
                        <label for="autor">Autor *</label>
                        <input type="text" id="autor" name="autor" required placeholder="Nome do autor">
                    </div>
                    <div class="grupo-form">
                        <label for="editora">Editora</label>
                        <input type="text" id="editora" name="editora" placeholder="Editora">
                    </div>
                </div>

                <div class="grupo-form">
                    <label for="descricao">Descrição *</label>
                    <textarea id="descricao" name="descricao" required placeholder="Descreva o livro..."></textarea>
                </div>

                <div class="linha-duas-colunas">
                    <div class="grupo-form">
                        <label for="preco">Preço (R$) *</label>
                        <input type="number" id="preco" name="preco" step="0.01" required placeholder="0.00">
                    </div>
                    <div class="grupo-form">
                        <label for="categoria">Categoria</label>
                        <select id="categoria" name="categoria">
                            <option value="">Selecione...</option>
                            <option value="Gastronomia">Gastronomia</option>
                            <option value="Ficção">Doces</option>
                            <option value="Romance">Salgados</option>
                            <option value="Técnico">Bolos</option>
                            <option value="Infantil">Outros</option>
                        </select>
                    </div>
                </div>

                <div class="linha-duas-colunas">
                    <div class="grupo-form">
                        <label for="imagem_url">URL da Imagem *</label>
                        <input type="url" id="imagem_url" name="imagem_url" required placeholder="https://...">
                    </div>
                    <div class="grupo-form">
                        <label for="estoque">Estoque</label>
                        <input type="number" id="estoque" name="estoque" value="0">
                    </div>
                </div>

                <div class="linha-duas-colunas">
                    <div class="grupo-form">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" placeholder="ISBN">
                    </div>
                    <div class="grupo-form">
                        <label for="num_paginas">Nº de Páginas</label>
                        <input type="number" id="num_paginas" name="num_paginas">
                    </div>
                </div>

                <button type="submit" class="btn-submit">📖 Cadastrar Livro</button>
            </form>

            <!-- Preview do livro -->
            <div class="preview">
                <h3>🔍 Preview do Livro</h3>
                <div id="previewLivro" class="livro-preview">
                    <img id="previewImg" src="https://via.placeholder.com/100x150?text=Prévia" alt="Preview">
                    <div class="info-preview">
                        <h4 id="previewTitulo">Título do livro</h4>
                        <p id="previewDescricao">Descrição aparecerá aqui...</p>
                        <div class="preco-preview" id="previewPreco">R$ 0,00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preview em tempo real
        document.getElementById('titulo').addEventListener('input', function() {
            document.getElementById('previewTitulo').textContent = this.value || 'Título do livro';
        });

        document.getElementById('descricao').addEventListener('input', function() {
            let desc = this.value.substring(0, 100);
            document.getElementById('previewDescricao').textContent = desc + (this.value.length > 100 ? '...' : '');
        });

        document.getElementById('preco').addEventListener('input', function() {
            let preco = parseFloat(this.value);
            if (!isNaN(preco)) {
                document.getElementById('previewPreco').textContent = `R$ ${preco.toFixed(2)}`;
            } else {
                document.getElementById('previewPreco').textContent = 'R$ 0,00';
            }
        });

        document.getElementById('imagem_url').addEventListener('input', function() {
            let img = document.getElementById('previewImg');
            if (this.value) {
                img.src = this.value;
                img.onerror = function() {
                    this.src = 'https://via.placeholder.com/100x150?text=Erro+imagem';
                };
            }
        });

        // Envio do formulário
        document.getElementById('formCadastroLivro').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Coletar dados do formulário
            const dadosLivro = {
                titulo: document.getElementById('titulo').value,
                autor: document.getElementById('autor').value,
                editora: document.getElementById('editora').value,
                descricao: document.getElementById('descricao').value,
                preco: parseFloat(document.getElementById('preco').value),
                categoria: document.getElementById('categoria').value,
                imagem_url: document.getElementById('imagem_url').value,
                estoque: parseInt(document.getElementById('estoque').value),
                isbn: document.getElementById('isbn').value,
                num_paginas: document.getElementById('num_paginas').value ? parseInt(document.getElementById('num_paginas').value) : null
            };

            // Validar campos obrigatórios
            if (!dadosLivro.titulo || !dadosLivro.autor || !dadosLivro.descricao || !dadosLivro.preco || !dadosLivro.imagem_url) {
                mostrarMensagem('Por favor, preencha todos os campos obrigatórios (*)', 'error');
                return;
            }

            try {
                // Aqui você fará a requisição para o backend
                // Exemplo com fetch (backend em PHP/Node.js)
                const response = await fetch('api/cadastrar_livro.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(dadosLivro)
                });

                if (response.ok) {
                    const resultado = await response.json();
                    mostrarMensagem('Livro cadastrado com sucesso!', 'success');
                    this.reset(); // Limpa o formulário
                    // Limpar preview
                    document.getElementById('previewTitulo').textContent = 'Título do livro';
                    document.getElementById('previewDescricao').textContent = 'Descrição aparecerá aqui...';
                    document.getElementById('previewPreco').textContent = 'R$ 0,00';
                    document.getElementById('previewImg').src = 'https://via.placeholder.com/100x150?text=Prévia';
                } else {
                    mostrarMensagem('Erro ao cadastrar livro. Tente novamente.', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                mostrarMensagem('Erro de conexão com o servidor.', 'error');
            }
        });

        function mostrarMensagem(msg, tipo) {
            const alertDiv = document.getElementById('mensagem');
            alertDiv.textContent = msg;
            alertDiv.className = `alert ${tipo}`;
            
            setTimeout(() => {
                alertDiv.style.display = 'none';
                alertDiv.className = 'alert';
            }, 5000);
        }
    </script>
</body>
</html>