<?php
session_start();
include '../includes/conexaodb.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta o usuário pelo email
    $stmt = $mysqli->prepare("SELECT usuario_id, nome, email, senha, tipo_usuario FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtém o usuário
        $usuario = $result->fetch_assoc();

        // Verifica a senha usando password_verify
        if (password_verify($senha, $usuario['senha'])) {
            // Senha correta, iniciar a sessão
            $_SESSION['usuario_id'] = $usuario['usuario_id']; // Corrigido aqui
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email']; // Armazena o email na sessão
            $_SESSION['usuario_id'] = $usuario['usuario_id']; // Certifique-se de que o nome da coluna está correto
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario']; // Verifique se a coluna `role` é a que armazena o tipo de usuário


            // Redireciona para a página inicial ou painel administrativo
            if ($_SESSION['tipo_usuario'] === 'admin') {
                header("Location: indexadm.php");           
            } else {
                header("Location: index2.php");
            }
            exit();
        } else {
            // Senha incorreta
            $erro = "E-mail ou senha incorretos.";
        }
    } else {
        // Usuário não encontrado
        $erro = "E-mail ou senha incorretos.";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Login Cosmo</title>
</head>

<body>
    <div class="starry-sky">
        <div class="stars" id="stars">
            <main class="main-login">
                <section class="left-login">
                    <?php if (isset($erro))
                        echo "<p class='error'>$erro</p>"; ?>
                    <form action="login.php" method="POST">
                </section>

                <section class="rigth-login">
                    <article class="card-login">
                        <h1>Login</h1>
                        <div class="textfield">
                            <label for="email">E-mail: </label>
                            <input type="text" name="email" placeholder="Digite seu e-mail" required>
                        </div>
                        <div class="textfield">
                            <label for="senha">Senha: </label>
                            <input type="password" name="senha" placeholder="Digite sua senha" required>
                        </div>
                        <button class="btn-login">Login</button>

                        <br><br>

                        <div class="login-link">
                            <h4>Não é cadastrado?</h4>
                            <button class="btn-login" onclick="window.location.href='usercadastro.php'">Realizar cadastro</button>
                        </div>

                            <div class="login-link">
                                <a href="index2.php" class="login-link">Voltar ao Início</a>
                            </div>
                    </article>
                </section>
            </main>
        </div>
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
        <p>&copy; 2024 Seu Site. Todos os direitos reservados.</p>
    </footer>
    <!-- Script para criar as estrelas e incluir o piscar aleatório, para que as mesmas pisquem naturalmente -->
    <script src="/cosmocrewONG/js/cad.js"></script>
</body>
</html>