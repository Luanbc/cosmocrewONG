<?php
require_once '../includes/conexaodb.php'; // Conexão com o banco

// Obter animais
$result = $mysqli->query("SELECT * FROM animais ORDER BY data_adicionado DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animais para Adoção</title>
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/styleadote.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

    <section class="sobre-instituto">    
        <div class="texto-container">
            <h1>Salve vidas, faça a diferença!</h1>
            <p>
            Nós precisamos do seu apoio para continuar mudando o destino dos animais abandonados. Seja você um voluntário, um doador ou um amigo.
            <br> 
            JUNTE-SE A NÓS!
            </p>
        </div>
    </section>
    <main>
        <section class="container">
            <h1>Adote um Amigo</h1>
            <div class="gallery active">
                <?php while ($animal = $result->fetch_assoc()): ?>
                    <div class="gallery-item">
                        <img src="<?= htmlspecialchars($animal['foto_url']) ?>" alt="Foto de <?= htmlspecialchars($animal['nome']) ?>">
                        <div class="overlay">
                            <h3><?= htmlspecialchars($animal['nome']) ?></h3>
                            <p><strong>Espécie:</strong> <?= htmlspecialchars($animal['especie']) ?></p>
                            <p><strong>Raça:</strong> <?= htmlspecialchars($animal['raca'] ?: 'Desconhecida') ?></p>
                            <p><strong>Idade:</strong> <?= htmlspecialchars($animal['idade'] ?: 'Não informada') ?></p>
                            <p><?= htmlspecialchars($animal['descricao']) ?></p>
                            <!-- Botão para redirecionar -->
                            <button class="adotar-btn" onclick="redirecionarFormulario(<?= intval($animal['id']) ?>)">Quero Adotar</button>

                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
        <div class="brands">
            <h1>Nossa galeria de momentos felizes.</h1>
            <div class="main-brands"></div>
        </div>            
    <section class="n-product">
        <br>
        <div class="n-content">
            <div class="gallery-item">
                <img src="../imgs/galeria/galeria1.png" alt="Ana e Luna">
                <div class="overlay">
                    <h3>Ana e Luna</h3>
                    <p>Ana sempre sonhou em ter um cachorro, mas o trabalho remoto durante a pandemia trouxe solidão. Foi quando encontrou Luna, uma cadela vira-lata resgatada pela ONG. 
                        Desde a adoção, Luna é sua companheira fiel durante o expediente. "Ela trouxe alegria e rotina para meus dias! Agora, trabalhar em casa nunca mais será solitário."</p>
                </div>
            </div>

            <div class="gallery-item">
                <img src="../imgs/galeria/galeria2.png" alt="Carlos e Bolota">
                <div class="overlay">
                    <h3> Carlos e Bolota</h3>
                    <p>Carlos foi à feira de adoção apenas para "dar uma olhada". No entanto, quando viu Bolota, um gato preto com olhos brilhantes, foi amor à primeira vista. 
                    "Ele é carinhoso e adora dormir no meu colo. Não consigo imaginar a casa sem ele!"</p>
                </div>
            </div>

            <div class="gallery-item">
                <img src="../imgs/galeria/galeria3.png" alt="Júlia e Max">
                <div class="overlay">
                    <h3>Júlia e Max</h3>
                    <p>Júlia queria motivação para se exercitar e decidiu adotar Max, um cachorro cheio de energia. Agora, eles caminham juntos todos os dias no parque. 
                        "Max mudou minha rotina. Estamos mais saudáveis e felizes juntos!"</p>
                </div>
            </div>

            <div class="gallery-item">
                <img src="../imgs/galeria/galeria4.png" alt="Pedro e Mel">
                <div class="overlay">
                    <h3>Pedro e Mel</h3>
                    <p>Após enfrentar um período difícil, Pedro adotou Mel, uma gatinha tímida. Com o tempo, ela se adaptou e trouxe luz para os dias de Pedro. 
                        "Mel me ajudou a sair da cama nos dias difíceis. Hoje, somos inseparáveis."</p>
                </div>
            </div>

            <div class="gallery-item">
                <img src="../imgs/galeria/galeria5.png" alt="Sofia, João e Thor">
                <div class="overlay">
                    <h3>Sofia, João e Thor</h3>
                    <p>Sofia e João queriam ensinar ao filho o valor do cuidado. Adotaram Thor, um cachorro grande e carinhoso. "Thor virou o irmão mais velho do João. Eles brincam o dia inteiro, e a casa está mais cheia de vida."</p>
                </div>
            </div>

            <div class="gallery-item">
                <img src="../imgs/galeria/galeria6.png" alt="Maria e Amora">
                <div class="overlay">
                    <h3>Maria e Amora</h3>
                    <p>Após se aposentar, Maria sentia falta de companhia. Foi então que encontrou Amora, uma cadela que também precisava de um novo começo.
                         "Adotar Amora foi a melhor decisão da minha vida. Ela me acompanha em tudo, e nunca me sinto sozinha."</p>
                </div>
            </div>
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
    <script src="/cosmocrewONG/js/adote.js"></script>
    <script src="/cosmocrewONG/js/global.js"></script>
</body>   
</html>
