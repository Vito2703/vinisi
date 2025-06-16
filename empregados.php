<?php
// Configuração da ligação à base de dados (substitui o require 'db.php')
try {
    $pdo = new PDO('mysql:host=localhost;dbname=viniat;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão à base de dados: " . $e->getMessage());
}

// Função para redirecionar e evitar reenvio do formulário
function redirecionar($url) {
    header("Location: $url");
    exit;
}

// Obter a ação via GET (listar, novo, editar, eliminar)
$acao = $_GET['acao'] ?? 'listar';
$id = $_GET['id'] ?? null;

// Tratar o POST para gravar (criar ou atualizar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_empregado = $_POST['numero_empregado'];
    $nome = $_POST['nome'];
    $genero = $_POST['genero'];
    $data_nascimento = $_POST['data_nascimento'];
    $morada_residencia = $_POST['morada_residencia'];
    $nacionalidade = $_POST['nacionalidade'];

    if (!empty($_POST['id_empregado'])) {
        // Atualizar empregado existente
        $id_empregado = $_POST['id_empregado'];
        $stmt = $pdo->prepare("UPDATE empregado SET numero_empregado=?, nome=?, genero=?, data_nascimento=?, morada_residencia=?, nacionalidade=? WHERE id_empregado=?");
        $stmt->execute([$numero_empregado, $nome, $genero, $data_nascimento, $morada_residencia, $nacionalidade, $id_empregado]);
    } else {
        // Criar novo empregado
        $stmt = $pdo->prepare("INSERT INTO empregado (numero_empregado, nome, genero, data_nascimento, morada_residencia, nacionalidade) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$numero_empregado, $nome, $genero, $data_nascimento, $morada_residencia, $nacionalidade]);
    }

    redirecionar('empregados.php');
}

// Tratar eliminação lógica (ou física, conforme tua tabela)
if ($acao === 'eliminar' && $id) {
    $stmt = $pdo->prepare("DELETE FROM empregado WHERE id_empregado=?");
    $stmt->execute([$id]);
    redirecionar('empregados.php');
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Gestão de Empregados</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fafafa; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #007BFF; color: white; }
        a.botao, button.botao {
            display: inline-block;
            padding: 6px 12px;
            background-color: #007BFF; /* azul */
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
            margin-right: 5px;
        }
        a.botao:hover, button.botao:hover {
            background-color: #0056b3; /* azul mais escuro no hover */
        }
        /* Botão Eliminar em vermelho */
        a.botao.eliminar {
            background-color: #dc3545; /* vermelho */
        }
        a.botao.eliminar:hover {
            background-color: #a71d2a; /* vermelho escuro no hover */
        }
        form {
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
            margin-top: 20px;
            max-width: 600px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        form label {
            display: block;
            margin-top: 10px;
            font-weight: 600;
        }
        form input[type="text"], form input[type="date"], form select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        .acoes {
            white-space: nowrap;
        }
        /* Botão Voltar com azul também */
        .botao-voltar {
            background-color: #007BFF;
            margin-top: 20px;
            display: inline-block;
        }
        .botao-voltar:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>Gestão de Empregados</h1>

<?php if ($acao === 'listar'): 
    $stmt = $pdo->prepare("SELECT * FROM empregado ORDER BY nome");
    $stmt->execute();
    $empregados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <a href="empregados.php?acao=novo" class="botao">Adicionar Novo Empregado</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Número Empregado</th>
                <th>Nome</th>
                <th>Género</th>
                <th>Data Nascimento</th>
                <th>Morada Residência</th>
                <th>Nacionalidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empregados as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['id_empregado']) ?></td>
                <td><?= htmlspecialchars($e['numero_empregado']) ?></td>
                <td><?= htmlspecialchars($e['nome']) ?></td>
                <td><?= htmlspecialchars($e['genero']) ?></td>
                <td><?= htmlspecialchars($e['data_nascimento']) ?></td>
                <td><?= htmlspecialchars($e['morada_residencia']) ?></td>
                <td><?= htmlspecialchars($e['nacionalidade']) ?></td>
                <td class="acoes">
                    <a href="empregados.php?acao=editar&id=<?= $e['id_empregado'] ?>" class="botao">Editar</a>
                    <a href="empregados.php?acao=eliminar&id=<?= $e['id_empregado'] ?>" onclick="return confirm('Tem a certeza que deseja eliminar este empregado?')" class="botao eliminar">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($empregados)): ?>
            <tr><td colspan="8">Nenhum empregado encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

<?php elseif ($acao === 'novo' || ($acao === 'editar' && $id)):

    $empregado = [
        'id_empregado' => '',
        'numero_empregado' => '',
        'nome' => '',
        'genero' => '',
        'data_nascimento' => '',
        'morada_residencia' => '',
        'nacionalidade' => ''
    ];

    if ($acao === 'editar') {
        $stmt = $pdo->prepare("SELECT * FROM empregado WHERE id_empregado = ?");
        $stmt->execute([$id]);
        $empregado = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$empregado) {
            echo "<p>Empregado não encontrado.</p>";
            echo '<a href="empregados.php" class="botao botao-voltar">Voltar à lista</a>';
            exit;
        }
    }
    ?>

    <h2><?= $acao === 'novo' ? 'Adicionar Novo Empregado' : 'Editar Empregado' ?></h2>

    <form method="POST" action="empregados.php">
        <input type="hidden" name="id_empregado" value="<?= htmlspecialchars($empregado['id_empregado']) ?>">
        
        <label>Número Empregado:
            <input type="text" name="numero_empregado" value="<?= htmlspecialchars($empregado['numero_empregado']) ?>" required>
        </label>

        <label>Nome:
            <input type="text" name="nome" value="<?= htmlspecialchars($empregado['nome']) ?>" required>
        </label>

        <label>Género:
            <select name="genero" required>
                <option value="">--Selecione--</option>
                <option value="Masculino" <?= $empregado['genero'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                <option value="Feminino" <?= $empregado['genero'] === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                <option value="Outro" <?= $empregado['genero'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
            </select>
        </label>

        <label>Data de Nascimento:
            <input type="date" name="data_nascimento" value="<?= htmlspecialchars($empregado['data_nascimento']) ?>" required>
        </label>

        <label>Morada Residência:
            <input type="text" name="morada_residencia" value="<?= htmlspecialchars($empregado['morada_residencia']) ?>" required>
        </label>

        <label>Nacionalidade:
            <input type="text" name="nacionalidade" value="<?= htmlspecialchars($empregado['nacionalidade']) ?>" required>
        </label>

        <button type="submit" class="botao"><?= $acao === 'novo' ? 'Adicionar' : 'Atualizar' ?></button>
    </form>

    <a href="empregados.php" class="botao botao-voltar">Voltar à lista</a>

<?php else: ?>

    <p>Ação inválida.</p>
    <a href="empregados.php" class="botao botao-voltar">Voltar à lista</a>

<?php endif; ?>

</body>
</html>
