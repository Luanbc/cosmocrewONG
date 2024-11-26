<?php
include '../includes/conexaodb.php';

session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
} else {
    header('Location: index2.php');
    exit;
}

$erro_msg = '';
$sucesso_msg = '';

// Verifica se existe um id de projeto para editar
if (isset($_POST['projeto_id'])) {
    $projeto_id = $_POST['projeto_id'];
    
    // Recupera os dados do projeto a ser editado
    $stmt = $mysqli->prepare("SELECT * FROM projeto_ativo WHERE projeto_id = ?");
    $stmt->bind_param("i", $projeto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $projeto = $result->fetch_assoc();
    $stmt->close();
    
    if (!$projeto) {
        $erro_msg = "Projeto não encontrado!";
    } else {
        // Se o formulário de edição for enviado
        if (isset($_POST['editar'])) {
            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];
            $data_inicio = $_POST['data_inicio'];
            $data_fim = $_POST['data_fim'];
            
            $stmt = $mysqli->prepare("UPDATE projeto_ativo SET titulo = ?, descricao = ?, data_inicio = ?, data_fim = ? WHERE projeto_id = ?");
            $stmt->bind_param("ssssi", $titulo, $descricao, $data_inicio, $data_fim, $projeto_id);
            
            if ($stmt->execute()) {
                $sucesso_msg = "Projeto atualizado com sucesso!";
                header('Location: gerenciarprojetos.php');
                exit;
            } else {
                $erro_msg = "Erro ao atualizar o projeto.";
            }
        }
    }
} else {
    $erro_msg = "Nenhum projeto encontrado para editar.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/editarprojeto.css">
    <title>Editar Projeto</title>
</head>
<a href="gerenciarprojetos.php">Voltar</a>
<body>
    <h1>Editar Projeto</h1>

    <?php if ($erro_msg): ?>
        <div class="erro"><?php echo $erro_msg; ?></div>
    <?php endif; ?>
    <?php if ($sucesso_msg): ?>
        <div class="sucesso"><?php echo $sucesso_msg; ?></div>
    <?php endif; ?>

<div id="projectForm" class="form-container">
    <form method="post">
        <input type="hidden" name="projeto_id" value="<?php echo $projeto['projeto_id']; ?>">

        <label for="titulo">Nome do Projeto:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($projeto['titulo']); ?>" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($projeto['descricao']); ?></textarea>

        <label for="data_inicio">Data de Início:</label>
        <input type="date" id="data_inicio" name="data_inicio" value="<?php echo $projeto['data_inicio']; ?>" required>

        <label for="data_fim">Data de Término:</label>
        <input type="date" id="data_fim" name="data_fim" value="<?php echo $projeto['data_fim']; ?>" required>

        <button type="submit" name="editar">Salvar Alterações</button>
    </form>
</div>
</body>
</html>
