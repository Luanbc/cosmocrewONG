<?php
session_start();
include '../includes/conexaodb.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $data_nascimento = $_POST['data_nascimento'];

    // Atualiza os dados do usuário
    $stmt = $mysqli->prepare("UPDATE usuario SET nome = ?, email = ?, telefone = ?, data_nascimento = ? WHERE usuario_id = ?");
    $stmt->bind_param("ssssi", $nome, $email, $telefone, $data_nascimento, $usuario_id);

    if ($stmt->execute()) {
        header("Location: perfil.php?mensagem=Perfil atualizado com sucesso!");
        exit();
    } else {
        echo "Erro ao atualizar o perfil: " . $stmt->error;
    }

    $stmt->close();
}
?>