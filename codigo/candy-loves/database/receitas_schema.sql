-- Tabela de Categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao VARCHAR(255),
    icone VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Receitas
CREATE TABLE IF NOT EXISTS receitas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    categoria_id INT NOT NULL,
    imagem_url VARCHAR(500),
    tempo_preparo INT COMMENT 'em minutos',
    tempo_cozimento INT COMMENT 'em minutos',
    dificuldade ENUM('Fácil', 'Média', 'Difícil') DEFAULT 'Média',
    rendimento VARCHAR(100),
    ativo BOOLEAN DEFAULT TRUE,
    destaque BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE,
    INDEX idx_categoria (categoria_id),
    INDEX idx_destaque (destaque),
    INDEX idx_titulo (titulo)
);

-- Tabela de Ingredientes
CREATE TABLE IF NOT EXISTS ingredientes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    receita_id INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    quantidade VARCHAR(50),
    unidade VARCHAR(20),
    FOREIGN KEY (receita_id) REFERENCES receitas(id) ON DELETE CASCADE,
    INDEX idx_receita (receita_id)
);

-- Tabela de Modo de Preparo
CREATE TABLE IF NOT EXISTS modo_preparo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    receita_id INT NOT NULL,
    passo INT NOT NULL,
    descricao TEXT NOT NULL,
    FOREIGN KEY (receita_id) REFERENCES receitas(id) ON DELETE CASCADE,
    INDEX idx_receita (receita_id),
    UNIQUE KEY unique_passo (receita_id, passo)
);

-- Inserir categorias iniciais
INSERT INTO categorias (nome, descricao, icone) VALUES
('Salgados', 'Petiscos e acompanhamentos', '🍽️'),
('Doces', 'Sobremesas deliciosas', '🍰'),
('Tortas', 'Salgadas e doces', '🥧'),
('Café da Manhã', 'Comece o dia bem', '☕'),
('Bebidas', 'Drinks e sucos', '🍹'),
('Fitness', 'Saudável e leve', '🏋️');

-- Inserir algumas receitas de exemplo
INSERT INTO receitas (titulo, descricao, categoria_id, imagem_url, tempo_preparo, tempo_cozimento, dificuldade, rendimento, ativo, destaque) VALUES
('Bolo de Chocolate', 'Bolo fofinho com cobertura cremosa', 2, 'https://images.unsplash.com/photo-1578985545062-69928b1d9587', 30, 35, 'Fácil', '8 porções', TRUE, TRUE),
('Pizza Margherita', 'Pizza caseira tradicional italiana', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSnLSfrVpl7G8H6uNkRv34qqcZbDP_tzLysUg', 20, 25, 'Média', '4 pedaços', TRUE, TRUE),
('Panqueca da Vovó', 'Panquecas macias para o café da manhã', 4, 'https://revistaoeste.com/oestegeral/wp-content/uploads/2026/01/panqueca.jpg', 15, 10, 'Fácil', '6 panquecas', TRUE, TRUE),
('Pudim de Leite', 'Clássico pudim de leite condensado', 2, 'https://docesdajessica.com.br/wp-content/uploads/2023/07/Imagem-destacada-Blog-2-750x394.png.webp', 20, 40, 'Fácil', '6 porções', TRUE, TRUE),
('Brigadeiro de Colher', 'Brigadeiro cremoso em vidro', 2, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTkzPSc2UGhHy2c-mOEW2K_X3tikNZuj4dOEA', 25, 15, 'Fácil', '10 unidades', TRUE, TRUE),
('Torta de Morango', 'Torta doce com morangos frescos', 3, 'https://anamariabrogui.com.br/assets/uploads/receitas/fotos/usuario-3405-d7c50f8f1e1d16ef3a40d31fd288775d.jpg', 40, 30, 'Média', '8 porções', TRUE, FALSE);
