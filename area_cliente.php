<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}

$id_cliente = $_SESSION['cliente_id'];

// Buscar dados do cliente
$sql = "SELECT nome, email, nif, morada, genero FROM cliente WHERE id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Cliente não encontrado.";
    exit;
}

$cliente = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Área do Cliente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('imagens/areacliente.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #333; }
        .info { margin-bottom: 20px; }
        .info p { margin: 5px 0; }
        .btn {
            display: inline-block;
            background-color: #5a67d8;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn:hover { background-color: #434190; }
        .encomenda {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }
        .encomenda strong { color: #2d3748; }
        hr { margin: 20px 0; }
        .estado { margin-top: 8px; font-size: 0.95em; }
        .detalhes-link { margin-top: 10px; display: inline-block; }
    </style>
</head>
<body>
<div class="container">
    <h1>Área de Cliente</h1>

    <div class="info">
        <h2>Dados do Cliente</h2>
        <p><strong>Nome:</strong> <?= htmlspecialchars($cliente['nome']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($cliente['email']) ?></p>
        <p><strong>NIF:</strong> <?= htmlspecialchars($cliente['nif']) ?></p>
        <p><strong>Morada:</strong> <?= htmlspecialchars($cliente['morada']) ?></p>
        <p><strong>Género:</strong> <?= htmlspecialchars($cliente['genero']) ?></p>
    </div>

    <hr>

    <h2>Últimas Encomendas</h2>
    <?php
    $sql = "SELECT id_encomenda, data_encomenda, tracking_id, transportadora, estado FROM encomenda_cliente 
            WHERE id_cliente = ? ORDER BY data_encomenda DESC LIMIT 3";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<p>Ainda não efetuou nenhuma encomenda.</p>";
    } else {
        while ($encomenda = $result->fetch_assoc()) {
            echo "<div class='encomenda'>";
            echo "<strong>Encomenda #{$encomenda['id_encomenda']}</strong><br>";
            echo "<p><strong>Transportadora:</strong> " . htmlspecialchars($encomenda['transportadora']) . "</p>";
            
            $dataEncomenda = new DateTime($encomenda['data_encomenda'], new DateTimeZone('Europe/Lisbon'));
            echo "<p><strong>Data:</strong> " . htmlspecialchars($dataEncomenda->format('Y-m-d H:i:s')) . "</p>";
            echo "<p><strong>Tracking ID:</strong> " . htmlspecialchars($encomenda['tracking_id']) . "</p>";

            if ($encomenda['tracking_id']) {
                echo "<p class='estado' id='estado-tracking-{$encomenda['tracking_id']}'>A carregar estado...</p>";
            } else {
                echo "<p class='estado'>Sem tracking disponível.</p>";
            }

            echo "<a class='detalhes-link btn' href='detalhes_encomenda.php?id_encomenda={$encomenda['id_encomenda']}' target='_blank'>Ver detalhes da encomenda</a>";

            echo "<div style='text-align:right; margin-top: 10px;'>";
            echo "<a class='btn' href='ocorrencia.php?id_encomenda={$encomenda['id_encomenda']}'>Reportar Ocorrência</a>";
            echo "</div>";

            echo "</div>";
        }
    }

    $stmt->close();
    ?>

    <hr>
    <a href="template.php" class="btn">Voltar à loja</a>
</div>

<script>
function atualizarEstado(tracking_id) {
    fetch('tracking_api.php?tracking_id=' + encodeURIComponent(tracking_id))
        .then(response => response.json())
        .then(data => {
            if (!data.erro) {
                const estadoEl = document.getElementById('estado-tracking-' + tracking_id);
                if (estadoEl) {
                    estadoEl.innerHTML = '<strong>Estado:</strong> ' + data.estado_atual + '<br><strong>Atualização:</strong> ' + data.ultima_atualizacao;
                }
            }
        })
        .catch(err => {
            console.error('Erro ao atualizar estado:', err);
        });
}

const trackingIds = [
    <?php
    $stmt = $conn->prepare("SELECT tracking_id FROM encomenda_cliente WHERE id_cliente = ? ORDER BY data_encomenda DESC LIMIT 3");
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    $ids = [];
    while ($row = $result->fetch_assoc()) {
        if ($row['tracking_id']) {
            $ids[] = "'" . addslashes($row['tracking_id']) . "'";
        }
    }
    echo implode(",", $ids);
    ?>
];

function atualizarTodosEstados() {
    trackingIds.forEach(id => {
        atualizarEstado(id);
    });
}

atualizarTodosEstados();
setInterval(atualizarTodosEstados, 60000);
</script>

</body>
</html>
