<?php
include '../includes/conexaodb.php'; 

// Variáveis para armazenar a mensagem de sucesso e erro.
$mensagem = ''; 
$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $data_nascimento = $_POST['data_nascimento'];
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;
    
    // Definindo o tipo de usuário
    // O valor do tipo de usuário pode ser passado como 'voluntario', 'gerente' ou 'admin', se necessário
    $tipo_usuario = 'usuario'; // Ajustado para refletir o nome da coluna no banco de dados

    // Validação da senha
    if (strlen($senha) < 8 || !preg_match('/[A-Z]/', $senha) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $senha)) {
        $mensagemErro = "Erro: A senha deve ter pelo menos 8 caracteres, incluindo uma letra maiúscula e um caractere especial.";
    } else {
        // Criptografando a senha
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

        try {
            // Preparando a chamada da stored procedure para cadastrar o usuário
            $stmt = $mysqli->prepare("CALL inserir_usuario(?, ?, ?, ?, ?, ?)");

            // Vinculando os parâmetros da query
            $stmt->bind_param("ssssss", $nome, $email, $senhaHash, $tipo_usuario, $telefone, $data_nascimento); // Usando tipo_usuario aqui

            // Executando a instrução
            if ($stmt->execute()) {
                $mensagem = "Usuário registrado com sucesso!";
            } else {
                $mensagemErro = "Erro ao cadastrar usuário.";
            }
        } catch (mysqli_sql_exception $e) {
            // Verifica se o erro é de entrada duplicada
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $mensagemErro = "Erro: Este e-mail já está registrado!";
            } else {
                $mensagemErro = "Erro ao cadastrar usuário: " . $e->getMessage();
            }
        } finally {
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../imgs/final.png" sizes="60x60" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylecad.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Cadastro Cosmo</title>
</head>

<body>
<div class="starry-sky">
        <div class="stars">
            <main class="main-login">
                <section class="rigth-login">
                    <article class="card-login">
                        <h1>Cadastro</h1>
                        <form method="POST">
                            <div class="textfield">
                                <label for="nome">Nome completo: </label>
                                <input type="text" name="nome" placeholder="Digite seu nome completo" required>
                            </div>
                            <div class="textfield">
                                <label for="email">E-mail: </label>
                                <input type="email" name="email" placeholder="Digite seu e-mail" required>
                            </div>
                            <div id="requisitos" class="textfield">
                                <label for="senha">Senha: </label>
                                <input type="password" name="senha" id="senha" required minlength="8" oninput="validarSenha()">
                                <span class="toggle-password" onclick="mostrarSenha()">👁️</span>
                            </div>
                    
                            <div class="textfield">
                                    <label for="data">Data de Nascimento: </label>
                                    <input type="date" name="data_nascimento" required>
                            </div>
                            <div class="textfield">
                                <label for="telefone">Contato: </label>
                                <input type="text" name="telefone" placeholder="(xx) xxxx-xxxx" required>
                            </div>

                            <button type="submit" class="btn-cadastro">Finalizar cadastro</button>

                            <?php if ($mensagem): ?>
                                <div class="mensagem"><?php echo $mensagem; ?></div> <!-- Exibe mensagem de sucesso -->
                            <?php endif; ?>

                            <?php if ($mensagemErro): ?>
                                <div class="mensagem erro"><?php echo $mensagemErro; ?></div>
                                <!-- Exibe mensagem de erro -->
                            <?php endif; ?>

                             
                            <div class="cad-link">
                                <a href="Login.php" class="cad-link">Já tem uma conta? Faça Login</a>
                            </div>
                            
                        </form>
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

    <script src="../js/cad.js"></script>
</body>
</html>