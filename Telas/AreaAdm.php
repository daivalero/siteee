<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Área do Administrador</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
    <div class="container">
        <div class="img">
            <a href="IndexCliente.php">
                <img src="../images/logo_snack_fast.png" alt="Logotipo do site">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php" class="btnvoltar">Dashboard</a></li>
                <li><a href="pedidos.php" class="btnvoltar">Pedidos</a></li>
                <li><a href="produtos.php" class="btnvoltar">Produtos</a></li>
                <li><a href="clientes.php" class="btnvoltar">Clientes</a></li>
                <li><a href="financeiro.php" class="btnvoltar">Financeiro</a></li>
                <li><a href="feedback.php" class="btnvoltar">Feedback</a></li>
                <li><a href="Funcionarios.php" class="btnvoltar">Funcionários</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        <h1>Dashboard</h1>
        <div class="box">
            <canvas id="myChart" width="300" height="150"></canvas>
        </div>
        <div class="box">
            <h2>Últimos Pedidos</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Produto</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>João Silva</td>
                        <td>Hambúrguer</td>
                        <td>Entregue</td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>Maria Souza</td>
                        <td>Pizza</td>
                        <td>Em andamento</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<footer>
    <div class="container">
        <p>Snack Fast 2024 &copy; Todos os direitos reservados.</p>
    </div>
</footer>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
            datasets: [{
                label: 'Vendas',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>