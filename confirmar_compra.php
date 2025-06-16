<?php
session_start();
require_once 'db.php';

$erros = [];
$produtosAComprar = [];

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header('Location: carrinho.php');
    exit;
}

$id_cliente = $_SESSION['cliente_id'];
$ids = implode(',', array_map('intval', array_keys($_SESSION['carrinho'])));

// Buscar produtos e verificar stock
$sql = "SELECT p.id_produto, p.nome_vinho, s.quantidade as stock_atual, p.valor
        FROM produto p
        JOIN stock_produto s ON p.id_produto = s.id_produto
        WHERE p.id_produto IN ($ids)";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $id = $row['id_produto'];
    $quantidadeCarrinho = $_SESSION['carrinho'][$id];

    if ($quantidadeCarrinho > $row['stock_atual']) {
        $erros[] = "❌ Produto <strong>{$row['nome_vinho']}</strong> tem apenas <strong>{$row['stock_atual']}</strong> unidades disponíveis. Por favor ajuste a sua Encomenda.";
    } else {
        $produtosAComprar[] = [
            'id' => $id,
            'nome' => $row['nome_vinho'],
            'quantidade' => $quantidadeCarrinho,
            'stock_atual' => $row['stock_atual'],
            'preco' => $row['valor']
        ];
    }
}

if (empty($erros)) {
    // Gerar tracking_id simulado
    $tracking_id = 'TRK-' . strtoupper(bin2hex(random_bytes(4)));

    // Transportadoras disponíveis
    $transportadoras = ['CTT', 'DHL', 'DPD', 'MRW'];
    $transportadora = $transportadoras[array_rand($transportadoras)];

    // Definir timezone e obter data/hora atual
    date_default_timezone_set('Europe/Lisbon');
    $agora = new DateTime();
    $hoje = $agora->format('Y-m-d H:i:s');

    $morada = $conn->real_escape_string('Morada do Cliente'); // Podes adaptar para usar do cliente
    $prevista = date('Y-m-d', strtotime('+3 days'));
    $total_produto = array_sum(array_map(fn($p) => $p['preco'] * $p['quantidade'], $produtosAComprar));
    $total_iva = $total_produto * 0.23;
    $total_transporte = 5.00;
    $estado = 'Em processamento';

    $stmt = $conn->prepare("INSERT INTO encomenda_cliente (id_cliente, data_encomenda, morada_entrega, data_prevista_entrega, valor_total_produto, valor_total_transporte, valor_total_impostos, estado, tracking_id, criado_por, transportadora)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssddddsss", $id_cliente, $hoje, $morada, $prevista, $total_produto, $total_transporte, $total_iva, $estado, $tracking_id, $id_cliente, $transportadora);
    $stmt->execute();
    $id_encomenda = $stmt->insert_id;
    $stmt->close();

    foreach ($produtosAComprar as $produto) {
        $valor_total = $produto['preco'] * $produto['quantidade'];
        $valor_iva = $valor_total * 0.23;

        $stmt = $conn->prepare("INSERT INTO encomenda_cliente_produto (id_encomenda, id_produto, quantidade, valor_unitario, valor_iva, valor_total) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiddd", $id_encomenda, $produto['id'], $produto['quantidade'], $produto['preco'], $valor_iva, $valor_total);
        $stmt->execute();
        $stmt->close();

        $novoStock = $produto['stock_atual'] - $produto['quantidade'];
        $conn->query("UPDATE stock_produto SET quantidade = $novoStock WHERE id_produto = {$produto['id']}");
    }

    $_SESSION['carrinho'] = [];
    $mensagem = "✅ Compra efetuada com sucesso! Nº Encomenda: <strong>$id_encomenda</strong><br>Tracking ID: <strong>$tracking_id</strong><br>Transportadora: <strong>$transportadora</strong>";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Confirmação da Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('imagens/compraefetuada.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 40px;
            margin: 0;
        }
        .caixa {
            max-width: 600px;
            margin: auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .erro {
            background: #ffe6e6;
            color: #b20000;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .btn-voltar {
            display: inline-block;
            margin-top: 20px;
            background: #5a67d8;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
        .btn-voltar:hover {
            background: #434190;
        }
    </style>
</head>
<body>
<div class="caixa">
    <?php if (!empty($erros)): ?>
        <h1>⚠️ Problema na compra</h1>
        <?php foreach ($erros as $erro): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endforeach; ?>
        <a href="carrinho.php" class="btn-voltar"> Voltar ao Carrinho</a>
    <?php else: ?>
        <h1><?= $mensagem ?></h1>
        <p>Poderás acompanhar o estado da entrega na Área de Cliente.</p>
        <a href="template.php" class="btn-voltar"> Voltar ao catálogo</a>
    <?php endif; ?>
</div>
</body>
</html>
