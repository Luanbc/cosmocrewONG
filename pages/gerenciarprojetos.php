<?php
session_start();

// Verifica se o usuário está logado e qual é o tipo de usuário
if (isset($_SESSION['tipo_usuario'])) {
    if ($_SESSION['tipo_usuario'] == 'gerente') {
        $redirectUrl = 'index2.php'; // Se for gerente, volta para o index2.php
    } elseif ($_SESSION['tipo_usuario'] == 'admin') {
        $redirectUrl = 'indexadm.php'; // Se for admin, volta para o paineladm.php
    } else {
        $redirectUrl = 'index2.php'; // Default, caso não seja nem gerente nem admin
    }
} else {
    // Se não houver sessão de usuário, redireciona para index2.php
    header('Location: index2.php');
    exit;
}

include '../includes/conexaodb.php';

$erro_msg = '';
$sucesso_msg = '';

// Verifique se o usuário é admin ou gerente
$is_admin = $_SESSION['tipo_usuario'] === 'admin';
$is_gerente = $_SESSION['tipo_usuario'] === 'gerente';

// Adicionar Projeto
if (isset($_POST['adicionar']) && ($is_admin || $is_gerente)) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $usuario_id = $_SESSION['usuario_id'];  // Use o ID do usuário da sessão

    try {
        $mysqli->begin_transaction();

        // Insere o projeto na tabela 'projeto_ativo'
        $stmt = $mysqli->prepare("INSERT INTO projeto_ativo (titulo, descricao, data_inicio, data_fim, usuario_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $titulo, $descricao, $data_inicio, $data_fim, $usuario_id);
        $stmt->execute();
        $projeto_id = $stmt->insert_id;   // Obtém o id do projeto 
        $stmt->close();

        // Insere o histórico do projeto na tabela 'historico_projeto'
        $stmt = $mysqli->prepare("INSERT INTO historico_projeto (projeto_id, titulo, descricao, data_inicio, data_fim, status, acao) VALUES (?, ?, ?, ?, ?, 'ativo', 'criado')");
        $stmt->bind_param("issss", $projeto_id, $titulo, $descricao, $data_inicio, $data_fim);
        $stmt->execute();

        $mysqli->commit();
        $sucesso_msg = "Projeto adicionado com sucesso!";
    } catch (Exception $e) {
        // Caso ocorra algum erro, faz rollback da transação
        $mysqli->rollback();
        $erro_msg = "Erro ao adicionar projeto: " . $e->getMessage();
    }
}

// Editar Projeto
if (isset($_POST['editar'])) {
    if (isset($_POST['titulo'], $_POST['descricao'], $_POST['data_inicio'], $_POST['data_fim'])) {
        $projeto_id = $_POST['projeto_id'];
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];

        // Atualiza na tabela projeto_ativo
        $stmt = $mysqli->prepare("UPDATE projeto_ativo SET titulo = ?, descricao = ?, data_inicio = ?, data_fim = ? WHERE projeto_id = ?");
        $stmt->bind_param("ssssi", $titulo, $descricao, $data_inicio, $data_fim, $projeto_id);
        $stmt->execute();
        $stmt->close();

        // Insere no histórico
        $stmt = $mysqli->prepare("INSERT INTO historico_projeto (projeto_id, titulo, descricao, data_inicio, data_fim, status, acao) VALUES (?, ?, ?, ?, ?, 'ativo', 'editado')");
        $stmt->bind_param("issss", $projeto_id, $titulo, $descricao, $data_inicio, $data_fim);
        $stmt->execute();

        $sucesso_msg = "Projeto editado com sucesso!";
    }
}

// Finalizar Projeto
if (isset($_POST['finalizar']) && ($is_admin || $is_gerente)) {
    $projeto_id = $_POST['projeto_id'];

    try {
        // Verifica se o projeto já está finalizado
        $stmt = $mysqli->prepare("SELECT projeto_id FROM projeto_ativo WHERE projeto_id = ?");
        $stmt->bind_param("i", $projeto_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            throw new Exception("Projeto não encontrado ou já finalizado.");
        }
        $stmt->close();

        // Move o projeto para a tabela projeto_finalizado
        $stmt = $mysqli->prepare("INSERT INTO projeto_finalizado (projeto_id, titulo, descricao, data_inicio, data_fim) SELECT projeto_id, titulo, descricao, data_inicio, data_fim FROM projeto_ativo WHERE projeto_id = ?");
        $stmt->bind_param("i", $projeto_id);
        $stmt->execute();
        $stmt->close();

        // Registra no histórico como finalizado
        $stmt = $mysqli->prepare("INSERT INTO historico_projeto (projeto_id, titulo, descricao, data_inicio, data_fim, status, acao) SELECT projeto_id, titulo, descricao, data_inicio, data_fim, 'finalizado', 'finalizado' FROM projeto_ativo WHERE projeto_id = ?");
        $stmt->bind_param("i", $projeto_id);
        $stmt->execute();
        $stmt->close();

        // Exclui o projeto da tabela projeto_ativo
        $stmt = $mysqli->prepare("DELETE FROM projeto_ativo WHERE projeto_id = ?");
        $stmt->bind_param("i", $projeto_id);
        $stmt->execute();
        $stmt->close();

        $mysqli->commit();
        $sucesso_msg = "Projeto finalizado com sucesso!";
    } catch (Exception $e) {
        $mysqli->rollback();
        $erro_msg = "Erro ao finalizar projeto: " . $e->getMessage();
    }
}

// Excluir Projeto
if (isset($_POST['excluir']) && ($is_admin || $is_gerente)) {
    $projeto_id = $_POST['projeto_id'];

    try {
        // Insere no histórico antes de excluir
        $stmt = $mysqli->prepare("INSERT INTO historico_projeto (projeto_id, titulo, descricao, data_inicio, data_fim, status, acao) SELECT projeto_id, titulo, descricao, data_inicio, data_fim, 'excluido', 'excluido' FROM projeto_ativo WHERE projeto_id = ?");
        $stmt->bind_param("i", $projeto_id);
        $stmt->execute();

        // Exclui o projeto da tabela projeto_ativo
        $stmt = $mysqli->prepare("DELETE FROM projeto_ativo WHERE projeto_id = ?");
        $stmt->bind_param("i", $projeto_id);
        $stmt->execute();
        $stmt->close();

        $sucesso_msg = "Projeto excluído com sucesso!";
    } catch (Exception $e) {
        $erro_msg = "Erro ao excluir projeto: " . $e->getMessage();
    }
}

// Busca os projetos
$query = $is_admin ? "SELECT * FROM projeto_ativo" : "SELECT * FROM projeto_ativo WHERE usuario_id = ?";
$stmt = $mysqli->prepare($query);
if ($is_gerente) {
    $stmt->bind_param("i", $_SESSION['usuario_id']);
}
$stmt->execute();
$result = $stmt->get_result();
$projetos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <a href="<?php echo $redirectUrl; ?>">Voltar</a>
    <title>Gerenciar Projetos</title>
    <link rel="stylesheet" href="../css/gerenciarproj.css">
</head>

<body>
    <h1>Gerenciar Projetos</h1>

    <?php if ($erro_msg): ?>
        <div class="erro"><?php echo $erro_msg; ?></div>
    <?php endif; ?>
    <?php if ($sucesso_msg): ?>
        <div class="sucesso"><?php echo $sucesso_msg; ?></div>
    <?php endif; ?>

    <!-- Adicionar Projeto Form -->
    <form method="post">
        <h2>Adicionar Projeto</h2>
        <label>Título:</label><input type="text" name="titulo" required><br>
        <label>Descrição:</label><textarea name="descricao" required></textarea><br>
        <label>Data de Início:</label><input type="date" name="data_inicio" required><br>
        <label>Data de Fim:</label><input type="date" name="data_fim" required><br>
        <button type="submit" name="adicionar">Adicionar Projeto</button>
    </form>

    <!-- Lista de Projetos -->
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Descrição</th>
                <th>Data de Início</th>
                <th>Data de Fim</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projetos as $projeto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($projeto['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($projeto['descricao']); ?></td>
                    <td><?php echo htmlspecialchars($projeto['data_inicio']); ?></td>
                    <td><?php echo htmlspecialchars($projeto['data_fim']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="projeto_id" value="<?php echo $projeto['projeto_id']; ?>">
                            <button type="submit" name="editar">Editar</button>
                            <button type="submit" name="finalizar">Finalizar</button>
                            <button type="submit" name="excluir">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
