<?php
include('../connections.php');

$query = "SELECT * FROM product";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$itemCount = 0; // Inisialisasi itemCount dengan nilai awal 0

if (isset($_SESSION["cart_item"]) && is_array($_SESSION["cart_item"])) {
    // Memeriksa apakah $_SESSION["cart_item"] sudah ada dan merupakan array
    $itemCount = count($_SESSION["cart_item"]);
}
?>

<head>
    <link rel="stylesheet" href="../template/navbar.css">
    <script src="https://kit.fontawesome.com/ad6991be8a.js" crossorigin="anonymous"></script>
</head>

<div class="bgheader" id="home">
    <header>
        <p>Welcome to Vape. salah satu online vape shop terbaik di Indonesia</p>
    </header>
</div>

    <div class="bar">
        <div class="gambar">
            <a href="#"><img src="image/logovapehitam.png" alt=""></a>
        </div>
        <div class="tombol">
            <a href="../home/HOME.php">HOME</a>
            <a href="../home/HOME.php#aboutus">ABOUT US</a>
            <a href="../home/HOME.php#product">PRODUCT</a>
            <a href="../home/HOME.php#report">REPORT</a>
            <div class="tombol1">
                <a href="../transaksi/keranjang.php"><i class="fa-solid fa-cart-shopping"><?php echo $itemCount; ?></i></a>
            </div>
            <div class="tombol1">
                <a href="../user/user-profil.php"><i class="fa-solid fa-user"></i></a>
            </div>
            <?php 
            if (isset($_SESSION['login'])  && isset($_SESSION['userId'])): ?>
                <div class="tombol2">
                <a href="../login/logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        <?php endif ?>
    </div>
</div>