<?php
require_once 'db.php';

if (!isset($_GET['id'])) {
    header('Location: template.php');
    exit;
}

$id = intval($_GET['id']);

$sql = "
SELECT p.*, c.nome_casta, s.quantidade
FROM produto p
LEFT JOIN produto_casta pc ON p.id_produto = pc.id_produto
LEFT JOIN casta c ON pc.id_casta = c.id_casta
LEFT JOIN stock_produto s ON p.id_produto = s.id_produto
WHERE p.id_produto = ? AND p.estado_registo = 'ativo'
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Produto não encontrado.";
    exit;
}

$produto = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($produto['nome_vinho']) ?> - Detalhes</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 20px;
    }

    .voltar {
      display: inline-block;
      margin-bottom: 20px;
      background-color: #ccc;
      color: black;
      text-decoration: none;
      padding: 8px 14px;
      border-radius: 6px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .voltar:hover {
      background-color: #aaa;
    }

    .produto-detalhe {
      background: white;
      padding: 25px;
      border-radius: 8px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      text-align: center;
    }

    .produto-detalhe img {
     max-width: 100%;
    max-height: 250px; /* Limita altura */
    object-fit: contain;
    border-radius: 6px;
    margin-bottom: 20px;
    }


    .produto-detalhe p {
      font-size: 16px;
      margin: 8px 0;
      color: #444;
    }

    .produto-detalhe strong {
      color: #000;
    }

    .btn-comprar {
      background-color: #2a7ae2;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
    }

    .btn-comprar:hover {
      background-color: #1a5bcc;
    }

    input[type="number"] {
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 60px;
      text-align: center;
    }
  </style>
</head>
<body>

<a href="template.php" class="voltar">⬅ Voltar</a>

<div class="produto-detalhe">
  <h1><?= htmlspecialchars($produto['nome_vinho']) ?></h1>
  <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem do vinho">
  <p><strong>Região:</strong> <?= htmlspecialchars($produto['regiao']) ?></p>
  <p><strong>Tipo:</strong> <?= htmlspecialchars($produto['tipo_vinho']) ?></p>
  <p><strong>Casta:</strong> <?= htmlspecialchars($produto['nome_casta']) ?></p>
  <p><strong>Preço:</strong> <?= number_format($produto['valor'], 2, ',', '.') ?> €</p>
  <p><strong>Stock disponível:</strong> <?= intval($produto['quantidade']) ?></p>

  <form action="carrinho.php" method="post">
    <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
    <label for="quantidade"><strong>Quantidade:</strong></label>
    <input type="number" name="quantidade" min="1" max="<?= intval($produto['quantidade']) ?>" value="1" required>
    <br>
    <button type="submit" class="btn-comprar">Adicionar ao Carrinho</button>
  </form>
</div>

</body>
</html>
