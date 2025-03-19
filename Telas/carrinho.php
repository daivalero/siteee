<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
<?php
session_start();
include '../DAO/Conexao.php';

use PHP\Modelo\DAO\Conexao;

$conexao = new Conexao();
$conn = $conexao->conectar();

if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM produtos WHERE id = $id");
    $produto = $result->fetch_assoc();

    $adicional_preco = isset($_POST['adicional']) ? $_POST['adicional'] : 0;
    $adicional_nome = $adicional_preco ? $produto['adicional_nome'] : '';

    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade']++;
        $_SESSION['carrinho'][$id]['adicional_preco'] += $adicional_preco;
    } else {
        $_SESSION['carrinho'][$id] = array(
            "nome" => $produto['nome'],
            "preco" => $produto['preco'],
            "adicional_nome" => $adicional_nome,
            "adicional_preco" => $adicional_preco,
            "quantidade" => 1,
            "observacoes" => ''
        );
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $id = $_GET['id'];
    if (isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
    }
    header("Location: carrinho.php");
    exit();
}

if (isset($_POST['save_observacoes'])) {
    $id = $_POST['id'];
    $observacoes = $_POST['observacoes'];
    $_SESSION['carrinho'][$id]['observacoes'] = $observacoes;
}

echo "<h1>Carrinho</h1>";
$total_geral = 0;
$total_quantidade = 0;
if (!empty($_SESSION['carrinho'])) {
    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>Produto</th>";
    echo "<th>Quantidade</th>";
    echo "<th>Adicional</th>";
    echo "<th>Preço</th>";
    echo "<th>Observações</th>";
    echo "<th>Ações</th>";
    echo "</tr>";
    foreach ($_SESSION['carrinho'] as $id => $produto) {
        $total_preco = ($produto['preco'] + $produto['adicional_preco']) * $produto['quantidade'];
        $total_geral += $total_preco;
        $total_quantidade += $produto['quantidade'];
        echo "<tr>";
        echo "<td>" . $produto['nome'] . "</td>";
        echo "<td style='text-align: center;'>" . $produto['quantidade'] . "</td>";
        echo "<td>" . ($produto['adicional_nome'] != '' ? $produto['adicional_nome'] . " (+R$ " . $produto['adicional_preco'] . ")" : 'Nenhum') . "</td>";
        echo "<td>R$ " . number_format($total_preco, 2, ',', '.') . "</td>";
        echo "<td><a href='#' onclick=\"openModal(" . $id . ")\"><i class='fas fa-pencil-alt'></i></a></td>";
        echo "<td><a href='carrinho.php?action=remove&id=" . $id . "'>Remover</a></td>";
        echo "</tr>";
    }
    echo "<tr>";
    echo "<td colspan='5'><strong>Total de Produtos: " . $total_quantidade . " | Total Geral: R$ " . number_format($total_geral, 2, ',', '.') . "</strong></td>";
    echo "<td><button onclick=\"window.location.href='agendar.php'\">AGENDAR</button></td>";
    echo "</tr>";
    echo "</table>";
} else {
    echo "<p>Seu carrinho está vazio.</p>";
}
?>

<!-- Modal -->
<div id="observacoesModal" style="display:none;">
    <div style="background-color: #fff; padding: 20px; border: 1px solid #ccc;">
        <h2>Adicionar Observações</h2>
        <form method="post" action="carrinho.php">
            <input type="hidden" name="id" id="produtoId">
            <textarea name="observacoes" id="observacoesText" rows="4" cols="50"></textarea><br>
            <button type="submit" name="save_observacoes">Salvar</button>
            <button type="button" onclick="closeModal()">Cancelar</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function openModal(id) {
    document.getElementById('produtoId').value = id;
    document.getElementById('observacoesText').value = "<?php echo $_SESSION['carrinho'][id]['observacoes']; ?>";
    document.getElementById('observacoesModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('observacoesModal').style.display = 'none';
}
</script>
</body>
</html>