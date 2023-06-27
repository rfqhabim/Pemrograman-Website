<?php
include('../connections.php');
session_start();
$userId = $_SESSION['userId'];
$datetime = date('Y-m-d H:i:s');
$totalBayar = $_POST['totalBayar'];
$status = '';

$query = "SELECT * FROM user where ID_USER = :id";
$stmt = $connection->prepare($query);
$stmt->bindParam(':id', $userId);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($totalBayar > $result['SALDO']) {
    $status = 'saldokurang';
    header('location: keranjang.php?status=' . $status);
    exit;
}

$query = "INSERT INTO transaksi (ID_USER, TANGGAL_TRANSAKSI) VALUES (:ID_USER, :TANGGAL_TRANSAKSI)";
$stmt = $connection->prepare($query);
$stmt->bindParam(':ID_USER', $userId);
$stmt->bindParam(':TANGGAL_TRANSAKSI', $datetime);
$stmt->execute();

$transaksiId = $connection->lastInsertId();
if (!empty($_SESSION["cart_item"])) {
    foreach ($_SESSION["cart_item"] as $item) {
        $productId = $item['id'];
        $jumlah = $item['quantity'];
        $totalHarga = $item['price'] * $jumlah;

        $query = "INSERT INTO transaksi_item (ID_TRANSAKSI, ID_PRODUCT, JUMLAH, HARGA) VALUES (:ID_TRANSAKSI, :ID_PRODUCT, :JUMLAH, :HARGA)";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':ID_TRANSAKSI', $transaksiId);
        $stmt->bindParam(':ID_PRODUCT', $productId);
        $stmt->bindParam(':JUMLAH', $jumlah);
        $stmt->bindParam(':HARGA', $totalHarga);
        $stmt->execute();
    }
    $query = "UPDATE user set SALDO = (:saldo-:totalBayar) where ID_USER = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $userId);
    $stmt->bindParam(':saldo', $result['SALDO']);
    $stmt->bindParam(':totalBayar', $totalBayar);
    $stmt->execute();

    unset($_SESSION['cart_item']);
    $status = 'ok';
    header('location: keranjang.php?status=' . $status);
    exit;
}
header('location: keranjang.php');
exit;
