<?php
// Incluir a conexão com o banco de dados
require_once '../includes/conexaodb.php'; // Ajuste conforme o caminho do seu arquivo de conexão

// Consultar o histórico de adoções
$query = "SELECT h.id_adocao, h.animal_id, h.animal_nome, h.adotante_nome, h.adotante_email, h.adotante_telefone, h.adotante_endereco, h.data_adocao
          FROM historico_adocoes h
          ORDER BY h.data_adocao DESC";

$result = $mysqli->query($query);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Adoções</title>
    <link rel="stylesheet" href="../css/hstadocao.css">
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h1>Histórico de Adoções</h1>

        <!-- Tabela de Histórico de Adoções -->
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>Nome do Animal</th>
                    <th>Nome do Adotante</th>
                    <th>Email do Adotante</th>
                    <th>Telefone do Adotante</th>
                    <th>Endereço do Adotante</th>
                    <th>Data da Adoção</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($historico = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($historico['animal_nome']) ?></td>
                        <td><?= htmlspecialchars($historico['adotante_nome']) ?></td>
                        <td><?= htmlspecialchars($historico['adotante_email']) ?></td>
                        <td><?= htmlspecialchars($historico['adotante_telefone']) ?></td>
                        <td><?= htmlspecialchars($historico['adotante_endereco']) ?></td>
                        <td><?= date("d/m/Y", strtotime($historico['data_adocao'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <br>
    <div class="a">
    <a href="indexadm.php">Voltar</a>
    </div>
</body>
</html>
