<?php
session_start();

// Destruir a sessão
session_destroy();

// Redirecionar para o index.php dentro da pasta 'pages'
header("Location: ../pages/index2.php");
exit();
?>
