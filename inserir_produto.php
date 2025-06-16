<?php
// Conexão à base de dados
$host = "localhost";
$db = "viniat";
$user = "root";
$pass = ""; // altera se usares password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

// Inserção quando o formulário for submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_vinho = $_POST["nome_vinho"];
    $lista_castas = $_POST["lista_castas"];
    $regiao = $_POST["regiao"];
    $valor = $_POST["valor"];
    $criado_por = "teste"; // valor fixo para testes

    $sql = "INSERT INTO produto (nome_vinho, lista_castas, regiao, valor, criado_por)
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssds", $nome_vinho, $lista_castas, $regiao, $valor, $criado_por);


    if ($stmt->execute()) {
        echo "<p style='color: green;'>Produto inserido com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!-- Formulário HTML -->
<!DOCTYPE html>
<html>
<head>
    <title>Inserir Produto</title>
</head>
<body>
    <h2>Inserir Novo Produto</h2>
    <form method="POST" action="">
        <label>Nome do Vinho:</label><br>
        <input type="text" name="nome_vinho" required><br><br>

        <label>Lista Castas:</label><br>
        <input type="text" name="lista_castas" required><br><br>

        <label>Região:</label><br>
        <input type="text" name="regiao"><br><br>

        <label>Valor (€):</label><br>
        <input type="number" step="0.01" name="valor" required><br><br>

        <input type="submit" value="Inserir Produto">
    </form>
</body>
</html>