<?php
session_start();
include '../includes/conexaodb.php'; 

// Verifica se o usuário está logado
$usuario_id = $_SESSION['usuario_id'] ?? null; // Pode ser nulo se não estiver logado
$mensagem = "";

// Processa a inscrição ou cancelamento de inscrição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o usuário está logado
    if (!$usuario_id) {
        $mensagem = "Você precisa estar logado para realizar esta ação.";
    } else {
        $projeto_id = $_POST['projeto_id'];

        if (isset($_POST['acao']) && $_POST['acao'] === 'cancelar') {
            // Cancela a inscrição no projeto
            $stmt = $mysqli->prepare("DELETE FROM projeto_usuarios WHERE projeto_id = ? AND usuario_id = ?");
            $stmt->bind_param("ii", $projeto_id, $usuario_id);

            if ($stmt->execute()) {
                $mensagem = "Inscrição cancelada com sucesso!";
            } else {
                $mensagem = "Erro ao cancelar inscrição: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        } else {
            // Inscreve no projeto
            $stmt = $mysqli->prepare("CALL inscrever_voluntario(?, ?)");
            $stmt->bind_param("ii", $projeto_id, $usuario_id);

            if ($stmt->execute()) {
                $mensagem = "Inscrição realizada com sucesso!";
            } else {
                $mensagem = "Erro ao inscrever no projeto: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        }
    }
}

// Consulta para listar todos os projetos ativos e verificar inscrição
$query = "
    SELECT p.projeto_id, p.titulo, p.descricao, p.data_inicio, p.data_fim, 
           CASE 
               WHEN ? IS NOT NULL AND EXISTS (
                   SELECT 1 
                   FROM projeto_usuarios pu 
                   WHERE pu.projeto_id = p.projeto_id 
                   AND pu.usuario_id = ?
               ) THEN 1 ELSE 0 
           END AS inscrito
    FROM projeto_ativo p
    WHERE p.status = 'ativo'";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ii", $usuario_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$projetos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Projetos Disponíveis</title>
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/projetos.css">
</head>
<body>
    <h1>Projetos Disponíveis</h1>

    <!-- Exibe mensagem de sucesso ou erro -->
    <?php if (!empty($mensagem)): ?>
        <div style="color: green; font-weight: bold; margin-bottom: 20px;">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Data de Início</th>
            <th>Data de Fim</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($projetos as $projeto): ?>
        <tr>
            <td><?php echo htmlspecialchars($projeto['titulo']); ?></td>
            <td><?php echo htmlspecialchars($projeto['descricao']); ?></td>
            <td><?php echo htmlspecialchars($projeto['data_inicio']); ?></td>
            <td><?php echo htmlspecialchars($projeto['data_fim']); ?></td>
            <td>
                <?php if ($usuario_id): ?>
                    <?php if ($projeto['inscrito']): ?>
                        <!-- Botão para cancelar inscrição -->
                        <form action="" method="post">
                            <input type="hidden" name="projeto_id" value="<?php echo $projeto['projeto_id']; ?>">
                            <input type="hidden" name="acao" value="cancelar">
                            <button type="submit" style="background-color: red; color: white;">Cancelar Inscrição</button>
                        </form>
                    <?php else: ?>
                        <!-- Botão para se inscrever -->
                        <form action="" method="post">
                            <input type="hidden" name="projeto_id" value="<?php echo $projeto['projeto_id']; ?>">
                            <button type="submit" style="background-color: green; color: white;">Inscrever-se</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <span style="color: gray;">Faça login para inscrever-se</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="index2.php">
        <button type="button">Voltar</button>
    </a>
</body>
</html>

