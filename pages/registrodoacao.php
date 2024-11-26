<?php
session_start();
include '..//includes/conexaodb.php'; 

// Verificar se o usuário está logado
$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valor = $_POST['valor'];
    $metodo = $_POST['metodo'];
    $anonimo = isset($_POST['anonimo']) ? $_POST['anonimo'] : "Não";
    $nome_doador = !$usuario_id && $anonimo === "Não" ? $_POST['nome_doador'] : null;

    // Preparar a chamada ao procedimento armazenado
    $stmt = $mysqli->prepare("CALL cadastrar_doacao(?, ?, ?, ?, ?)");
    $stmt->bind_param("dsiss", $valor, $metodo, $usuario_id, $anonimo, $nome_doador);

    if ($stmt->execute()) {
        echo "Doação registrada com sucesso!";
    } else {
        echo "Erro ao registrar doação: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doação Cosmo</title>
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/styledoacao.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<header>
    <a href="../pages/index2.php" class="logo">COSMOCREW</a>
    <ul class="navlist">
        <li><a href="index2.php">Home</a></li>
        <li><a href="contato.php">Quem Somos</a></li>      
        
        <?php if (!isset($_SESSION['usuario_id'])): ?>
        <!-- Exibe a opção Cadastro apenas se o usuário não estiver logado -->
        <li><a href="usercadastro.php">Cadastro</a></li>
        <?php endif; ?>
        
        <li><a href="../pages/adote.php">Adote um amigo</a></li>
        
        <li><a href="../pages/registrodoacao.php">DOAR</a></li>
        <li><a href="../pages/projetos.php">Projetos</a></li>

        <?php if (!isset($_SESSION['usuario_id'])): ?>
        <!-- Exibe a opção Login se o usuário não estiver logado -->
        <li><a href="../pages/login.php">Login</a></li>
        <?php else: ?>
        <!-- Exibe a opção Perfil se o usuário estiver logado -->
        <li><a href="../pages/perfil.php">Perfil</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['usuario_id'])): ?>
        <!-- Exibe a opção Logout se o usuário estiver logado -->
        <li><a href="../pages/logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</header>

<body>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="../imgs/carrossel/cadastro1.png" alt="Imagem 1"></div>
            <div class="swiper-slide"><img src="../imgs/carrossel/cadastro2.png" alt="Imagem 2"></div>
            <div class="swiper-slide"><img src="../imgs/carrossel/cadastro3.png" alt="Imagem 3"></div>
            <div class="swiper-slide"><img src="../imgs/carrossel/cadastro4.png" alt="Imagem 4"></div>
        </div>
        <!-- Botões de navegação -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!-- Paginação -->
        <div class="swiper-pagination"></div>
    </div>

    <section>
        <div class="donation-section">
            <h1>FAÇA A SUA DOAÇÃO!</h1>
            <h6>Ajude a proteger a fauna e preservar a biodiversidade! Sua doação é essencial para o resgate, cuidado e bem-estar dos animais em nosso centro de conservação. <br> DOE E FAÇA A DIFERENÇA!</h6>
            <div class="donation-grid">
                <a href="http://pag.ae/7_6f_C3M5" target="_blank" class="donation-card-link">
                    <div class="donation-card">
                        <h2> PLANO REX</h2>
                        <img src="../imgs/doa1.png" alt="Ícone">
                        <h3>Doando R$ 10,00</h3>
                        <p>Você ajuda a impulsionar nossas campanhas de conscientização
                            e adoção em nosso planeta.</p>
                    </div>
                </a>
                <a href="http://pag.ae/7_6g1gr-v" target="_blank" class="donation-card-link">
                    <div class="donation-card">
                        <h2>PLANO BOLT</h2>
                        <img src="../imgs/doa2.png" alt="Ícone">
                        <h3>Doando R$ 20,00</h3>
                        <p>Você ajuda a alimentar um animal em abrigos parceiros e com os custos de monitoramento de
                            fauna.</p>
                    </div>
                </a>
                <a href="http://pag.ae/7_6g1B4hR" target="_blank" class="donation-card-link">
                    <div class="donation-card">
                        <h2>PLANO PLUTO</h2>
                        <img src="../imgs/doa3.png" alt="Ícone">
                        <h3>Doando R$ 50,00</h3>
                        <p>Você ajuda com medicamentos e cuidados dos animais em abrigos e animais silvestres em
                            reabilitação no Fauna.</p>
                    </div>
                </a>
                <a href="http://pag.ae/7_6g1VHga" target="_blank" class="donation-card-link">
                    <div class="donation-card">
                        <h2>PLANO MEL</h2>
                        <img src="../imgs/doa4.png" alt="Ícone">
                        <h3>Doando R$ 100,00</h3>
                        <p>Você ajuda com todos os custos de uma castração segura e nos cuidados médicos veterinários
                            das onças sob os nossos cuidados.</p>
                    </div>
                </a>
                <a href="http://pag.ae/7_6g2wC9q" target="_blank" class="donation-card-link">
                    <div class="donation-card">
                        <h2>PLANO BETHOVEEN</h2>
                        <img src="../imgs/doa6.png" alt="Ícone">
                        <h3>Doando R$ 200,00</h3>
                        <p>Nós conseguimos expandir nossos mutirões de castração e auxiliamos na manutenção da nossa
                            Base de Atendimento.</p>
                    </div>
                </a>
                <div class="pix-donation">
                    <p>Para doar qualquer valor, doe pelo pix:</p>
                    <img src="../imgs/qrcode.jpeg" alt="QR Code">
                    <p id="pix-key">cosmocrew01@gmail.com</p>
                    <button onclick="copyPixKey()">Copiar Chave Pix</button>
                </div>


            </div>
        </div>
    </section>


    <section class="investimento">
        <div class="investimento-section">
            <h2>COMO SEU RECURSO É INVESTIDO</h2>
            <div class="investimento-item" onclick="toggleDetails(this)">
                <p>Proteção da fauna</p>
                <span class="plus-icon">+</span>
                <div class="details">
                    <p>Os recursos voltados para a proteção da fauna são essenciais para garantir a preservação das diversas espécies e ecossistemas.
                        Além disso, existem iniciativas de reabilitação e centros de resgate que cuidam de animais feridos e promovem a reintrodução segura ao meio ambiente.
                        Esses esforços combinados fortalecem a conservação da fauna, promovendo um equilíbrio sustentável.
                    </p>
                </div>
            </div>
            <div class="investimento-item" onclick="toggleDetails(this)">
                <p>Campanhas de conscientização.</p>
                <span class="plus-icon">+</span>
                <div class="details">
                    <p>As campanhas de conscientização são ferramentas poderosas para promover mudanças de comportamento e fomentar o engajamento social em prol de causas importantes, como a proteção ambiental, a saúde pública e os direitos humanos.
                    Por meio de redes sociais, palestras, eventos comunitários e materiais educativos, essas iniciativas alcançam um público amplo, criando uma cultura de responsabilidade e ação coletiva.
                    </p>
                </div>
            </div>
            <div class="investimento-item" onclick="toggleDetails(this)">
                <p>Cuidados e bem-estar de animais que não podem retornar para a natureza</p>
                <span class="plus-icon">+</span>
                <div class="details">
                    <p>Esses animais, muitas vezes resgatados de situações de risco, acidentes ou maus-tratos, podem ter sequelas físicas ou comportamentais que os tornam incapazes de sobreviver por conta própria em seus habitats naturais. Santuários de animais e centros de reabilitação desempenham um papel fundamental ao proporcionar ambientes adaptados, onde as necessidades específicas de cada espécie são atendidas.
                        Oferecemos abrigo, alimentação balanceada, cuidados veterinários contínuos e estímulos ambientais que garantem o bem-estar físico e psicológico dos animais.
                    </p>
                </div>
            </div>
        </div>
    </section> 

    <section class="apoio-abrigos">
        <div class="texto-container">
        <h1>APOIO A ONGS!</h1>
            <p>
            A Animália funciona como uma ONG mãe. Iniciamos um trabalho de auxílio às ONGs e protetores independentes.
            Essa iniciativa tende a direcionar cuidados e recursos com atendimento veterinário, distribuição de ração e medicamentos aos abrigos, onde muitos animais necessitam de assistência e seus cuidadores enfrentam desafios financeiros. 
            </p>
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


    <button id="scrollToTopBtn">↑ Voltar ao topo</button>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="/cosmocrewONG/js/doacao.js"></script>
    <script src="/cosmocrewONG/js/global.js"></script>
</body>
</html>