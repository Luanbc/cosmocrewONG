<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylecontato.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Quem Somos</title>
</head>

<body>
    <section class="testeheader">
        <a href="index2.php" class="logo">COSMOCREW</a>
        <span id="menu-icon" class="material-icons">☰</span>
        <ul class="navlist">
            <li><a href="index2.php">Home</a></li>
            <li><a href="contato.php">Quem Somos</a></li>
        
            <?php if (!isset($_SESSION['usuario_id'])): ?>
            <!-- Exibe a opção Cadastro apenas se o usuário não estiver logado -->
            <li><a href="usercadastro.php">Cadastro</a></li>
            <?php endif; ?>
        
            <li><a href="adote.php">Adote um amigo</a></li>
            
            <li><a href="registrodoacao.php">DOAR</a></li>
            <li><a href="projetos.php">Projetos</a></li>

            <?php if (!isset($_SESSION['usuario_id'])): ?>
            <!-- Exibe a opção Login se o usuário não estiver logado -->
            <li><a href="login.php">Login</a></li>
            <?php else: ?>
            <!-- Exibe a opção Perfil se o usuário estiver logado -->
            <li><a href="perfil.php">Perfil</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['usuario_id'])): ?>
            <!-- Exibe a opção Logout se o usuário estiver logado -->
            <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </section>

    <section class="mission-section">
        <div class="text-content">
            <h1>ONG ANIMÁLIA</h1>
            <p>
            Em 2024, com seu planeta a beira de um colapso, um grupo de apaixonados pela natureza e pela vida animal decidiu transformar sua paixão em ação.
            Foi assim que nasceu a Animália, uma ONG dedicada à proteção e conservação de animais de todas as espécies do Fauna.
            A jornada começou em um pequeno centro de resgates, onde o grupo se uniu para salvar animais em situações de risco — seja devido ao tráfico ilegal, maus-tratos ou desastres naturais. O primeiro resgate de um macaco debilitado e órfão marcou a primeira vitória da Animália, mostrando que, com trabalho árduo e dedicação, era possível mudar a vida de muitos.
            Hoje, Animália é conhecida não apenas pelos resgates e cuidados que oferece, mas também por seu papel importante na promoção de eventos e campanhas educativas. Desde feiras de adoção até eventos de arrecadação de fundos, a ONG tem conseguido envolver a comunidade em suas causas, criando um elo forte entre as pessoas e o compromisso com a fauna.    
            Com a colaboração de voluntários e apoiadores de todo o país, a Animália continua a expandir seus esforços, sonhando com um mundo onde cada animal tenha a chance de viver de forma digna e segura.    
            <br><br>
            <h2>A ANIMÁLIA NÃO É APENAS UM CENTRO DE RESGATES, É UM PONTO DE ENCONTRO PARA QUEM DESEJA FAZER A DIFERENÇA.</h2>
            </p>
            <a href="registrodoacao.php" class="btn-contribute">CONTRIBUA</a>
        </div>
        <div class="image-content"></div>
    </section>

    <section class="formulario">
        <div class="interface">
            <h2 class="titulo">FALE CONOSCO</h2>

            <form action="">
                <input type="text" name="" id="" placeholder="Digite seu nome:" required>
                <input type="text" name="" id="" placeholder="E-mail:" required>
                <input type="tel" name="" id="" placeholder="Telefone para contato:">
                <textarea name="" id="" placeholder="Escreva sua mensagem" required></textarea>
                <div class="btn-enviar"><input type="submit" value="ENVIAR"></div>
            </form>
        </div>
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
    
    
       

        
        <button id="scrollToTopBtn">↑ Voltar ao início</button>
        <script src="/cosmocrewONG/js/global.js" defer></script>   
    </body>
</html>