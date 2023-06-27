<?php

// membuat koneksi ke system
$dbServer = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = "pemwebvape";

try {
    //membuat object PDO untuk koneksi ke database
    $connection = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
    // setting ERROR mode PDO: ada tiga mode error mode silent, warning, exception
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    echo "Failed Connect to Database Server : " . $err->getMessage();
}
