create database flwrs_def;
use flwrs_def;

CREATE TABLE USUARIO (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_nascimento DATE,
    telefone VARCHAR(15),
    genero CHAR(1) CHECK (genero IN ('M', 'F', 'O')),
    cep VARCHAR(10),
    tipo ENUM('admin', 'comum') DEFAULT 'comum',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE CLIENTE (
    id_cliente INT PRIMARY KEY AUTO_INCREMENT,
    nome_completo VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefone VARCHAR(15),
    cep VARCHAR(10),
    endereco VARCHAR(200),
    data_cadastro DATE,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    id_usuario INT UNIQUE,
    FOREIGN KEY (id_usuario) REFERENCES USUARIO(id_usuario)
);

CREATE TABLE CATEGORIA (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) UNIQUE NOT NULL,
    descricao TEXT
);

CREATE TABLE PRODUTO (
    id_buque INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL CHECK (preco >= 0),
    estoque INT NOT NULL DEFAULT 0 CHECK (estoque >= 0),
    imagem_url VARCHAR(255),
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_categoria INT NOT NULL,
    FOREIGN KEY (id_categoria) REFERENCES CATEGORIA(id_categoria)
);

CREATE TABLE PEDIDO (
    id_pedido INT PRIMARY KEY AUTO_INCREMENT,
    id_cliente INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pendente', 'confirmado', 'preparando', 'enviado', 'entregue', 'cancelado') DEFAULT 'pendente',
    total DECIMAL(10,2) DEFAULT 0,
    observacao TEXT,
    data_cancelamento DATETIME,
    FOREIGN KEY (id_cliente) REFERENCES CLIENTE(id_cliente)
);

CREATE TABLE ITEM_PEDIDO (
    id_item INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    id_buque INT NOT NULL,
    quantidade INT NOT NULL CHECK (quantidade > 0),
    preco_unitario DECIMAL(10,2) NOT NULL CHECK (preco_unitario >= 0),
    subtotal DECIMAL(10,2) GENERATED ALWAYS AS (quantidade * preco_unitario) STORED,
    FOREIGN KEY (id_pedido) REFERENCES PEDIDO(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_buque) REFERENCES PRODUTO(id_buque),
    UNIQUE KEY (id_pedido, id_buque)
);

CREATE TABLE FORNECEDOR (
    id_fornecedor INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    cnpj VARCHAR(18) UNIQUE NOT NULL,
    telefone VARCHAR(15),
    email VARCHAR(100),
    endereco VARCHAR(200),
    status ENUM('ativo', 'inativo') DEFAULT 'ativo'
);

CREATE TABLE FORNECEDOR_PRODUTO (
    id_fornecedor INT NOT NULL,
    id_buque INT NOT NULL,
    preco_custo DECIMAL(10,2),
    prazo_entrega INT COMMENT 'dias',
    PRIMARY KEY (id_fornecedor, id_buque),
    FOREIGN KEY (id_fornecedor) REFERENCES FORNECEDOR(id_fornecedor),
    FOREIGN KEY (id_buque) REFERENCES PRODUTO(id_buque)
);

CREATE TABLE ENTREGA (
    id_entrega INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT UNIQUE NOT NULL,
    id_cliente INT NOT NULL,
    data_prevista DATETIME,
    data_entrega DATETIME,
    cep VARCHAR(10),
    endereco_completo VARCHAR(200),
    status_entrega ENUM('pendente', 'em_transporte', 'entregue', 'falha') DEFAULT 'pendente',
    observacao TEXT,
    FOREIGN KEY (id_pedido) REFERENCES PEDIDO(id_pedido),
    FOREIGN KEY (id_cliente) REFERENCES CLIENTE(id_cliente)
);