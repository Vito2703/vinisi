<?php
require_once 'db.php';

// BUSCAR ENCOMENDAS
$sql = "SELECT e.*, c.nome, c.morada AS morada_cliente FROM encomenda_cliente e 
        LEFT JOIN cliente c ON e.id_cliente = c.id_cliente
        WHERE e.estado_registo = 'ativo' ORDER BY e.id_encomenda DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Backoffice - Encomendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('imagens/adega.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
            margin: 0;
        }

        h1 {
            background-color: rgba(0, 0, 0, 0.6);
            color: #ffffff;
            padding: 15px 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #343a40;
            color: white;
        }

        tr {
            background-color: white;
        }

        .btn {
            background-color: #5a67d8;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
        }

        .btn:hover {
            background-color: #434190;
        }
    </style>
</head>
<body>
    <h1>Gestão de Encomendas</h1>
    <table>
        <thead>
            <tr>
                <th>ID Encomenda</th>
                <th>Cliente</th>
                <th>Data Encomenda</th>
                <th>Morada Cliente</th>
                <th>Tracking ID</th>
                <th>Estado Atual</th>
                <th>Transportadora</th>
                <th>Produtos</th> <!-- ✅ Nova coluna -->
            </tr>
        </thead>
        <tbody>
            <?php
            $trackingIds = [];
            if ($result && $result->num_rows > 0) {
                while ($encomenda = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($encomenda['id_encomenda']) . "</td>";
                    echo "<td>" . htmlspecialchars($encomenda['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($encomenda['data_encomenda']) . "</td>";
                    echo "<td>" . htmlspecialchars($encomenda['morada_cliente']) . "</td>";
                    echo "<td>" . htmlspecialchars($encomenda['tracking_id']) . "</td>";

                    if (!empty($encomenda['tracking_id'])) {
                        $tracking_id = htmlspecialchars($encomenda['tracking_id']);
                        echo "<td><span id='estado-$tracking_id'>A carregar...</span></td>";
                        $trackingIds[] = $tracking_id;
                    } else {
                        echo "<td>Sem tracking</td>";
                    }

                    echo "<td>" . (!empty($encomenda['transportadora']) ? htmlspecialchars($encomenda['transportadora']) : 'N/D') . "</td>";

                    // ✅ Botão Ver Produtos
                    echo "<td><a class='btn' href='detalhes_encomenda.php?id_encomenda={$encomenda['id_encomenda']}'>Ver Produtos</a></td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Nenhuma encomenda encontrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>

<script>
const trackingIds = <?php echo json_encode($trackingIds); ?>;

function atualizarEstado(tracking_id) {
    fetch('tracking_api.php?tracking_id=' + encodeURIComponent(tracking_id))
        .then(res => res.json())
        .then(data => {
            if (!data.erro) {
                const el = document.getElementById('estado-' + tracking_id);
                if (el) {
                    el.textContent = data.estado_atual + " (Atualizado às " + data.ultima_atualizacao + ")";
                }
            } else {
                console.error("Erro tracking API:", data.erro);
            }
        })
        .catch(err => console.error('Erro ao atualizar estado:', err));
}

function atualizarTodosEstados() {
    trackingIds.forEach(atualizarEstado);
}

atualizarTodosEstados();
setInterval(atualizarTodosEstados, 300000);
</script>
</body>
</html>
