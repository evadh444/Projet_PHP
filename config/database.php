<?php

// Informations de connexion à la base de données

$source ="sqlsrv";
$host ="WAD-03\IF3";
$dbname ="henna_pdo";


$dsn ="$source:Server=$host;Database=$dbname; TrustServerCertificate=true";
$username = "henna_user";
$password = "Test1234=";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];


// Tentative de connexion, choper l'erreur et l'afficher s'il y en a une
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erreur de connexion : ". $e->getMessage());
}



?>
