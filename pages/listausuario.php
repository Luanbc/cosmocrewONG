<?php
session_start();
include '../includes/conexaodb.php';

// Verifica se o administrador está logado
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Atualiza o tipo de usuário se enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario_id'], $_POST['tipo_usuario'])) {
    $usuario_id = $_POST['usuario_id'];
    $novo_tipo = $_POST['tipo_usuario'];

    $stmt = $mysqli->prepare("UPDATE usuario SET tipo_usuario = ? WHERE usuario_id = ?");
    $stmt->bind_param("si", $novo_tipo, $usuario_id);
    if ($stmt->execute()) {
        $mensagem = "Tipo de usuário atualizado com sucesso!";
    } else {
        $mensagem = "Erro ao atualizar o tipo de usuário: " . $stmt->error;
    }
    $stmt->close();
}

// Deleta usuário se enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $mysqli->prepare("DELETE FROM usuario WHERE usuario_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $mensagem = "Usuário deletado com sucesso!";
    } else {
        $mensagem = "Erro ao deletar usuário: " . $stmt->error;
    }
    $stmt->close();
}

// Consulta todos os usuários
$query = "SELECT usuario_id, nome, email, tipo_usuario FROM usuario";
$result = $mysqli->query($query);
if ($result) {
    $usuarios = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Erro ao buscar usuários: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <h1>Gerenciar Usuários</h1>

    <?php if (!empty($mensagem)): ?>
        <p style="color: green;"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Tipo Atual</th>
            <th>Ação</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= htmlspecialchars($usuario['usuario_id']) ?></td>
            <td><?= htmlspecialchars($usuario['nome']) ?></td>
            <td><?= htmlspecialchars($usuario['email']) ?></td>
            <td><?= htmlspecialchars($usuario['tipo_usuario']) ?></td>
            <td>
                <!-- Atualizar tipo de usuário -->
                <form method="post" action="" style="display: inline;">
                    <input type="hidden" name="usuario_id" value="<?= $usuario['usuario_id'] ?>">
                    <select name="tipo_usuario">
                        <option value="gerente" <?= $usuario['tipo_usuario'] === 'gerente' ? 'selected' : '' ?>>Gerente</option>
                        <option value="voluntario" <?= $usuario['tipo_usuario'] === 'voluntario' ? 'selected' : '' ?>>Voluntário</option>
                        <option value="admin" <?= $usuario['tipo_usuario'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                    <button type="submit">Salvar</button>
                </form>

                <!-- Deletar usuário -->
                <form method="post" action="" style="display: inline;">
                    <input type="hidden" name="delete_id" value="<?= $usuario['usuario_id'] ?>">
                    <button type="submit" style="background-color: red; color: white;">Deletar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="indexadm.php">Voltar</a>
</body>
</html>
