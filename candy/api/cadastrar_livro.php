<?php


require_once '../config/conexao.php';

try {
  
    
    // Preparar SQL
    $sql = "INSERT INTO livros (titulo, autor, editora, descricao, preco, categoria, imagem_url, estoque, isbn, num_paginas) 
            VALUES (:titulo, :autor, :editora, :descricao, :preco, :categoria, :imagem_url, :estoque, :isbn, :num_paginas)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titulo' => $dados['titulo'],
        ':autor' => $dados['autor'],
        ':editora' => $dados['editora'],
        ':descricao' => $dados['descricao'],
        ':preco' => $dados['preco'],
        ':categoria' => $dados['categoria'],
        ':imagem_url' => $dados['imagem_url'],
        ':estoque' => $dados['estoque'],
        ':isbn' => $dados['isbn'],
        ':num_paginas' => $dados['num_paginas']
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Livro cadastrado com sucesso!', 'id' => $pdo->lastInsertId()]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>