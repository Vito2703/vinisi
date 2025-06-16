<?php
require_once 'db.php';

if (isset($_POST['editar'])) {
    $id_edit = intval($_POST['id_cliente']);
    $nome = $_POST['nome'];
    $nif = $_POST['nif'];
    $morada = $_POST['morada'];
    $genero = $_POST['genero'];

    if (empty($nome) || empty($nif) || empty($morada) || empty($genero)) {
        $erro = "Por favor preencha todos os campos.";
    } else {
        $sql_upd = "UPDATE cliente SET nome=?, nif=?, morada=?, genero=? WHERE id_cliente=?";
        $stmt = $conn->prepare($sql_upd);
        $stmt->bind_param("ssssi", $nome, $nif, $morada, $genero, $id_edit);
        $stmt->execute();
        $stmt->close();
        header("Location: clientes.php");
        exit;
    }
}

$sql = "SELECT * FROM cliente ORDER BY id_cliente DESC";
$result = $conn->query($sql);

$cliente_edit = null;
if (isset($_GET['editar'])) {
    $id_edit = intval($_GET['editar']);
    $res_edit = $conn->query("SELECT * FROM cliente WHERE id_cliente = $id_edit LIMIT 1");
    if ($res_edit && $res_edit->num_rows > 0) {
        $cliente_edit = $res_edit->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestão de Clientes</title>
    <style>
        body {
            background: url('imagens/clientes.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #333;
        }

        h1, h2 {
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            display: block;
            width: fit-content;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
            margin: 0 0 20px 0;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin-bottom: 30px;
            background-color: white;
            color: black;
        }

        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }

        form {
            margin-bottom: 30px;
        }

        label {
            color: white;
            font-weight: bold;
        }

        input[type=text], select {
            width: 300px;
            padding: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        input[type=submit] {
            padding: 8px 15px;
            border: none;
            background-color: #2b6cb0;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #2c5282;
        }

        .btn-editar {
            padding: 6px 12px;
            background-color: #3182ce;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-editar:hover {
            background-color: #2b6cb0;
        }

        .btn-cancelar {
            display: inline-block;
            padding: 8px 15px;
            background-color: #e53e3e;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin-left: 15px;
            transition: background 0.3s;
        }

        .btn-cancelar:hover {
            background-color: #c53030;
        }

        .erro {
            color: red;
            background-color: rgba(255, 0, 0, 0.1);
            padding: 10px;
            border-radius: 6px;
            width: fit-content;
        }
    </style>
</head>
<body>

    <h1>Gestão de Clientes</h1>

    <?php if (!empty($erro)): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <?php if ($cliente_edit): ?>
        <h2>Editar Cliente</h2>
        <form method="post" action="clientes.php">
            <input type="hidden" name="id_cliente" value="<?= $cliente_edit['id_cliente'] ?>">
            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?= htmlspecialchars($cliente_edit['nome']) ?>" required><br><br>

            <label>NIF:</label><br>
            <input type="text" name="nif" value="<?= htmlspecialchars($cliente_edit['nif']) ?>" required><br><br>

            <label>Morada:</label><br>
            <input type="text" name="morada" value="<?= htmlspecialchars($cliente_edit['morada']) ?>" required><br><br>

            <label>Género:</label><br>
            <select name="genero" required>
                <option value="">--Selecionar--</option>
                <option value="Masculino" <?= $cliente_edit['genero'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                <option value="Feminino" <?= $cliente_edit['genero'] === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                <option value="Outro" <?= $cliente_edit['genero'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
            </select><br><br>

            <input type="submit" name="editar" value="Salvar Alterações">
            <a href="clientes.php" class="btn-cancelar">Cancelar</a>
        </form>
    <?php endif; ?>

    <h2>Lista de Clientes</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>NIF</th>
            <th>Morada</th>
            <th>Género</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_cliente'] ?></td>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= htmlspecialchars($row['nif']) ?></td>
            <td><?= htmlspecialchars($row['morada']) ?></td>
            <td><?= htmlspecialchars($row['genero']) ?></td>
            <td>
                <a class="btn-editar" href="clientes.php?editar=<?= $row['id_cliente'] ?>">Editar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
