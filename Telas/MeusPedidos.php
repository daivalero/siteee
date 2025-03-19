<?php
include '../DAO/Conexao.php';

use PHP\Modelo\DAO\Conexao;

$conexao = new Conexao();
$conn = $conexao->conectar();

// Definir o ID do usuário (substitua pelo método que você usa para obter o ID do usuário)
$user_id = 1;

// Verificar se o formulário foi enviado para cancelar ou apagar um pedido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel_pedido'])) {
        $pedido_id = $_POST['pedido_id'];

        // Mover o pedido para a tabela de pedidos cancelados
        $sql_move = "INSERT INTO pedidos_cancelados (cliente_id, data, horario, produtos, status)
                     SELECT cliente_id, data, horario, produtos, 'Cancelado'
                     FROM agendamentos
                     WHERE id = ?";
        $stmt_move = $conn->prepare($sql_move);
        if ($stmt_move) {
            $stmt_move->bind_param('i', $pedido_id);
            $stmt_move->execute();

            // Atualizar o status do pedido na tabela original
            $sql_update = "UPDATE agendamentos SET status = 'Cancelado' WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            if ($stmt_update) {
                $stmt_update->bind_param('i', $pedido_id);
                $stmt_update->execute();
            } else {
                echo "Erro ao preparar a consulta: " . $conn->error;
            }
        } else {
            echo "Erro ao preparar a consulta: " . $conn->error;
        }
    } elseif (isset($_POST['delete_pedido'])) {
        $pedido_id = $_POST['pedido_id'];
        $sql_delete = "DELETE FROM agendamentos WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        if ($stmt_delete) {
            $stmt_delete->bind_param('i', $pedido_id);
            $stmt_delete->execute();
        } else {
            echo "Erro ao preparar a consulta: " . $conn->error;
        }
    }
}

// Consulta SQL para selecionar pedidos na tabela agendamentos
$sql_pedidos = "SELECT a.id, a.data, a.horario, a.produtos, a.status
                FROM agendamentos a
                WHERE a.cliente_id = ?
                ORDER BY a.data DESC, a.horario DESC";
$stmt_pedidos = $conn->prepare($sql_pedidos);
if ($stmt_pedidos) {
    $stmt_pedidos->bind_param('i', $user_id);
    $stmt_pedidos->execute();
    $result_pedidos = $stmt_pedidos->get_result();
} else {
    die("Erro ao preparar a consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cliente.css">
    <title>Meus Pedidos</title>
</head>
<body>

<header>
    <div class="container">
        <div class="img">
            <img src="../images/logo_snack_fast.png" alt="Logotipo do site">
        </div>
        <nav>
            <ul>
                <li><a href="indexCliente.php" class="btnvoltar">VOLTAR</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        <h1>Meus Pedidos</h1>
        <div class="box">
            <table border="1" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Produtos</th>
                        <th>Observações</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_pedidos->num_rows > 0): ?>
                        <?php while ($pedido = $result_pedidos->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $pedido['data'] != '0000-00-00' ? date('d/m/Y', strtotime($pedido['data'])) : 'Data não definida'; ?></td>
                                <td>
                                    <?php 
                                    $produtos = json_decode($pedido['produtos'], true);
                                    if (!empty($produtos)) {
                                        foreach ($produtos as $produto) {
                                            echo $produto['nome'] . " - " . $produto['quantidade'] . "x<br>";
                                        }
                                    } else {
                                        echo "Nenhum produto";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    $observacoes = json_decode($pedido['produtos'], true);
                                    if (!empty($observacoes)) {
                                        foreach ($observacoes as $observacao) {
                                            if (!empty($observacao['observacoes'])) {
                                                echo $observacao['observacoes'] . "<br>";
                                            }
                                        }
                                    } else {
                                        echo "Nenhuma observação";
                                    }
                                    ?>
                                </td>
                                <td><?php echo $pedido['status']; ?></td>
                                <td>
                                    <?php if ($pedido['status'] != 'Cancelado'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                                            <button type="submit" name="cancel_pedido" class="btncancelar">Cancelar</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                                            <button type="submit" name="delete_pedido" class="btncancelar">Apagar</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nenhum pedido encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

</body>
</html>