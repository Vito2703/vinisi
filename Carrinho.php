<?php
session_start();
require_once 'db.php';

// Remover produto do carrinho
if (isset($_GET['remover'])) {
    $idRemover = (int)$_GET['remover'];
    unset($_SESSION['carrinho'][$idRemover]);
    header("Location: carrinho.php");
    exit;
}

// Atualiza automaticamente quantidades via POST (sem botão)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantidade'] as $id_produto => $qtd) {
        $_SESSION['carrinho'][$id_produto] = max(1, (int)$qtd);
    }
}

// Obter os produtos do carrinho
$produtos = [];
$total = 0;

if (!empty($_SESSION['carrinho'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['carrinho'])));
    $sql = "SELECT id_produto, nome_vinho, valor, imagem FROM produto WHERE id_produto IN ($ids)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $id = $row['id_produto'];
        $qtd = $_SESSION['carrinho'][$id];
        $subtotal = $qtd * $row['valor'];
        $row['quantidade'] = $qtd;
        $row['subtotal'] = $subtotal;
        $produtos[] = $row;
        $total += $subtotal;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('imagens/vinhoscompras.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            min-height: 100vh;
            padding: 20px;
            color: #fff;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0,0,0,0.6);
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            color: black; /* volta a preto aqui */
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        input[type="number"] {
            width: 60px;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            color: white; /* total em branco */
        }

        .btn {
            background-color: #2a7ae2;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            margin-top: 15px;
            display: inline-block;
        }

        .btn:hover {
            background-color: #1a5bcc;
        }

        .btn-remover {
            background-color: #e22a2a;
        }

        .btn-remover:hover {
            background-color: #c01919;
        }

        .btn-voltar {
            background-color: #6c757d;
        }

        .btn-voltar:hover {
            background-color: #565e64;
        }

        img {
            border-radius: 4px;
        }
    </style>
</head>
<body>
  <div class="content">

    <h1 style="color:white;">Carrinho</h1>

    <?php if (empty($produtos)): ?>
        <p style="color:white;">Carrinho vazio</p>
        <a href="template.php" class="btn btn-voltar">Voltar ao Catálogo</a>
    <?php else: ?>
    <form method="post" id="form-carrinho">
        <table>
            <tr>
                <th>Imagem</th>
                <th>Produto</th>
                <th>Preço Unitário</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($produto['imagem']) ?>" width="60"></td>
                    <td><?= htmlspecialchars($produto['nome_vinho']) ?></td>
                    <td><?= number_format($produto['valor'], 2, ',', '.') ?> €</td>
                    <td>
                        <input type="number" name="quantidade[<?= $produto['id_produto'] ?>]"
                               value="<?= $produto['quantidade'] ?>" min="1"
                               class="quantidade" data-preco="<?= $produto['valor'] ?>">
                    </td>
                    <td class="subtotal">
                        <?= number_format($produto['subtotal'], 2, ',', '.') ?> €
                    </td>
                    <td>
                        <a href="?remover=<?= $produto['id_produto'] ?>" class="btn btn-remover">❌</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="total">
            Total: <span id="total"><?= number_format($total, 2, ',', '.') ?> €</span>
        </div>

        <a href="confirmar_compra.php" class="btn">Confirmar Compra</a>
        <a href="template.php" class="btn btn-voltar">← Voltar ao Catálogo</a>
    </form>
    <?php endif; ?>

  </div>

<script>
    const quantidades = document.querySelectorAll('.quantidade');
    const subtotais = document.querySelectorAll('.subtotal');
    const totalSpan = document.getElementById('total');
    const form = document.getElementById('form-carrinho');

    function atualizarTotais() {
        let total = 0;
        quantidades.forEach((input, i) => {
            const preco = parseFloat(input.dataset.preco);
            const qtd = parseInt(input.value) || 1;
            const subtotal = preco * qtd;
            subtotais[i].textContent = subtotal.toFixed(2).replace('.', ',') + ' €';
            total += subtotal;
        });
        totalSpan.textContent = total.toFixed(2).replace('.', ',') + ' €';
    }

    quantidades.forEach(input => {
        input.addEventListener('change', () => {
            atualizarTotais();
            form.submit();
        });
    });
</script>

</body>
</html>
