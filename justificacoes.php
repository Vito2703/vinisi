<?php
require_once 'db.php';

if (!isset($_GET['id'])) {
    echo "ID da ocorrência não fornecido.";
    exit;
}

$id_ocorrencia = intval($_GET['id']);

// Buscar os dados da ocorrência
$sql = "SELECT o.id_ocorrencia, o.id_encomenda, o.data_registo, o.data_resolucao,
               o.descricao, o.estado, o.motivo, c.nome AS cliente_nome, e.data_encomenda
        FROM ocorrencia o
        LEFT JOIN encomenda_cliente e ON o.id_encomenda = e.id_encomenda
        LEFT JOIN cliente c ON e.id_cliente = c.id_cliente
        WHERE o.id_ocorrencia = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_ocorrencia);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Ocorrência não encontrada.";
    exit;
}

$ocorrencia = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Detalhes da Ocorrência</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      /* Fundo com imagem */
      background-image: url('imagens/ocorrencia.jpg'); /* Altera para o caminho da tua imagem */
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      margin: 0;
      padding: 30px;
      min-height: 100vh;
    }

    .container {
      background: white;
      padding: 25px;
      border-radius: 8px;
      max-width: 700px;
      margin: auto;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    h2 {
      color: #333;
    }

    p {
      margin: 10px 0;
      color: #555;
    }

    .label {
      font-weight: bold;
      color: #222;
    }

    .btn-voltar {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 16px;
      background-color: #5a67d8;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .btn-voltar:hover {
      background-color: #434190;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📄 Detalhes da Ocorrência #<?= htmlspecialchars($ocorrencia['id_ocorrencia']) ?></h2>
    
    <p><span class="label">Cliente:</span> <?= htmlspecialchars($ocorrencia['cliente_nome']) ?></p>
    <p><span class="label">ID Encomenda:</span> <?= htmlspecialchars($ocorrencia['id_encomenda']) ?></p>
    <p><span class="label">Data da Encomenda:</span> <?= htmlspecialchars($ocorrencia['data_encomenda']) ?></p>
    <p><span class="label">Data do Registo:</span> <?= htmlspecialchars($ocorrencia['data_registo']) ?></p>
    <p><span class="label">Motivo:</span> <?= htmlspecialchars($ocorrencia['motivo']) ?></p>
    <p><span class="label">Descrição do Cliente:</span><br> <?= nl2br(htmlspecialchars($ocorrencia['descricao'])) ?></p>
    <p><span class="label">Estado Atual:</span> <?= htmlspecialchars($ocorrencia['estado']) ?></p>
    <p><span class="label">Data de Resolução:</span> <?= $ocorrencia['data_resolucao'] ? htmlspecialchars($ocorrencia['data_resolucao']) : "⏳ Aguardando" ?></p>

    <a href="Gocorrencias.php" class="btn-voltar">⬅️ Voltar à Gestão de Ocorrências</a>
  </div>
</body>
</html>
