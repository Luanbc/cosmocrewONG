<?php
require_once '../includes/conexaodb.php'; // Conexão com o banco

// Capturar o ID do animal
$animal_id = isset($_GET['animal_id']) ? (int) $_GET['animal_id'] : 0;

// Buscar informações do animal no banco
$stmt = $mysqli->prepare("SELECT * FROM animais WHERE id = ?");
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$result = $stmt->get_result();
$animal = $result->fetch_assoc();
$stmt->close();

if (!$animal) {
    echo "Animal não encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Adoção</title>
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/styleform.css">
</head>

<body>
    <div class="container-form">
        <h1>Formulário de Adoção</h1>
        <p>Adote o(a) <strong><?= htmlspecialchars($animal['nome']) ?></strong>!</p>
        <br>
        <form method="POST" action="/cosmocrewONG/includes/processar_adocao.php" enctype="multipart/form-data">
            <input type="hidden" name="animal_id" value="<?= $animal['id'] ?>">

            <label for="nome">Seu Nome:</label>
            <input type="text" id="nome" name="nome" required><br>

            <label for="email">Seu Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="telefone">Seu Telefone:</label>
            <input type="tel" id="telefone" name="telefone" required><br>

            <label for="endereco">Endereço Completo:</label>
            <input type="text" id="endereco" name="endereco" placeholder="Digite seu endereço completo" required><br>

            <label for="motivo">Por que deseja adotar este animal?</label>
            <textarea id="motivo" name="motivo" rows="4"
                placeholder="Conte-nos um pouco sobre você e o motivo da adoção" required></textarea>

            <label for="fotos_comprovante">Insira fotos do espaço destinado ao animal:</label>
            <input type="file" id="fotos_comprovante" name="fotos_comprovante[]" accept="image/*" multiple required><br>

            <button type="submit">Enviar Pedido de Adoção</button>
        </form>

    </div>
</body>
</html>

