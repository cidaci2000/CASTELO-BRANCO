<?php
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    /* ADICIONAR */
    if ($acao === 'adicionar') {
        $pid = (int)($_POST['produto_id'] ?? 0);
        $qtd = max(1, (int)($_POST['quantidade'] ?? 1));
        if ($pid) {
            adicionarCarrinho($pid, $qtd);
            setFlash('sucesso', 'Produto adicionado ao carrinho!');
        }
        redirect($_SERVER['HTTP_REFERER'] ?? 'produtos.php');
    }

    /* ATUALIZAR QUANTIDADES */
    if ($acao === 'atualizar') {
        foreach (($_POST['qtd'] ?? []) as $itemId => $q) {
            atualizarQuantidade((int)$itemId, (int)$q);
        }
        setFlash('sucesso', 'Carrinho atualizado.');
        redirect('carrinho.php');
    }

    /* REMOVER ITEM */
    if ($acao === 'remover') {
        $itemId = (int)($_POST['item_id'] ?? 0);
        if ($itemId) {
            removerItem($itemId);
            setFlash('sucesso', 'Item removido.');
        }
        redirect('carrinho.php');
    }

    /* LIMPAR CARRINHO */
    if ($acao === 'limpar') {
        limparCarrinho();
        setFlash('sucesso', 'Carrinho esvaziado.');
        redirect('carrinho.php');
    }

    /* FINALIZAR */
    if ($acao === 'finalizar') {
        $pedidoId = finalizarCompra([
            'forma_pagamento' => $_POST['forma_pagamento'] ?? 'a_definir',
            'observacoes'     => $_POST['observacoes'] ?? null,
            'frete'           => 0,
        ]);

        if ($pedidoId) {
            setFlash('sucesso', "Pedido #$pedidoId finalizado com sucesso! Obrigado 🌸");
            redirect('pedido_confirmado.php?id=' . $pedidoId);
        } else {
            
            redirect('home.php');
        }
    }
}

// Qualquer outro método: volta pro carrinho
redirect($_SERVER['HTTP_REFERER'] ?? 'produtos.php');