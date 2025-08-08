<?php
    $servername = "localhost";
    $username = "root"; // Nome de usuário do MySQL
    $password = "asdy2005"; // Senha do MySQL
    $dbname = "PAR3"; // Nome do banco de dados

    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Checagem da conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
?>