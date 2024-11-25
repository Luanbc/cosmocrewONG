<?php
session_start();
include '../includes/conexaodb.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $foto = $_POST['foto'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];

    // Tratamento para upload de foto
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_nome = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_extensao = strtolower(pathinfo($foto_nome, PATHINFO_EXTENSION));
        $foto_nova = "admin_" . $usuario_id . "." . $foto_extensao;

        $destino = "/cosmocrewONG/pages/uploads/" . $foto_nova;

        // Move o arquivo para a pasta de uploads
        if (move_uploaded_file($foto_tmp, $destino)) {
            $foto = $foto_nova;
        } else {
            header("Location: perfiladm.php?erro=Erro ao fazer upload da foto.");
            exit();
        }
    }

    $extensoes_permitidas = ['jpg', 'jpeg', 'png'];
    if (!in_array($foto_extensao, $extensoes_permitidas)) {
        header("Location: perfiladm.php?erro=Formato de foto não permitido.");
        exit();
    }





    // Atualiza os dados do administrador
    $query = "UPDATE usuario SET nome = ?, email = ?, telefone = ?, data_nascimento = ?";
    $tipos = "sssi";
    $valores = [$nome, $email, $telefone, $data_nascimento,];

    if ($foto) {
        $query .= ", foto = ?";
        $tipos .= "s";
        $valores[] = $foto;
    }

    $query .= " WHERE usuario_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param($tipos, ...$valores);

    if ($stmt->execute()) {
        header("Location: perfiladm.php?mensagem=Perfil atualizado com sucesso!");
        exit();
    } else {
        header("Location: perfiladm.php?erro=Erro ao atualizar o perfil.");
        exit();
    }
}
?>
