<?php
// Conexao.php - Conexão com o banco de dados

$host   = "127.0.0.1";
$usuario = "root";
$senha  = "root";
$banco  = "sistema";
$porta = 3307;


$mysqli = new mysqli($host, $usuario, $senha, $banco, $porta);

if ($mysqli->connect_errno) {
    echo "Falha ao conectar ao MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}
?>