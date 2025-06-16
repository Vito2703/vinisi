<?php
require_once 'db.php';

// Consulta todos os produtos associados às encomendas, com informações de cliente e produto
$sql = "
    SELECT 
        ecp.id_encomenda,
        c.nome AS nome_cliente,
        p.nome_vinho,
        ecp.quantidade,
        ecp.valor_unitario,
        ecp.valor_iva,
        ecp.valor_total
    FROM encomenda_cliente_produto AS ecp
    JOIN encomenda_cliente AS ec ON ecp.id_encomenda = ec.id_encomenda
    JOIN cliente AS c ON ec.id_cliente = c.id_cliente
    JOIN produto AS p ON ecp.id_produto = p.id_produto
    ORDER BY ecp.id_encomenda DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Produtos por Encomenda</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: url('imagens/armazem.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        h1 {
            text-align: center;
            background: rgba(0,0,0,0.6);
            padding: 15px;
            border-radius: 10px;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            margin: 30px auto;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: rgba(255, 255, 255, 0.2);
        }

        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <h1>Produtos por Encomenda</h1>

    <table>
        <tr>
            <th>ID Encomenda</th>
            <th>Cliente</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor Unitário (€)</th>
            <th>Valor IVA (€)</th>
            <th>Valor Total (€)</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_encomenda'] ?></td>
            <td><?= htmlspecialchars($row['nome_cliente']) ?></td>
            <td><?= htmlspecialchars($row['nome_vinho']) ?></td>
            <td><?= $row['quantidade'] ?></td>
            <td><?= number_format($row['valor_unitario'], 2, ',', '.') ?></td>
            <td><?= number_format($row['valor_iva'], 2, ',', '.') ?></td>
            <td><?= number_format($row['valor_total'], 2, ',', '.') ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
