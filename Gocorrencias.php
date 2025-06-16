<?php

require_once 'db.php';

$sql = "SELECT o.id_ocorrencia, o.id_encomenda, o.motivo, o.descricao, o.data_registo, o.estado,
               c.nome AS cliente_nome
        FROM ocorrencia o
        JOIN encomenda_cliente ec ON o.id_encomenda = ec.id_encomenda
        JOIN cliente c ON ec.id_cliente = c.id_cliente
        ORDER BY o.data_registo DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Ocorrências</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* Fundo com imagem */
            background-image: url('imagens/ocorrencia.jpg'); /* altera para o caminho da tua imagem */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            /* Para evitar esticar verticalmente */
            background-attachment: fixed;
            padding: 30px;
            margin: 0;
            min-height: 100vh;
        }
        /* Para melhor leitura, colocar um fundo branco sem transparência na container */
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }
        th {
            background-color: #e2e8f0;
        }
        .btn {
            background-color: #5a67d8;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #434190;
        }
        h1 {
            margin-top: 0;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>📋 Gestão de Ocorrências</h1>
    <?php if ($result->num_rows === 0): ?>
        <p>Não existem ocorrências registadas.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Encomenda</th>
                    <th>Motivo</th>
                    <th>Descrição</th>
                    <th>Data</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_ocorrencia']) ?></td>
                        <td><?= htmlspecialchars($row['cliente_nome']) ?></td>
                        <td>#<?= htmlspecialchars($row['id_encomenda']) ?></td>
                        <td><?= htmlspecialchars($row['motivo']) ?></td>
                        <td><?= htmlspecialchars($row['descricao']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['data_registo'])) ?></td>
                        <td><?= htmlspecialchars($row['estado']) ?></td>
                        <td><a class="btn" href="justificacoes.php?id=<?= $row['id_ocorrencia'] ?>">🔍 Ver</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
