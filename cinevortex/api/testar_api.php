<?php
// api/testar_api.php - Arquivo para testar a API
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste da API - Adicionar Filme</title>
    <style>
        body { font-family: Arial; padding: 20px; max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #e50914; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #f40612; }
        .result { margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 4px; white-space: pre-wrap; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Teste da API - Adicionar Filme</h1>
    
    <form id="testForm">
        <div class="form-group">
            <label>Título do Filme *</label>
            <input type="text" id="titulo" required value="Filme Teste API">
        </div>
        
        <div class="form-group">
            <label>Ano *</label>
            <input type="number" id="ano" required value="2024">
        </div>
        
        <div class="form-group">
            <label>Descrição</label>
            <textarea id="descricao">Este é um filme de teste adicionado via API</textarea>
        </div>
        
        <div class="form-group">
            <label>Gênero</label>
            <input type="text" id="genero" value="Ação">
        </div>
        
        <div class="form-group">
            <label>URL da Imagem</label>
            <input type="text" id="url_imagem" value="https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg">
        </div>
        
        <button type="submit">Adicionar Filme</button>
    </form>
    
    <div id="result" class="result"></div>
    
    <script>
        document.getElementById('testForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const data = {
                titulo: document.getElementById('titulo').value,
                ano: parseInt(document.getElementById('ano').value),
                descricao: document.getElementById('descricao').value,
                genero: document.getElementById('genero').value,
                url_imagem: document.getElementById('url_imagem').value
            };
            
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = 'Enviando requisição...';
            resultDiv.className = 'result';
            
            try {
                const response = await fetch('adicionar_filme.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                resultDiv.innerHTML = JSON.stringify(result, null, 2);
                
                if (result.success) {
                    resultDiv.className = 'result success';
                    alert('✅ Filme adicionado com sucesso!');
                    // Limpa o formulário
                    document.getElementById('titulo').value = '';
                    document.getElementById('ano').value = '';
                    document.getElementById('descricao').value = '';
                    document.getElementById('genero').value = '';
                    document.getElementById('url_imagem').value = '';
                } else {
                    resultDiv.className = 'result error';
                }
            } catch (error) {
                resultDiv.innerHTML = 'Erro: ' + error.message;
                resultDiv.className = 'result error';
            }
        });
    </script>
</body>
</html>