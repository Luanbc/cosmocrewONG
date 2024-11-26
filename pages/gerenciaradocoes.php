<?php

session_start();

if ($_SESSION['tipo_usuario'] === 'admin') {
    
} else {
    
    echo "Você não tem permissão para acessar esta página.";
}


require_once '../includes/conexaodb.php'; 

// Consultar todas as adoções com fotos associadas
$query = "SELECT a.id, a.nome, a.email, a.telefone, a.status, a.data_adocao, an.nome AS animal_nome, 
                 GROUP_CONCAT(f.caminho_foto SEPARATOR ',') AS fotos_comprovante
          FROM adocoes a
          JOIN animais an ON a.animal_id = an.id
          LEFT JOIN fotos_adocao f ON a.id = f.adocao_id
          GROUP BY a.id";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Adoções</title>
    <link rel="stylesheet" href="../css/geradocao.css">
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h1>Gerenciar Adoções</h1>

        <!-- Tabela de Adoções -->
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>Nome do Usuário</th>
                    <th>Animal Adotado</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Comprovantes de Espaço</th>
                    <th>Data da Adoção</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($adocao = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($adocao['nome']) ?></td>
                        <td><?= htmlspecialchars($adocao['animal_nome']) ?></td>
                        <td><?= htmlspecialchars($adocao['email']) ?></td>
                        <td><?= htmlspecialchars($adocao['telefone']) ?></td>
                        <td>
                            <?php if ($adocao['fotos_comprovante']): ?>
                                <?php 
                                $fotos = explode(',', $adocao['fotos_comprovante']);
                                foreach ($fotos as $foto): ?>
                                    <img src="<?= htmlspecialchars($foto) ?>" alt="Comprovante" width="100" style="margin: 5px;">
                                <?php endforeach; ?>
                            <?php else: ?>
                                Nenhuma imagem
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($adocao['data_adocao']) ?></td>
                        <td><?= htmlspecialchars($adocao['status']) ?></td>
                        <td>
                            <!-- Ações (Aprovar/Rejeitar) -->
                         <div class="botao-container">    
                            <a href="../includes/aprovar_adocao.php?id=<?= $adocao['id'] ?>">Aprovar</a> 
                            <a href="../includes/rejeitar_adocao.php?id=<?= $adocao['id'] ?>">Rejeitar</a>
                        </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <a href="indexadm.php">Voltar</a>
    </div>  
</body>
</html>
