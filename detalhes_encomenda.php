<?php
require_once 'db.php';

$id = intval($_GET['id_encomenda'] ?? 0);

$sql = "SELECT p.nome_vinho AS nome, ep.quantidade, ep.valor_unitario
        FROM encomenda_cliente_produto ep
        JOIN produto p ON ep.id_produto = p.id_produto
        WHERE ep.id_encomenda = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Encomenda #<?= $id ?></title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 40px; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 0 10px #ccc; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background: #343a40; color: white; }
    </style>
</head>
<body>
    <h2>📦 Detalhes da Encomenda #<?= $id ?></h2>
    <table>
        <thead>
            <tr><th>Produto</th><th>Quantidade</th><th>Preço Unitário</th><th>Total</th></tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        while ($row = $res->fetch_assoc()) {
            $subtotal = $row['quantidade'] * $row['valor_unitario'];
            $total += $subtotal;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
            echo "<td>{$row['quantidade']}</td>";
            echo "<td>" . number_format($row['valor_unitario'], 2, ',', '.') . " €</td>";
            echo "<td>" . number_format($subtotal, 2, ',', '.') . " €</td>";
            echo "</tr>";
        }
        echo "<tr><td colspan='3'><strong>Total</strong></td><td><strong>" . number_format($total, 2, ',', '.') . " €</strong></td></tr>";
        ?>
        </tbody>
    </table>
</body>
</html>
