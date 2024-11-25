<?php
require_once '../includes/conexaodb.php';

if (isset($_GET['id'])) {
    $adocao_id = $_GET['id'];

    // Buscar as informações da adoção e do animal associado
    $query = "SELECT a.animal_id, a.nome AS adotante_nome, a.email AS adotante_email, 
                     a.telefone AS adotante_telefone, a.endereco AS adotante_endereco, 
                     an.nome AS animal_nome
              FROM adocoes a
              JOIN animais an ON a.animal_id = an.id
              WHERE a.id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $adocao_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $adocao = $result->fetch_assoc();
    $stmt->close();

    if (!$adocao) {
        echo "Adoção não encontrada.";
        exit();
    }

    $animal_id = $adocao['animal_id'];

    // Atualizar o status da adoção para 'aprovada'
    $stmt = $mysqli->prepare("UPDATE adocoes SET status = 'aprovada' WHERE id = ?");
    $stmt->bind_param("i", $adocao_id);

    if ($stmt->execute()) {
        // Inserir os dados no histórico de adoções (se a tabela historico_adocoes for usada)
        $stmt_historico = $mysqli->prepare("
            INSERT INTO historico_adocoes (animal_id, animal_nome, adotante_nome, adotante_email, adotante_telefone, adotante_endereco)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt_historico->bind_param(
            "isssss", 
            $animal_id, 
            $adocao['animal_nome'], 
            $adocao['adotante_nome'], 
            $adocao['adotante_email'], 
            $adocao['adotante_telefone'], 
            $adocao['adotante_endereco']
        );
        $stmt_historico->execute();
        $stmt_historico->close();

        // Excluir o animal da tabela 'animais'
        $stmt_delete = $mysqli->prepare("DELETE FROM animais WHERE id = ?");
        $stmt_delete->bind_param("i", $animal_id);
        if ($stmt_delete->execute()) {
            echo "Adoção aprovada e animal removido com sucesso!";
            // Redirecionar para a página de gerenciamento
            header("Location: ../pages/gerenciaradocoes.php");
            exit();
        } else {
            echo "Erro ao remover o animal do banco de dados.";
        }
        $stmt_delete->close();
    } else {
        echo "Erro ao aprovar a adoção.";
    }
    $stmt->close();
}
?>
