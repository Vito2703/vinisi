<?php
session_start();
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Se o carrinho ainda não existir, cria-o
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    // Adiciona ou incrementa o produto no carrinho
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]++;
    } else {
        $_SESSION['carrinho'][$id] = 1;
    }
}

header('Location: carrinho.php');
exit;
