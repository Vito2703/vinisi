<?php
require_once 'db.php';

// Excluir stock
if (isset($_GET['excluir'])) {
    $id_stock = intval($_GET['excluir']);
    $stmt = $conn->prepare("DELETE FROM stock_produto WHERE id_stock = ?");
    $stmt->bind_param("i", $id_stock);
    $stmt->execute();
    $stmt->close();
    header("Location: stock_produto.php");
    exit;
}

// Editar stock
if (isset($_POST['editar'])) {
    $id_stock = intval($_POST['id_stock']);
    $id_produto = intval($_POST['id_produto']);
    $quantidade = intval($_POST['quantidade']);
    $data_entrada = $_POST['data_ultima_entrada'];
    $data_saida = $_POST['data_ultima_saida'];
    $localizacao = $_POST['localizacao'];

    $stmt = $conn->prepare("UPDATE stock_produto 
                            SET id_produto=?, quantidade=?, data_ultima_entrada=?, data_ultima_saida=?, localizacao=? 
                            WHERE id_stock=?");
    $stmt->bind_param("iisssi", $id_produto, $quantidade, $data_entrada, $data_saida, $localizacao, $id_stock);
    $stmt->execute();
    $stmt->close();
    header("Location: stock_produto.php");
    exit;
}

// Adicionar stock
if (isset($_POST['adicionar'])) {
    $id_produto = intval($_POST['id_produto']);
    $quantidade = intval($_POST['quantidade']);
    $data_entrada = $_POST['data_ultima_entrada'];
    $data_saida = $_POST['data_ultima_saida'];
    $localizacao = $_POST['localizacao'];

    if ($id_produto && $quantidade && $data_entrada && $localizacao) {
        $stmt = $conn->prepare("INSERT INTO stock_produto (id_produto, quantidade, data_ultima_entrada, data_ultima_saida, localizacao) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $id_produto, $quantidade, $data_entrada, $data_saida, $localizacao);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: stock_produto.php");
    exit;
}

// Buscar dados
$result = $conn->query("SELECT s.*, p.nome_vinho FROM stock_produto s 
                        JOIN produto p ON s.id_produto = p.id_produto");

$produtos = $conn->query("SELECT id_produto, nome_vinho FROM produto");

// Ver se está em modo de edição
$stock_edit = null;
if (isset($_GET['editar'])) {
    $id_edit = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM stock_produto WHERE id_stock = $id_edit");
    if ($res->num_rows > 0) {
        $stock_edit = $res->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestão de Stock</title>
    <style>
        table {border-collapse: collapse; width: 90%; margin-bottom: 30px;}
        th, td {border: 1px solid #999; padding: 8px;}
        input, select {padding: 5px; margin-bottom: 10px;}
    </style>
</head>
<body>
    <h1>Gestão de Stock</h1>

    <?php if ($stock_edit): ?>
        <h2>Editar Stock</h2>
        <form method="post">
            <input type="hidden" name="id_stock" value="<?= $stock_edit['id_stock'] ?>">

            <label>Produto:</label><br>
            <select name="id_produto" required>
                <?php while ($p = $produtos->fetch_assoc()): ?>
                    <option value="<?= $p['id_produto'] ?>" <?= ($p['id_produto'] == $stock_edit['id_produto']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nome_vinho']) ?>
                    </option>
                <?php endwhile; ?>
            </select><br>

            <label>Quantidade:</label><br>
            <input type="number" name="quantidade" value="<?= $stock_edit['quantidade'] ?>" required><br>

            <label>Data Última Entrada:</label><br>
            <input type="date" name="data_ultima_entrada" value="<?= $stock_edit['data_ultima_entrada'] ?>" required><br>

            <label>Data Última Saída:</label><br>
            <input type="date" name="data_ultima_saida" value="<?= $stock_edit['data_ultima_saida'] ?>"><br>

            <label>Localização:</label><br>
            <input type="text" name="localizacao" value="<?= htmlspecialchars($stock_edit['localizacao']) ?>" required><br>

            <input type="submit" name="editar" value="Salvar Alterações">
            <a href="stock_produto.php">Cancelar</a>
        </form>

    <?php else: ?>
        <h2>Adicionar Stock</h2>
        <form method="post">
            <label>Produto:</label><br>
            <select name="id_produto" required>
                <option value="">Selecione</option>
                <?php while ($p = $produtos->fetch_assoc()): ?>
                    <option value="<?= $p['id_produto'] ?>"><?= htmlspecialchars($p['nome_vinho']) ?></option>
                <?php endwhile; ?>
            </select><br>

            <label>Quantidade:</label><br>
            <input type="number" name="quantidade" required><br>

            <label>Data Última Entrada:</label><br>
            <input type="date" name="data_ultima_entrada" required><br>

            <label>Data Última Saída:</label><br>
            <input type="date" name="data_ultima_saida"><br>

            <label>Localização:</label><br>
            <input type="text" name="localizacao" required><br>

            <input type="submit" name="adicionar" value="Adicionar Stock">
        </form>
    <?php endif; ?>

    <h2>Lista de Stock</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Última Entrada</th>
            <th>Última Saída</th>
            <th>Localização</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_stock'] ?></td>
                <td><?= htmlspecialchars($row['nome_vinho']) ?></td>
                <td><?= $row['quantidade'] ?></td>
                <td><?= $row['data_ultima_entrada'] ?></td>
                <td><?= $row['data_ultima_saida'] ?></td>
               
                <td>
                    <a href="?editar=<?= $row['id_stock'] ?>">Editar</a> |
                    <a href="?excluir=<?= $row['id_stock'] ?>" onclick="return confirm('Excluir este registo de stock?')">Excluir</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

