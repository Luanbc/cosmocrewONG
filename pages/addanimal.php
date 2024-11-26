<?php
session_start();

// Verifica se o usuário está logado e qual é o tipo de usuário
if (isset($_SESSION['tipo_usuario'])) {
    if ($_SESSION['tipo_usuario'] == 'gerente') {
        $redirectUrl = 'painelgerente.php';
    } elseif ($_SESSION['tipo_usuario'] == 'admin') {
        $redirectUrl = 'indexadm.php';
    } else {
        $redirectUrl = 'index2.php';
    }
} else {
    header('Location: index2.php');
    exit;
}

// Incluir conexão com o banco
require_once '../includes/conexaodb.php';

// Pasta onde as imagens serão armazenadas
$upload_dir = 'uploads/';

// Certifique-se de que a pasta exista e seja gravável
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Adicionar animal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $nome = $_POST['nome'];
    $especie = $_POST['especie'];
    $raca = $_POST['raca'];
    $idade = $_POST['idade'];
    $descricao = $_POST['descricao'];
    $foto_path = null;

    // Verificar se uma imagem foi enviada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_tmp_name = $_FILES['foto']['tmp_name'];
        $foto_name = basename($_FILES['foto']['name']);
        $foto_ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

        // Validar o tipo de arquivo
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($foto_ext, $allowed_extensions)) {
            echo "Tipo de arquivo inválido. Apenas JPG, JPEG, PNG e GIF são permitidos.";
            exit();
        }

        // Gerar um nome único para a imagem
        $foto_new_name = uniqid('animal_', true) . '.' . $foto_ext;
        $foto_path = $upload_dir . $foto_new_name;

        // Mover a imagem para o diretório de upload
        if (!move_uploaded_file($foto_tmp_name, $foto_path)) {
            echo "Erro ao salvar a imagem.";
            exit();
        }
    }

    // Inserir no banco de dados
    $stmt = $mysqli->prepare("INSERT INTO animais (nome, especie, raca, idade, descricao, foto_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $nome, $especie, $raca, $idade, $descricao, $foto_path);


    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Animal adicionado com sucesso!";
        header('Location: addanimal.php'); // Redireciona para a página atual
        exit();
    } else {
        echo "Erro ao adicionar animal: " . $mysqli->error;
    }
    $stmt->close();
}

// Excluir animal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
    $id = $_POST['id'];

    $stmt = $mysqli->prepare("DELETE FROM animais WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Animal excluído com sucesso!";
        header('Location: addanimal.php');
        exit();
    } else {
        echo "Erro ao excluir animal: " . $mysqli->error;
    }
    $stmt->close();
}
// Obter animais do banco
$result = $mysqli->query("SELECT * FROM animais ORDER BY data_adicionado DESC");
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Animais</title>
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/addanimal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="mensagem-sucesso">
            <?= htmlspecialchars($_SESSION['mensagem']); ?>
        </div>
        <?php unset($_SESSION['mensagem']); ?>
    <?php endif; ?>

    <a href="<?php echo $redirectUrl; ?>">Voltar</a>

    <h1>Gerenciamento de Animais</h1>

    <button id="toggleFormBtn" class="btn-toggle">Adicionar Animal</button>
    <div id="projectForm" class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required><br>
            <label for="especie">Espécie:</label>
            <input type="text" id="especie" name="especie" required><br>
            <label for="raca">Raça:</label>
            <input type="text" id="raca" name="raca"><br>
            <label for="idade">Idade:</label>
            <input type="number" id="idade" name="idade"><br>
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"></textarea><br>
            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto" accept="image/*"><br>
            <button type="submit" name="adicionar">Adicionar</button>
        </form>
    </div>

        <h2>Lista de Animais</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Espécie</th>
                    <th>Raça</th>
                    <th>Idade</th>
                    <th>Foto</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($animal = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($animal['nome']) ?></td>
                        <td><?= htmlspecialchars($animal['especie']) ?></td>
                        <td><?= htmlspecialchars($animal['raca']) ?></td>
                        <td><?= htmlspecialchars($animal['idade']) ?></td>
                        <td>
                            <?php if ($animal['foto_url']): ?>
                                <img src="<?= htmlspecialchars($animal['foto_url']) ?>"
                                    alt="Foto de <?= htmlspecialchars($animal['nome']) ?>" width="100">
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $animal['id'] ?>">
                                <button type="submit" name="excluir">
                                    <i class="fas fa-trash"></i>Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
<script src="/cosmocrewONG/js/cadprojeto.js"></script>
</body>
</html>