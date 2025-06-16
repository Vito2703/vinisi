<?php
session_start();
require_once 'db.php';

$sql = "
SELECT 
  p.id_produto, 
  p.nome_vinho, 
  p.imagem, 
  p.valor, 
  p.tipo_vinho, 
  p.regiao, 
  GROUP_CONCAT(DISTINCT c.nome_casta SEPARATOR ', ') AS castas
FROM produto p
LEFT JOIN stock_produto s ON p.id_produto = s.id_produto
LEFT JOIN produto_casta pc ON p.id_produto = pc.id_produto
LEFT JOIN casta c ON pc.id_casta = c.id_casta
WHERE p.estado_registo = 'ativo' AND s.quantidade > 0
GROUP BY p.id_produto
ORDER BY p.id_produto DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <title>Catálogo de Vinhos</title>
  <link rel="stylesheet" href="styletemplate.css" />
  <style>
    body {
      background: url('imagens/template.jpg') no-repeat center center fixed;
      background-size: cover;
    }
  </style>
</head>
<body>
  <div class="top-bar">
    <h1>Catálogo de Vinhos</h1>
    <div class="btns">
      <?php if (isset($_SESSION['cliente_id'])): ?>
        <div class="welcome-msg">Olá, <?= htmlspecialchars($_SESSION['cliente_nome']) ?>!</div>
        <a href="area_cliente.php" class="area-cliente-btn">👤 Área de Cliente</a>
        <a href="logout.php" class="login-btn logout-btn">Logout</a>
      <?php else: ?>
        <a href="login.php" class="login-btn">🔐 Iniciar Sessão</a>
        <a href="registar.php" class="login-btn" style="background-color: #28a745; margin-left: 8px;"> Registar</a>
      <?php endif; ?>
      <a href="carrinho.php" class="carrinho-btn"> Carrinho</a>
    </div>
  </div>

  <div class="grid-container">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="card">
  <img src="<?= htmlspecialchars($row['imagem']) ?>" alt="Imagem do vinho">
  <h3><?= htmlspecialchars($row['nome_vinho']) ?></h3>
  <p><strong>Tipo:</strong> <?= htmlspecialchars($row['tipo_vinho']) ?></p>
  <p><strong>Região:</strong> <?= htmlspecialchars($row['regiao']) ?></p>
  <p><strong>Casta:</strong> <?= htmlspecialchars($row['castas']) ?></p>
  <p><strong>Preço:</strong> <?= number_format($row['valor'], 2, ',', '.') ?> €</p>

<?php
        $id_produto = $row['id_produto'];
        $ja_adicionado = isset($_SESSION['carrinho']) && isset($_SESSION['carrinho'][$id_produto]);
        ?>

        <button 
          class="carrinho-btn add-to-cart-btn <?= $ja_adicionado ? 'adicionado' : '' ?>" 
          data-id="<?= $id_produto ?>"
          <?= $ja_adicionado ? 'disabled' : '' ?>
      >
          <?= $ja_adicionado ? '✅ Adicionado' : '➕ Adicionar ao Carrinho' ?>
        </button>
      </div>
    <?php endwhile; ?>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const buttons = document.querySelectorAll('.add-to-cart-btn');

      buttons.forEach(button => {
        button.addEventListener('click', function () {
          const idProduto = this.dataset.id;
          const btn = this;

          fetch('adicionar_carrinho.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id_produto=${encodeURIComponent(idProduto)}`
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              btn.textContent = '✅ Adicionado';
              btn.disabled = true;
              btn.classList.add('adicionado');
            } else if(data.message) {
              alert(data.message);
            } else {
              alert('Erro ao adicionar ao carrinho');
            }
          })
          .catch(err => {
            console.error('Erro AJAX:', err);
            alert('Erro ao comunicar com o servidor.');
          });
        });
      });
    });
  </script>
</body>
</html>
