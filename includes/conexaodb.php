<?php
// db_config.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cosmocrewong";

// Criar conexão
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Verifica erro na conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}
?>

