<?php

$host = 'localhost';
$banco = 'banco';
$usuario = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$banco};charset=utf8mb4", $usuario, $password, [

    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC

    ]);
    
} catch (PDOException $e) {
    die("Falha na conexao" . $e->getMessage());
}


?>