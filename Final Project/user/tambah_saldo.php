<?php
include('../connections.php');
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] != 1 || !isset($_SESSION['userId'])) {
    header('location: ../login/login_user.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $saldoToAdd = $_POST['saldo'];

    // Validasi input saldo
    if (!is_numeric($saldoToAdd) || $saldoToAdd <= 0) {
        echo "Jumlah saldo yang dimasukkan tidak valid.";
        exit;
    }

    $userId = $_SESSION['userId'];

    // Ambil saldo saat ini dari database
    $query = "SELECT SALDO FROM user WHERE ID_USER = :userId";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $currentBalance = $stmt->fetchColumn();

    // Tambahkan saldo baru
    $newBalance = $currentBalance + $saldoToAdd;

    // Perbarui saldo pengguna di database
    $updateQuery = "UPDATE user SET saldo = saldo + :saldoToAdd WHERE ID_USER = :userId";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bindParam(':saldoToAdd', $saldoToAdd);
    $updateStmt->bindParam(':userId', $userId);
    $updateStmt->execute();

    // Redirect kembali ke halaman profil pengguna setelah memperbarui saldo
    header('location: user-profil.php');
    exit;
} else {
    echo "Metode permintaan tidak valid.";
    exit;
}
