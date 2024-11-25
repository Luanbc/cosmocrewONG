<?php
session_start();
include '../includes/conexaodb.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$mensagem = "";

// Busca os dados do administrador
$stmt = $mysqli->prepare("SELECT foto, nome, email, telefone, data_nascimento, tipo_usuario FROM usuario WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($foto, $nome, $email, $telefone, $data_nascimento, $tipo_usuario);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Perfil do Administrador</title>
    <link rel="stylesheet" href="../css/styleperfil.css">
</head>

<body>
    <a href="indexadm.php">Voltar</a>
    <h1>Perfil do <?php echo htmlspecialchars($nome); ?></h1>

    <!-- Exibe mensagem de sucesso ou erro -->
    <?php if (isset($_GET['mensagem'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['mensagem']); ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['erro']); ?></p>
    <?php endif; ?>

    <div class="profile">
        <section class="profile-edit">
            <!-- Exibe a foto do administrador -->
            <div class="profile-photo">
                <?php if ($foto): ?>
                    <img src="/cosmocrewONG/pages/uploads/<?php echo htmlspecialchars($foto); ?>" alt="Foto do Administrador" style="width: 150px; height: 150px; border-radius: 50%;">
                <?php else: ?>
                    <p>Sem foto de perfil.</p>
                <?php endif; ?>
            </div>    
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
                <form action="atualizar_perfil_admin.php" method="post" enctype="multipart/form-data">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>

                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>" required><br>

                    <label for="data_nascimento">Data de Nascimento:</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($data_nascimento); ?>" required><br>

                    <label for="foto">Foto de Perfil:</label>
                    <input type="file" id="foto" name="foto" accept="image/*"><br>

                    <button type="submit">Atualizar Perfil</button>
                </form>
            </div>
        </section>
    </div>

    <script src="/cosmocrewONG/js/perfiladm.js"></script>
</body>
</html>
