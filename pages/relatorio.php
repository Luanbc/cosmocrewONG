<?php
session_start();
include '../includes/conexaodb.php'; 

// Verifica se o usuário está logado e tem a role de admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Consulta para obter os dados dos usuários inscritos nos projetos
$query = "
    SELECT p.projeto_id, p.titulo, p.descricao, p.data_inicio, p.data_fim, u.usuario_id, u.nome, u.email, pu.data_inscricao
    FROM projeto_usuarios pu
    JOIN projeto_ativo p ON pu.projeto_id = p.projeto_id
    JOIN usuario u ON pu.usuario_id = u.usuario_id
    ORDER BY p.projeto_id
";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Inscrição nos Projetos</title>
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylerelatorio.css">
</head>
<body>
    <h1>Relatório de Usuários Inscritos nos Projetos</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID Projeto</th>
                <th>Título do Projeto</th>
                <th>Descrição do Projeto</th>
                <th>Data de Início</th>
                <th>Data de Fim</th>
                <th>ID do Usuário</th>
                <th>Nome do Usuário</th>
                <th>Email do Usuário</th>
                <th>Data de Inscrição</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['projeto_id']); ?></td>
                <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                <td><?php echo htmlspecialchars($row['descricao']); ?></td>
                <td><?php echo date("d/m/Y", strtotime($row['data_inicio'])); ?></td>
                <td><?php echo date("d/m/Y", strtotime($row['data_fim'])); ?></td>
                <td><?php echo htmlspecialchars($row['usuario_id']); ?></td>
                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['data_inscricao']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    
    <br>
    <a href="indexadm.php">
        <button type="button">Voltar</button>
    </a>
</body>
</html>

<?php

$mysqli->close();
?>
