<?php
session_start();
include '../includes/conexaodb.php'; 

// Verifica se o usuário está logado e tem a role de admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit;
}


$query_finalizados = "
    SELECT hp.projeto_id, hp.titulo, hp.descricao, hp.data_inicio, hp.data_fim, hp.status 
    FROM historico_projeto hp
    WHERE hp.status = 'finalizado'";
$result_finalizados = $mysqli->query($query_finalizados);
$projetos_finalizados = $result_finalizados->fetch_all(MYSQLI_ASSOC);
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

    <h2>Projetos Finalizados</h2>
    <table border="1">
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Data de Início</th>
            <th>Data de Fim</th>
            <th>Status</th>
        </tr>
        <?php foreach ($projetos_finalizados as $projeto): ?>
        <tr>
            <td><?php echo htmlspecialchars($projeto['titulo']); ?></td>
            <td><?php echo htmlspecialchars($projeto['descricao']); ?></td>
            <td><?php echo date("d/m/Y", strtotime($projeto['data_inicio'])); ?></td>
            <td><?php echo date("d/m/Y", strtotime($projeto['data_fim'])); ?></td>
            <td><?php echo htmlspecialchars($projeto['status']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="indexadm.php">
        <button type="button">Voltar</button>
    </a>
</body>
</html>