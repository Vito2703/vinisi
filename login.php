<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Preparar consulta para buscar cliente pelo email
    $stmt = $conn->prepare("SELECT * FROM cliente WHERE email = ? AND estado_registo = 'ativo'");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $cliente = $resultado->fetch_assoc();

        // Verificar senha (assumindo que está guardada com password_hash)
        if (password_verify($senha, $cliente['senha'])) {
            // Login OK: guardar info na sessão (podes guardar só o email ou id_cliente)
            $_SESSION['cliente_id'] = $cliente['id_cliente'];
            $_SESSION['cliente_nome'] = $cliente['nome'];
            $_SESSION['cliente_email'] = $cliente['email'];

            header('Location: template.php');
            exit;
        } else {
            $erro = "Palavra-passe incorreta.";
        }
    } else {
        $erro = "Cliente não encontrado.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <style>
        /* Usa o teu CSS preferido aqui */
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .login-container { max-width: 350px; margin: auto; background: white; padding: 20px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 8px; margin: 10px 0; border-radius: 4px; border: 1px solid #ccc; }
        button { width: 100%; padding: 10px; background-color: #2a7ae2; color: white; border: none; border-radius: 6px; cursor: pointer; }
        button:hover { background-color: #1a5bcc; }
        .erro { color: red; margin-bottom: 10px; }
        a.voltar-btn { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #555; }
    </style>
</head>
<body>

<a class="voltar-btn" href="template.php">⬅ Voltar</a>

<div class="login-container">
    <h2>Iniciar Sessão</h2>

    <?php if (isset($erro)): ?>
        <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Palavra-passe" required>
        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>
