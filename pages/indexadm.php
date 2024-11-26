<?php
session_start();

include '../includes/conexaodb.php'; 

// Verificar se o usuário está logado como administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: login.php");
    exit();
}
// Buscar o nome do administrador logado
$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT nome FROM usuario WHERE usuario_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nome_admin);
$stmt->fetch();
$stmt->close();
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmo</title>
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/indexadm.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
<section class="testeheader">
        <a href="indexadm.php" class="logo">COSMOCREW</a>
        <span id="menu-icon" class="material-icons">☰</span>
        <ul class="navlist">
            <li><a href="perfiladm.php">Perfil</a></li>
               
            <li><a href="addanimal.php">Adicionar animais para adoção</a></li> 

            <li><a href="gerenciaradocoes.php">Pedidos de Adoção</a></li>
           
            <li><a href="hstadocao.php">Histórico de Adoção</a></li>

            <li><a href="gerenciarprojetos.php">Gerenciar Projetos</a></li>

            <li><a href="relatorio.php">Relatórios de Projetos</a></li>

            <li><a href="historicoprojetos.php">Histórico de Projetos</a></li>         
    
            <li><a href="listausuario.php">Gerenciar Usuários</a></li>

            <?php if (isset($_SESSION['usuario_id'])): ?>
                <!-- Exibe a opção Logout se o usuário estiver logado -->
                <li><a href="../includes/logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
</section>

    <section class="mission-section">
        <div class="text-content">
        <h1>Bem-vindo, <?php echo htmlspecialchars($nome_admin); ?>!
        <br>
            Essa é sua página administrativa.</h1>
        </div>
        <div class="image-content"></div>
    </section>

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
        <p>&copy; 2024 Seu Site. Todos os direitos reservados.</p>
    </footer>

    <script src="/cosmocrewONG/js/indexadm.js" defer></script>            
</body>
</html>