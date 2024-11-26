<?php
session_start();

// Verifica se o usuário está logado e qual é o tipo de usuário
if (isset($_SESSION['tipo_usuario'])) {
    if ($_SESSION['tipo_usuario'] == 'gerente') {
        $redirectUrl = 'painelgerente.php'; // Se for gerente, volta para o painel gerencial
    } elseif ($_SESSION['tipo_usuario'] == 'admin') {
        $redirectUrl = 'indexadm.php'; // Se for admin, volta para o painel do administrador
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
        $mysqli->rollback();
        $erro_msg = "Erro ao adicionar projeto: " . $e->getMessage();
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

    <!-- form de cadastro de projeto -->
    <button id="toggleFormBtn" class="btn-toggle">Cadastrar Projeto</button>
    <div id="projectForm" class="form-container">
        <form method="POST">
            <label for="titulo">Nome do Projeto:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" required></textarea>

            <label for="data_inicio">Data de Início:</label>
            <input type="date" id="data_inicio" name="data_inicio" required>

            <label for="data_fim">Data de Término:</label>
            <input type="date" id="data_fim" name="data_fim" required>

            <button type="submit" name="adicionar">Cadastrar</button>
        </form>
    </div>

    <!-- listagem dos projetos -->
    <table>
        <thead>
            <tr>
                <th>ID Responsável</th>
                <th>Nome da campanha</th>
                <th>Descrição</th>
                <th>Data de Início</th>
                <th>Data de Fim</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = $is_admin ? "SELECT * FROM projeto_ativo" : "SELECT * FROM projeto_ativo WHERE usuario_id = ?";
            $stmt = $mysqli->prepare($query);
            if ($is_gerente) {
                $stmt->bind_param("i", $_SESSION['usuario_id']);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $projetos = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            foreach ($projetos as $projeto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($projeto['usuario_id']); ?></td>
                    <td><?php echo htmlspecialchars($projeto['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($projeto['descricao']); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($projeto['data_inicio'])); ?></td>
                    <td><?php echo date("d/m/Y", strtotime($projeto['data_fim'])); ?></td>
                    <td>
                        <form method="post" action="editar_projeto.php" style="display:column;">
                            <input type="hidden" name="projeto_id" value="<?php echo $projeto['projeto_id']; ?>">
                            <button type="submit" class="editar">Editar</button>
                        </form>
                        <form method="post" style="display:column;">
                            <input type="hidden" name="projeto_id" value="<?php echo $projeto['projeto_id']; ?>">
                            <button type="submit" name="finalizar" class="finalizar">Finalizar</button>
                        </form>
                        <form method="post" style="display:column;">
                            <input type="hidden" name="projeto_id" value="<?php echo $projeto['projeto_id']; ?>">
                            <button type="submit" name="excluir" class="excluir">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script src="/cosmocrewONG/js/cadprojeto.js" defer></script>
</body>

</html>