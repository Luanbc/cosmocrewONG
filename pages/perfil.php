<?php
session_start();
include '../includes/conexaodb.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$mensagem = "";


//busca os dados do usuário
$stmt = $mysqli->prepare("SELECT nome, email, telefone, data_nascimento, tipo_usuario FROM usuario WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nome, $email, $telefone, $data_nascimento, $tipo_usuario);
$stmt->fetch();
$stmt->close();

//  busca projetos ativos do usuário
$query_ativos = "
    SELECT p.projeto_id, p.titulo, p.descricao, p.data_inicio, p.data_fim 
    FROM projeto_ativo p
    JOIN projeto_usuarios pu ON p.projeto_id = pu.projeto_id
    WHERE pu.usuario_id = ? AND p.status = 'ativo'";
$stmt_ativos = $mysqli->prepare($query_ativos);
$stmt_ativos->bind_param("i", $usuario_id);
$stmt_ativos->execute();
$result_ativos = $stmt_ativos->get_result();
$projetos_ativos = $result_ativos->fetch_all(MYSQLI_ASSOC);
$stmt_ativos->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/styleperfil.css">
</head>

<body>
    <a href="index2.php">Voltar</a>
    <h1>Perfil de <?php echo htmlspecialchars($nome); ?></h1>
    <!-- Exibe mensagem de sucesso ou erro -->
    <?php if (!empty($mensagem)): ?>
        <div style="color: green; font-weight: bold; margin-bottom: 20px;">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <div class="profile">
        <section class="profile-edit">
            <h2>Informações do Perfil</h2>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($nome); ?></p>
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($tipo_usuario); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Telefone:</strong> <?php echo htmlspecialchars($telefone); ?></p>
            <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($data_nascimento); ?></p>

            <!-- Botão para ativar o formulário de edição -->
            <button id="edit-button" onclick="toggleEditForm()">Editar Perfil</button>

            <!-- Formulário de Edição de Perfil (inicialmente oculto) -->
            <div id="edit-form" style="display: none;">
                <h2>Editar Perfil</h2>
                <form action="atualizar_perfil.php" method="post">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>"
                        required><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
                        required><br>

                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>"
                        required><br>

                    <label for="data_nascimento">Data de Nascimento:</label>
                    <input type="date" id="data_nascimento" name="data_nascimento"
                        value="<?php echo htmlspecialchars($data_nascimento); ?>" required><br>

                    <button type="submit">Atualizar Perfil</button>
                </form>

            </div>
        </section>
    </div>
    <!-- Exibe a mensagem de sucesso, se existir -->
    <?php if (isset($_GET['mensagem'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['mensagem']); ?></p>
    <?php endif; ?>

    <!-- Exibe a mensagem de erro, se existir -->
    <?php if (isset($_GET['erro'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['erro']); ?></p>
    <?php endif; ?>

    <!-- Seção de Projetos Ativos -->
    <div class="projetos">
        <h2>Histórico de Projetos</h2>
        <section class="projetos-edit">
            <p>PROJETOS ATIVOS</p>
            <?php if (count($projetos_ativos) > 0): ?>
                <table border="1">
                    <tr>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Data de Início</th>
                        <th>Data de Fim</th>
                    </tr>
                    <?php foreach ($projetos_ativos as $projeto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($projeto['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($projeto['descricao']); ?></td>
                            <td><?php echo htmlspecialchars($projeto['data_inicio']); ?></td>
                            <td><?php echo htmlspecialchars($projeto['data_fim']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Você não está inscrito em nenhum projeto ativo.</p>
            <?php endif; ?>
<script src="/cosmocrewONG/js/perfil.js"></script>
</body>
</html>