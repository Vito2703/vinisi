<?php
session_start();
header('Content-Type: application/json');

// Validar id_produto e adicionar ao $_SESSION['carrinho']
// Retornar: ['success' => true] ou ['success' => false, 'message' => 'Explicação do erro']

// Exemplo mínimo:
if(!isset($_POST['id_produto'])) {
    echo json_encode(['success' => false, 'message' => 'Produto não especificado']);
    exit;
}

$id = intval($_POST['id_produto']);
if(!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if(isset($_SESSION['carrinho'][$id])) {
    echo json_encode(['success' => false, 'message' => 'Produto já no carrinho']);
    exit;
}

// Aqui podes adicionar validações de stock se quiseres

$_SESSION['carrinho'][$id] = 1; // quantidade inicial 1
echo json_encode(['success' => true]);
?>

