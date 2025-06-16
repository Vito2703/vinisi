<?php
require_once 'db.php';

if (!isset($_GET['tracking_id'])) {
    echo json_encode(['erro' => 'Tracking ID não fornecido']);
    exit;
}

$tracking_id = $_GET['tracking_id'];

// Buscar encomenda
$stmt = $conn->prepare("SELECT data_encomenda FROM encomenda_cliente WHERE tracking_id = ?");
$stmt->bind_param("s", $tracking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['erro' => 'Tracking ID inválido']);
    exit;
}

$encomenda = $result->fetch_assoc();
$stmt->close();

// Definir timezone para Lisboa
date_default_timezone_set('Europe/Lisbon');

$dataEncomenda = new DateTime($encomenda['data_encomenda'], new DateTimeZone('Europe/Lisbon'));
$agora = new DateTime('now', new DateTimeZone('Europe/Lisbon'));

$interval = $dataEncomenda->diff($agora);
$minutos = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

if ($minutos < 5) {
    $estadoAtual = "Em preparação";
} elseif ($minutos < 10) {
    $estadoAtual = "Em trânsito";
} elseif ($minutos < 15) {
    $estadoAtual = "Em entrega";
} else {
    $estadoAtual = "Entregue";
}

// Atualizar o estado na base de dados
$updateStmt = $conn->prepare("UPDATE encomenda_cliente SET estado = ? WHERE tracking_id = ?");
$updateStmt->bind_param("ss", $estadoAtual, $tracking_id);
$updateStmt->execute();
$updateStmt->close();

echo json_encode([
    'estado_atual' => $estadoAtual,
    'ultima_atualizacao' => $agora->format('Y-m-d H:i:s')
]);
