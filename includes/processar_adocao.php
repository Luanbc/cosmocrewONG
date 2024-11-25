<?php
require_once '../includes/conexaodb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['animal_id'], $_POST['nome'], $_POST['email'], $_POST['telefone'], $_POST['endereco'], $_FILES['fotos_comprovante'])) {
    $animal_id = $_POST['animal_id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    // Validar os dados recebidos
    if (empty($animal_id) || empty($nome) || empty($email) || empty($endereco)) {
        echo "Por favor, preencha todos os campos obrigatórios.";
        exit();
    }

    // Verificar o tamanho total dos arquivos enviados
    $tamanho_total = array_sum($_FILES['fotos_comprovante']['size']);
    if ($tamanho_total > 100 * 1024 * 1024) { // Limite de 100 MB
        echo "Erro: O tamanho total dos arquivos enviados excede o limite de 100 MB.";
        exit();
    }

    // Inserir dados na tabela de adoções
    $stmt = $mysqli->prepare("INSERT INTO adocoes (animal_id, nome, email, telefone, endereco) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $animal_id, $nome, $email, $telefone, $endereco);

    if ($stmt->execute()) {
        // Obter o ID da adoção recém-inserida
        $adocao_id = $stmt->insert_id;

        // Processar cada arquivo de imagem enviado
        if (!empty($_FILES['fotos_comprovante']['name'][0])) {
            $upload_dir = '../includes/uploads/';
            foreach ($_FILES['fotos_comprovante']['tmp_name'] as $index => $tmp_name) {
                if ($_FILES['fotos_comprovante']['error'][$index] === UPLOAD_ERR_OK) {
                    $foto_tmp_name = $tmp_name;
                    $foto_name = basename($_FILES['fotos_comprovante']['name'][$index]);
                    $foto_ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

                    // Validar o tipo de arquivo
                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                    if (!in_array($foto_ext, $allowed_extensions)) {
                        echo "Tipo de arquivo inválido. Apenas JPG, JPEG, PNG e GIF são permitidos.";
                        continue; // Ignorar arquivos inválidos
                    }

                    // Gerar um nome único para cada imagem
                    $foto_new_name = uniqid('comprovante_', true) . '.' . $foto_ext;
                    $foto_path = $upload_dir . $foto_new_name;

                    // Mover a imagem para o diretório de upload
                    if (move_uploaded_file($foto_tmp_name, $foto_path)) {
                        // Inserir o caminho da imagem na tabela de fotos
                        $stmt_foto = $mysqli->prepare("INSERT INTO fotos_adocao (adocao_id, caminho_foto) VALUES (?, ?)");
                        $stmt_foto->bind_param("is", $adocao_id, $foto_path);
                        $stmt_foto->execute();
                        $stmt_foto->close();
                    }
                }
            }
        }

        // Redirecionar para a página de agradecimento
        header("Location: /cosmocrewONG/pages/agradecimento.php");
        exit();
    } else {
        echo "Erro ao processar a adoção: " . $mysqli->error;
    }

    $stmt->close();
} else {
    echo "Erro: Formulário não enviado corretamente.";
}
?>
