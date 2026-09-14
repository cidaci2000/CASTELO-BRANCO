<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$pdo = getPDO();

$filmes = $pdo->query("
    SELECT
        f.id, f.titulo, f.ano, f.descricao, f.genero, f.url_imagem,
        COUNT(a.id)             AS total_avaliacoes,
        ROUND(AVG(a.nota), 2)   AS media_nota
    FROM filmes f
    LEFT JOIN avaliacoes a ON a.filme_id = f.id
    GROUP BY f.id, f.titulo, f.ano, f.descricao, f.genero, f.url_imagem
    ORDER BY f.titulo ASC
")->fetchAll();

jsonResponse(['success' => true, 'filmes' => $filmes]);