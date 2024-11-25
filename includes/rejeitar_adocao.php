<?php
require_once '../includes/conexaodb.php';

if (isset($_GET['id'])) {
    $adocao_id = $_GET['id'];

    // Atualizar o status da adoção para 'rejeitada'
    $stmt = $mysqli->prepare("UPDATE adocoes SET status = 'rejeitada' WHERE id = ?");
    $stmt->bind_param("i", $adocao_id);

    if ($stmt->execute()) {
        echo "Adoção rejeitada com sucesso!";
    } else {
        echo "Erro ao rejeitar a adoção.";
    }
    $stmt->close();
}
?>
