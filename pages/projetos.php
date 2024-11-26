<?php
session_start();
include '../includes/conexaodb.php'; 

// Verifica se o usuário está logado
$usuario_id = $_SESSION['usuario_id'] ?? null; // Pode ser nulo se não estiver logado
$mensagem = "";

// Processa a inscrição ou cancelamento de inscrição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$usuario_id) {
        $mensagem = "Você precisa estar logado para realizar esta ação.";
    } else {
        $projeto_id = $_POST['projeto_id'];

        if (isset($_POST['acao']) && $_POST['acao'] === 'cancelar') {
            // Cancela a inscrição no projeto
            $stmt = $mysqli->prepare("DELETE FROM projeto_usuarios WHERE projeto_id = ? AND usuario_id = ?");
            $stmt->bind_param("ii", $projeto_id, $usuario_id);

            if ($stmt->execute()) {
                $mensagem = "Inscrição cancelada com sucesso!";
            } else {
                $mensagem = "Erro ao cancelar inscrição: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        } else {
            // Inscreve no projeto
            $stmt = $mysqli->prepare("CALL inscrever_voluntario(?, ?)");
            $stmt->bind_param("ii", $projeto_id, $usuario_id);

            if ($stmt->execute()) {
                $mensagem = "Inscrição realizada com sucesso!";
            } else {
                $mensagem = "Erro ao inscrever no projeto: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        }
    }
}

// Consulta para listar todos os projetos ativos e verificar inscrição
$query = "
    SELECT p.projeto_id, p.titulo, p.descricao, 
           DATE_FORMAT(p.data_inicio, '%d-%m-%Y') AS data_inicio, 
           DATE_FORMAT(p.data_fim, '%d-%m-%Y') AS data_fim, 
           CASE 
               WHEN ? IS NOT NULL AND EXISTS (
                   SELECT 1 
                   FROM projeto_usuarios pu 
                   WHERE pu.projeto_id = p.projeto_id 
                   AND pu.usuario_id = ?
               ) THEN 1 ELSE 0 
           END AS inscrito
    FROM projeto_ativo p
    WHERE p.status = 'ativo'";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ii", $usuario_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$projetos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projetos Disponíveis</title>
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/projetos2.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <h1>Projetos Disponíveis</h1>
    <?php if (!empty($mensagem)): ?>
        <div class="mensagem">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>
    <div class="container">
        <?php foreach ($projetos as $projeto): ?>
        <div class="card">
            <h2><?php echo htmlspecialchars($projeto['titulo']); ?></h2>
            <p><?php echo htmlspecialchars($projeto['descricao']); ?></p>
            <p><strong>Início:</strong> <?php echo htmlspecialchars($projeto['data_inicio']); ?></p>
            <p><strong>Fim:</strong> <?php echo htmlspecialchars($projeto['data_fim']); ?></p>
            <?php if ($usuario_id): ?>
                <?php if ($projeto['inscrito']): ?>
                    <!-- Botão para cancelar inscrição -->
                    <form action="" method="post">
                        <input type="hidden" name="projeto_id" value="<?php echo $projeto['projeto_id']; ?>">
                        <input type="hidden" name="acao" value="cancelar">
                        <button class="cancelar" type="submit">Cancelar Inscrição</button>
                    </form>
                <?php else: ?>
                    <!-- Botão para se inscrever -->
                    <form action="" method="post">
                        <input type="hidden" name="projeto_id" value="<?php echo $projeto['projeto_id']; ?>">
                        <button class="inscrever" type="submit">Inscrever-se</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <div class="login-link">
                    <p>Faça <a href="login.php">Login</a> para inscrever-se</p>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="voltar">
        <a href="index2.php">
            <button type="button">Voltar</button>
        </a>
    </div>
    <footer class="footer">
        <div class="social-links">
            <a href="https://facebook.com" target="_blank" class="social-icon">
                <i class="fab fa-facebook"></i>
            </a>
            <a href="https://twitter.com" target="_blank" class="social-icon">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="https://instagram.com" target="_blank" class="social-icon">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
        <p>&copy; 2024 ONG Animália. Todos os direitos reservados.</p>
    </footer>   
</body>
</html>

