<?php
// backoffice.php
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Backoffice - ViniSI</title>
    <style>
        body {
    background-image: url('imagens/vinho.webp');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    height: 100vh;
    margin: 0;
    font-family: sans-serif;
    color: white;
}
        {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
        header {
            background: #82094e;
            color: white;
            padding: 15px 30px;
            font-size: 24px;
            font-weight: bold;
        }
        .container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 15px;
            padding: 20px 30px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background 0.3s ease;
            text-align: center;
        }
        .card:hover {
            background: #d7c9f6;
        }
        .card h2 {
            margin: 10px 0 0 0;
            font-size: 20px;
            color: #4b2e83;
        }
        .card p {
            margin-top: 8px;
            color: #555;
            font-size: 14px;
        }
        @media(max-width: 800px) {
            .container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media(max-width: 500px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<header>Backoffice ViniSI</header>

<div class="container">
    <div class="card" onclick="location.href='clientes.php'">
        <h2>Gestão de Clientes</h2>
        <p>Consultar, criar, editar e eliminar clientes.</p>
    </div>
    <div class="card" onclick="location.href='produtos.php'">
        <h2>Gestão de Produtos</h2>
        <p>Catálogo de vinhos e estoque.</p>
    </div>
    <div class="card" onclick="location.href='encomendas.php'">
        <h2>Gestão de Encomendas</h2>
        <p>Gestão Encomendas.</p>
    </div>
    <div class="card" onclick="location.href='Encomenda_produtos.php'">
        <h2>Gestão de Produtos das Encomendas</h2>
        <p>Processar e acompanhar pedidos.</p>
    </div>
    <div class="card" onclick="location.href='Gocorrencias.php'">
        <h2>Gestão de Ocorrências</h2>
        <p>Registo e resolução de problemas.</p>
    </div>
    <div class="card" onclick="location.href='empregados.php'">
        <h2>Gestão de Empregados</h2>
        <p>Alteraçoes dados de empregados</p>
    </div>
    <div class="card" onclick="location.href='stock_produto.php'">
        <h2>Gestão de stock de produtos</h2>
        <p>Gestão de stock de produtos</p>
    </div>
</div>

</body>
</html>
