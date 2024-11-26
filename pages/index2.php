<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmo</title>
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylehome.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="../imgs/carrossel/carrossel 1.png" alt="Imagem 1"></div>
            <div class="swiper-slide"><img src="../imgs/carrossel/carrossel 2.png" alt="Imagem 2"></div>
            <div class="swiper-slide"><img src="../imgs/carrossel/carrossel 3.jpeg" alt="Imagem 3"></div>
            <div class="swiper-slide"><img src="../imgs/carrossel/carrossel 4.jpeg" alt="Imagem 4"></div>
            <div class="swiper-slide"><img src="../imgs/carrossel/carrossel 5.jpg" alt="Imagem 4"></div>
        </div>
        <!-- Botões de navegação -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!-- Paginação -->
        <div class="swiper-pagination"></div>
    </div>

    <section class="testeheader">
        <a href="index2.php" class="logo">COSMOCREW</a>
        <span id="menu-icon" class="material-icons">☰</span>
        <ul class="navlist">
            <li><a href="index2.php">Home</a></li>
            <li><a href="contato.php">Quem Somos</a></li>

            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <!-- Exibe a opção Cadastro apenas se o usuário não estiver logado -->
                <li><a href="../pages/usercadastro.php">Cadastro</a></li>
            <?php endif; ?>

            <li><a href="../pages/adote.php">Adote um amigo</a></li>
           
            <li><a href="../pages/registrodoacao.php">DOAR</a></li>

            <li><a href="../pages/projetos.php">Projetos</a></li>


            <?php if (isset($_SESSION['usuario_id']) && isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'gerente'): ?>
        <!-- Exibe a opção Gerenciar Projetos se o usuário logado for gerente -->
            <li><a href="../pages/painelgerente.php">Painel Gerente</a></li>
            <?php endif; ?>

            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <!-- Exibe a opção Login se o usuário não estiver logado -->
                <li><a href="../pages/login.php">Login</a></li>
            <?php else: ?>
                <!-- Exibe a opção Perfil se o usuário estiver logado -->
                <li><a href="../pages/perfil.php">Perfil</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['usuario_id'])): ?>
                <!-- Exibe a opção Logout se o usuário estiver logado -->
                <li><a href="../includes/logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </section>


    <section class="sobre-instituto">
        <div class="texto-container">
            <h1>Conheça o Instituto Animália!</h1>
            <p>
                Somos uma organização não-governamental sem fins lucrativos, dedicada a cuidados e preservação com a
                Fauna.
                Fundada em 2024, trabalhamos incansavelmente para promover a saúde e o bem-estar dos nossos amigos.
            </p>
            <a href="contato.php" class="ler-mais">Ler mais...</a>
        </div>
    </section>

    <section class="n-product">
        <div class="center-text">
            <h2>RESGATES EM DESTAQUE</h2>
            <p>Todos os animais resgatados, foram encaminhados para tratamento e viveiros especializados em seus cuidados.</p>
        </div>
        <div class="n-content">
            <div class="gallery-item">
                <img src="../img/resgateshome/a1.jpeg" alt="Chimpa">
                <div class="overlay">
                    <h3>Kiko</h3>
                    <p>Kiko foi resgatado de um pequeno circo itinerante onde vivia em uma jaula apertada e sem luz. Era forçado a realizar truques para o público e alimentado de forma inadequada. 
                        Após uma denúncia anônima, uma equipe de resgate conseguiu levá-lo para um santuário, onde ele agora vive em grupo, explora árvores e recebe cuidados especiais.</p>
                </div>
            </div>

            <div class="gallery-item">
                <img src="../img/resgateshome/a2.jpeg" alt="Cão 2">
                <div class="overlay">
                    <h3>Maya</h3>
                    <p>Maya passou anos sendo usada como atração em passeios turísticos, carregando pessoas sob o sol escaldante e sendo mantida acorrentada por longas horas. Seu resgate foi possível graças a um grupo que fizeram uma denúncia. 
                        Hoje, ela vive em um grande espaço protegido, com lagos e florestas para explorar.</p>
                </div>
            </div>

            <div class="gallery-item">
                <img src="../img/resgateshome/a4.jpeg" alt="Cão 2">
                <div class="overlay">
                    <h3> Azulinda</h3>
                    <p>Azulinda foi resgatada de um traficante de animais que a mantinha em condições precárias para vendê-la no mercado negro. Suas asas estavam parcialmente danificadas devido ao tempo que passou presa. 
                        Após ser tratada por veterinários e biólogos, ela foi transferida para um santuário de aves, onde agora participa de um programa de reintrodução à natureza.</p>
                </div>
            </div>

            <div class="gallery-item">
                <img src="../img/resgateshome/a3.jpeg" alt="Cão 2">
                <div class="overlay">
                    <h3>Tico</h3>
                    <p>Tico foi encontrado em uma gaiola minúscula em uma casa, onde era mantido como animal de estimação ilegal. Desnutrido e assustado, ele foi resgatado. 
                        Após meses de reabilitação, ele foi levado para uma reserva natural, onde vive livremente com outros lêmures.</p>
                </div>
            </div>

            <div class="gallery-item">
                <img src="../img/resgateshome/a7.jpeg" alt="Cão 2">
                <div class="overlay">
                    <h3>Alex</h3>
                    <p>Foi resgatado de um zoológico clandestino onde vivia em condições deploráveis. Confinado em uma jaula enferrujada e suja, ele era alimentado com restos de comida inadequados e apresentava sinais de desnutrição. Após uma operação coordenada entre autoridades ambientais e a nossa ONG, Alex foi levado para um santuário especializado em grandes felinos. Lá, ele recebeu tratamento veterinário e, pela primeira vez, sentiu a grama sob suas patas. 
                        Hoje, ele vive em um espaço amplo e seguro, onde pode finalmente ser o rei que sempre foi destinado a ser.</p>
                </div>
            </div>

            <div class="gallery-item">
                <img src="../img/resgateshome/a6.jpeg" alt="Cão 2">
                <div class="overlay">
                    <h3>Valente</h3>
                    <p>Valente foi encontrada com uma asa quebrada após ter sido alvejada por caçadores. A equipe de resgate foi acionada por moradores da região, que a encontraram caída em uma área de mata devastada. 
                        Depois de meses de tratamento em um centro especializado, Valente recuperou a força e foi solta em uma área protegida, longe de ameaças humanas.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="mission-section">
        <div class="text-content">
            <h1>Missão</h1>
            <p>
                Lutar pela defesa dos direitos animais por meio de ações ativas e
                preventivas, que valorizem práticas sustentáveis.
            </p>
            <a href="../pages/registrodoacao.php" class="btn-contribute">Contribuir</a>
        </div>
        <div class="image-content"></div>
    </section>
    <div class="brands">
        <h1>Empresas que nos apoiam.</h1>
        <p>Com a ajuda de grandes empresas, conseguimos nos manter lutando pelos direitos dos animais e levar
            suprimentos para protetores independentes.</p>
        <div class="main-brands">
            <div class="brands-c">
                <img src="../img/brands/nasa1.png">
            </div>

            <div class="brands-c">
                <img src="../img/brands/spx2.png">
            </div>

            <div class="brands-c">
                <img src="../img/brands/aeb2.png">
            </div>

            <div class="brands-c">
                <img src="../img/brands/esa.png">
            </div>

            <div class="brands-c">
                <img src="../img/brands/ja.png">
            </div>
        </div>
    </div>
    <section class="voluntario-section">
        <div class="text-content">
            <h1>SEJA UM VOLUNTÁRIO</h1>
            <p>
            Estamos procurando pessoas com amor pelos animais para fazer parte da nossa ONG. Você pode ajudar em feiras de adoção, cuidados com os resgatados e campanhas.
            </p>
            <a href="../pages/projetos.php" class="btn-contribute">Seja VOLUNTÁRIO</a>
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
        <p>&copy; 2024 ONG Animália. Todos os direitos reservados.</p>
    </footer>   

    <script src="/cosmocrewONG/js/index.js"></script>            
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
</body>
</html>