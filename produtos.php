<?php
require_once 'db.php';

// EXCLUIR PRODUTO
if (isset($_POST['excluir'])) {
    $id_excluir = intval($_POST['excluir']);

    $stmt_del_pc = $conn->prepare("DELETE FROM produto_casta WHERE id_produto = ?");
    $stmt_del_pc->bind_param("i", $id_excluir);
    $stmt_del_pc->execute();
    $stmt_del_pc->close();

    $stmt_del_stock = $conn->prepare("DELETE FROM stock_produto WHERE id_produto = ?");
    $stmt_del_stock->bind_param("i", $id_excluir);
    $stmt_del_stock->execute();
    $stmt_del_stock->close();

    $stmt_del = $conn->prepare("DELETE FROM produto WHERE id_produto = ?");
    $stmt_del->bind_param("i", $id_excluir);
    $stmt_del->execute();
    $stmt_del->close();

    header("Location: produtos.php");
    exit;
}

$castas_result = $conn->query("SELECT * FROM casta ORDER BY nome_casta ASC");
$castas = [];
while ($c = $castas_result->fetch_assoc()) {
    $castas[] = $c;
}

$produto_para_editar = null;
if (isset($_GET['editar'])) {
    $id_editar = intval($_GET['editar']);
    $stmt = $conn->prepare("
        SELECT p.*, s.quantidade 
        FROM produto p 
        LEFT JOIN stock_produto s ON p.id_produto = s.id_produto 
        WHERE p.id_produto = ?
    ");
    $stmt->bind_param("i", $id_editar);
    $stmt->execute();
    $result_editar = $stmt->get_result();
    if ($result_editar->num_rows > 0) {
        $produto_para_editar = $result_editar->fetch_assoc();
    }
    $stmt->close();
}

$erro_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_vinho = $_POST['nome_vinho'] ?? '';
    $regiao = $_POST['regiao'] ?? '';
    $valor = floatval($_POST['valor'] ?? 0);
    $id_casta = intval($_POST['casta'] ?? 0);
    $quantidade = intval($_POST['quantidade'] ?? 0);
    $tipo_vinho = $_POST['tipo_vinho'] ?? '';
    $criado_por = "teste";

    $imagem = $produto_para_editar['imagem'] ?? '';
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $upload_dir = "imagens/";
        $extensoes_validas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $nome_ficheiro = basename($_FILES['imagem']['name']);
        $extensao = strtolower(pathinfo($nome_ficheiro, PATHINFO_EXTENSION));

        if (in_array($extensao, $extensoes_validas)) {
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $nome_final = uniqid() . "." . $extensao;
            $caminho_final = $upload_dir . $nome_final;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_final)) {
                $imagem = $caminho_final;
            } else {
                $erro_msg = "Falha ao mover a imagem para o destino.";
            }
        } else {
            $erro_msg = "Extensão de imagem inválida.";
        }
    }

    if (empty($erro_msg)) {
        if (isset($_POST['id_editar'])) {
            $id_editar = intval($_POST['id_editar']);
            $stmt = $conn->prepare("UPDATE produto SET nome_vinho=?, regiao=?, valor=?, imagem=?, tipo_vinho=? WHERE id_produto=?");
            $stmt->bind_param("ssdssi", $nome_vinho, $regiao, $valor, $imagem, $tipo_vinho, $id_editar);
            $stmt->execute();
            $stmt->close();

            $stmt_stock = $conn->prepare("UPDATE stock_produto SET quantidade=?, data_ultima_entrada=NOW() WHERE id_produto=?");
            $stmt_stock->bind_param("ii", $quantidade, $id_editar);
            $stmt_stock->execute();
            $stmt_stock->close();

            header("Location: produtos.php");
            exit;
        } else {
            $stmt = $conn->prepare("INSERT INTO produto (nome_vinho, regiao, valor, imagem, tipo_vinho, criado_por, data_criacao, estado_registo) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'ativo')");
            $stmt->bind_param("ssdsss", $nome_vinho, $regiao, $valor, $imagem, $tipo_vinho, $criado_por);

            if ($stmt->execute()) {
                $id_novo = $stmt->insert_id;

                $stmt_casta = $conn->prepare("INSERT INTO produto_casta (id_produto, id_casta) VALUES (?, ?)");
                $stmt_casta->bind_param("ii", $id_novo, $id_casta);
                $stmt_casta->execute();
                $stmt_casta->close();

                $stmt_stock = $conn->prepare("INSERT INTO stock_produto (id_produto, quantidade, data_ultima_entrada, criado_por, data_criacao) VALUES (?, ?, NOW(), ?, NOW())");
                $stmt_stock->bind_param("iis", $id_novo, $quantidade, $criado_por);
                $stmt_stock->execute();
                $stmt_stock->close();

                $stmt->close();

                header("Location: produtos.php");
                exit;
            } else {
                $erro_msg = "Erro ao inserir produto: " . $stmt->error;
            }
        }
    }
}

$sql = "
SELECT p.*, c.nome_casta, s.quantidade
FROM produto p
LEFT JOIN produto_casta pc ON p.id_produto = pc.id_produto
LEFT JOIN casta c ON pc.id_casta = c.id_casta
LEFT JOIN stock_produto s ON p.id_produto = s.id_produto
WHERE p.estado_registo = 'ativo'
ORDER BY p.id_produto DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Produtos</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .produto-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            margin: 10px;
            width: 240px;
            float: left;
            text-align: center;
            background: rgba(255, 255, 255, 0.85);
        }

        .produto-card img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        form {
            margin: 5px 0;
        }

        .secao-adicionar {
            background-image: url('imagens/adicionar.jpg');
            background-size: cover;
            background-position: center;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 40px;
            color: #fff;
        }

        .secao-lista {
            background-image: url('imagens/lista2.jpg');
            background-size: cover;
            background-position: center;
            padding: 20px;
            border-radius: 12px;
            overflow: hidden;
        }

        .secao-adicionar h1,
        .secao-lista h2 {
            background: rgba(255, 255, 255, 0.8);
            color: #000;
            padding: 10px;
            border-radius: 8px;
            display: inline-block;
        }

        .secao-adicionar form label {
            font-weight: bold;
            color: #fff;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
            background-color: rgba(0, 0, 0, 0.5);
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            margin-bottom: 4px;
        }

        .secao-adicionar form {
            background-color: rgba(0, 0, 0, 0.4);
            padding: 20px;
            border-radius: 12px;
        }

        form input, form select {
            margin-bottom: 10px;
            padding: 6px;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        button {
            margin-top: 10px;
            padding: 8px 14px;
        }
    </style>
</head>
<body>

<div class="secao-adicionar">
    <h1><?= $produto_para_editar ? "Editar Produto" : "Adicionar Produto" ?></h1>
    <?php if (!empty($erro_msg)): ?>
        <p style="color:red;"><?= htmlspecialchars($erro_msg) ?></p>
    <?php endif; ?>
    <form action="produtos.php" method="post" enctype="multipart/form-data">
        <?php if ($produto_para_editar): ?>
            <input type="hidden" name="id_editar" value="<?= $produto_para_editar['id_produto'] ?>">
        <?php endif; ?>

        <label>Nome do Vinho:</label><br>
        <input type="text" name="nome_vinho" required value="<?= htmlspecialchars($produto_para_editar['nome_vinho'] ?? '') ?>"><br>

        <label>Região:</label><br>
        <input type="text" name="regiao" required value="<?= htmlspecialchars($produto_para_editar['regiao'] ?? '') ?>"><br>

        <label>Valor (€):</label><br>
        <input type="number" step="0.01" name="valor" required value="<?= htmlspecialchars($produto_para_editar['valor'] ?? '') ?>"><br>

        <label>Imagem:</label><br>
        <input type="file" name="imagem" accept="image/*"><br>

        <label>Tipo de Vinho:</label><br>
        <select name="tipo_vinho" required>
            <option value="">-- Selecione --</option>
            <?php foreach (["Tinto", "Branco", "Rosé", "Espumante"] as $tipo): ?>
                <option value="<?= $tipo ?>" <?= (isset($produto_para_editar['tipo_vinho']) && $produto_para_editar['tipo_vinho'] == $tipo) ? 'selected' : '' ?>>
                    <?= $tipo ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Casta:</label><br>
        <select name="casta" required>
            <option value="">-- Selecione --</option>
            <?php foreach ($castas as $casta): ?>
                <option value="<?= $casta['id_casta'] ?>"
                    <?= (isset($produto_para_editar['id_casta']) && $produto_para_editar['id_casta'] == $casta['id_casta']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($casta['nome_casta']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Quantidade em Stock:</label><br>
        <input type="number" name="quantidade" min="0" required value="<?= htmlspecialchars($produto_para_editar['quantidade'] ?? 0) ?>"><br><br>

        <?php if ($produto_para_editar): ?>
            <button type="submit" name="adicionar">Guardar Alterações</button>
            <a href="produtos.php"><button type="button">Cancelar Edição</button></a>
        <?php else: ?>
            <button type="submit" name="adicionar">Adicionar Produto</button>
        <?php endif; ?>
    </form>
</div>

<hr style="clear:both;">

<div class="secao-lista">
    <h2>Lista de Produtos (Backoffice)</h2>
    <div>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="produto-card">
                <?php if (!empty($row['imagem']) && file_exists($row['imagem'])): ?>
                    <img src="<?= htmlspecialchars($row['imagem']) ?>" alt="Imagem">
                <?php else: ?>
                    <img src="placeholder.jpg" alt="Sem imagem">
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['nome_vinho']) ?></h3>
                <p><strong>Região:</strong> <?= htmlspecialchars($row['regiao']) ?></p>
                <p><strong>Valor:</strong> €<?= number_format($row['valor'], 2) ?></p>
                <p><strong>Casta:</strong> <?= htmlspecialchars($row['nome_casta'] ?? '-') ?></p>
                <p><strong>Tipo:</strong> <?= htmlspecialchars($row['tipo_vinho'] ?? '-') ?></p>
                <p><strong>Stock:</strong> <?= intval($row['quantidade'] ?? 0) ?> unidade(s)</p>
                <form action="produtos.php" method="post" onsubmit="return confirm('Deseja mesmo eliminar este produto?');" style="display:inline-block;">
                    <input type="hidden" name="excluir" value="<?= intval($row['id_produto']) ?>">
                    <button type="submit">Excluir</button>
                </form>
                <form action="produtos.php" method="get" style="display:inline-block;">
                    <input type="hidden" name="editar" value="<?= intval($row['id_produto']) ?>">
                    <button type="submit">Editar</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
