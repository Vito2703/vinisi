<?php
session_start();
require_once 'db.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';
    $nif = trim($_POST['nif'] ?? '');
    $morada = trim($_POST['morada'] ?? '');
    $genero = $_POST['genero'] ?? '';

    if (!$nome || !$email || !$senha || !$confirma_senha || !$nif || !$morada || !$genero) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Por favor, insira um email válido.";
    } elseif ($senha !== $confirma_senha) {
        $erro = "As senhas não coincidem.";
    } else {
        $stmt = $conn->prepare("SELECT id_cliente FROM cliente WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erro = "Este email já está registado.";
        } else {
            $hash_senha = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("
                INSERT INTO cliente (nome, email, senha, nif, morada, genero, criado_por, data_criacao, estado_registo)
                VALUES (?, ?, ?, ?, ?, ?, 'registo', NOW(), 'ativo')
            ");
            $stmt->bind_param("ssssss", $nome, $email, $hash_senha, $nif, $morada, $genero);

            if ($stmt->execute()) {
                $sucesso = "Registo concluído com sucesso! Agora pode iniciar sessão.";
            } else {
                $erro = "Erro ao registar. Tente novamente mais tarde.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8" />
<title>Registar Utilizador</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f5f5;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .top-voltar {
        position: absolute;
        top: 15px;
        left: 15px;
    }

    .top-voltar a {
        text-decoration: none;
        color: #555;
        background-color: #eee;
        padding: 8px 12px;
        border-radius: 5px;
    }

    .top-voltar a:hover {
        background-color: #ddd;
    }

    .registar-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        width: 360px;
        margin: 80px auto;
    }

    h2 {
        margin-top: 0;
        margin-bottom: 20px;
        text-align: center;
    }

    input[type="text"], input[type="email"], input[type="password"], select {
        width: 100%;
        padding: 10px;
        margin: 8px 0 15px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        background-color: #28a745;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #218838;
    }

    .message {
        margin-bottom: 15px;
        text-align: center;
        color: red;
    }

    .success {
        color: green;
    }
</style>
</head>
<body>

<div class="top-voltar">
    <a href="template.php">⬅ Voltar</a>
</div>

<div class="registar-container">
    <h2>Registar</h2>
    <?php if ($erro): ?>
        <div class="message"><?= htmlspecialchars($erro) ?></div>
    <?php elseif ($sucesso): ?>
        <div class="message success"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <input type="text" name="nome" placeholder="Nome completo" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
        <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <input type="text" name="nif" placeholder="NIF" required value="<?= htmlspecialchars($_POST['nif'] ?? '') ?>">
        <input type="text" name="morada" placeholder="Morada" required value="<?= htmlspecialchars($_POST['morada'] ?? '') ?>">
        <select name="genero" required>
            <option value="">Selecione o género</option>
            <option value="Masculino" <?= (($_POST['genero'] ?? '') == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
            <option value="Feminino" <?= (($_POST['genero'] ?? '') == 'Feminino') ? 'selected' : '' ?>>Feminino</option>
            <option value="Outro" <?= (($_POST['genero'] ?? '') == 'Outro') ? 'selected' : '' ?>>Outro</option>
        </select>
        <input type="password" name="senha" placeholder="Senha" required>
        <input type="password" name="confirma_senha" placeholder="Confirmar senha" required>
        <button type="submit">Registar</button>
    </form>
</div>

</body>
</html>
