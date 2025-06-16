<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}

$id_cliente = $_SESSION['cliente_id'];

$id_encomenda = isset($_GET['id_encomenda']) ? intval($_GET['id_encomenda']) : 0;
if ($id_encomenda <= 0) {
    echo "❌ Encomenda não especificada.";
    exit;
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $motivo = $_POST['motivo'];
    $descricao = $_POST['descricao'];
    $registado_por = $_SESSION['cliente_id'];

    $stmt = $conn->prepare("INSERT INTO ocorrencia 
        (id_encomenda, motivo, descricao, data_registo, registado_por, estado, estado_registo) 
        VALUES (?, ?, ?, NOW(), ?, 'pendente', 'ativo')");
    $stmt->bind_param("issi", $id_encomenda, $motivo, $descricao, $registado_por);

    if ($stmt->execute()) {
        $mensagem = '<div class="mensagem sucesso"> Obrigado pela ocorrência. Estamos a analisar o seu pedido.</div>';
        $mensagem .= "<script>
            setTimeout(function() {
                window.location.href = 'area_cliente.php';
            }, 3000);
        </script>";
    } else {
        $mensagem = '<div class="mensagem erro">❌ Erro ao registar ocorrência: ' . htmlspecialchars($stmt->error) . '</div>';
    }

    $stmt->close();
}

$id_encomenda = $_GET['id_encomenda'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Reportar Ocorrência</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: url('imagens/vinhoderramado.jpg') no-repeat center center fixed; 
            background-size: cover; 
            padding: 40px; 
            margin: 0;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9); 
            max-width: 600px; 
            margin: auto; 
            padding: 30px;
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #333; margin-bottom: 20px; }
        label { display: block; margin-top: 15px; color: #333; font-weight: bold; }
        textarea, select {
            width: 100%; padding: 10px; border: 1px solid #ccc;
            border-radius: 6px; resize: vertical;
        }
        .btn {
            display: inline-block; margin-top: 20px; background-color: #e53e3e;
            color: white; padding: 10px 20px; border-radius: 6px;
            text-decoration: none; border: none; cursor: pointer;
            font-size: 16px;
        }
        .btn:hover { background-color: #c53030; }

        .mensagem {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .mensagem.sucesso {
            background-color: rgba(255, 255, 255, 0.95);
            color: #2f855a;
            border: 2px solid #38a169;
        }
        .mensagem.erro {
            background-color: rgba(255, 255, 255, 0.95);
            color: #c53030;
            border: 2px solid #e53e3e;
        }
    </style>
</head>
<body>
    <?= $mensagem ?>
    <div class="form-container">
        <h1>⚠️ Reportar Ocorrência da Encomenda #<?= htmlspecialchars($id_encomenda) ?></h1>
        <form method="POST" action="">
            <label for="motivo">Motivo:</label>
            <select name="motivo" id="motivo" required>
                <option value="">-- Selecione --</option>
                <option value="danos nos produtos">Danos nos produtos</option>
                <option value="quantidades incorretas">Quantidades incorretas</option>
                <option value="falhas na entrega">Falhas na entrega</option>
            </select>

            <label for="descricao">Descrição detalhada:</label>
            <textarea name="descricao" id="descricao" rows="5" required></textarea>

            <input type="submit" value="Submeter Ocorrência" class="btn">
        </form>
    </div>
</body>
</html>
