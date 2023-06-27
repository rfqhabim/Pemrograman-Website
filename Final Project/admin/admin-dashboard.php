<?php
include("../connections.php");
session_start();
if (!$_SESSION['login'] == 1 && !isset($_SESSION['adminId'])) {
    header('location: ../login/login_user.php');
    exit;
}

// Mengambil data usernmae_admin dari tabel admin
$query = "SELECT USERNAME_ADMIN FROM admin LIMIT 1";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$usernameAdmin = $result['USERNAME_ADMIN'];

// Mengambil data jumlah produk dari tabel product
$query = "SELECT COUNT(*) AS jumlah_produk FROM product";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$jumlahProduk = $result['jumlah_produk'];

// Mengambil total pemasukan dari tabel transaksi_item
$query = "SELECT SUM(HARGA * JUMLAH) AS total_pemasukan FROM transaksi_item";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$totalPemasukan = $result['total_pemasukan'];

// Mengambil total keuntungan dari tabel transaksi_item
$query = "SELECT SUM((PRICE_PRODUCT * 0.1) * JUMLAH) AS total_keuntungan 
          FROM transaksi_item 
          INNER JOIN product ON transaksi_item.ID_PRODUCT = product.ID_PRODUCT";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$totalKeuntungan = $result['total_keuntungan'];

// Mengambil jumlah pengguna dari tabel user
$query = "SELECT COUNT(*) AS jumlah_pengguna FROM user";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$jumlahPengguna = $result['jumlah_pengguna'];

// Mengambil jumlah pesanan dari tabel transaksi_item
$query = "SELECT COUNT(*) AS jumlah_pesanan FROM transaksi";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$jumlahPesanan = $result['jumlah_pesanan'];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN DASHBOARD</title>
    <link rel="stylesheet" href="css/admin-dashboard.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo"><img src="img/Vape.png" alt=""></div>
            <div class="profile-preview">
                <img src="img/userprofile.jpg" alt="">
                <h3><?php echo $usernameAdmin; ?></h3>
                <p>Admin</p>
            </div>
            <div class="list-link">
                <ul>
                    <li class="active"><i class="fa-solid fa-house"></i><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class=""><i class="fa-solid fa-bag-shopping"></i><a href="admin-product.php"> Product</a></li>
                    <li class=""><i class="fa-solid fa-stopwatch"></i><a href="admin-recent.php"> Recent Activity</a></li>
                    <li class=""><i class="fa-solid fa-flag"></i><a href="admin-report.php">Report</a></li>
                </ul>
            </div>
            <div class="logout"><a href="../login/login_user.php"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a></div>
        </div>
        <div class="container-main">
            <div class="section1">
                <div class="greeting">
                    <p>Hello, Welcome Back</p>
                    <h3>MANAGE YOUR MARKETPLACE</h3>
                </div>
                <div class="logo"><img src="img/Vape.png" alt=""></div>
            </div>
            <div class="section2">
                <div class="keuangan">
                    <div class="keuangan-detail">
                        <p>Pemasukan</p>
                        <h2><?php echo number_format($totalPemasukan, 0, ',', '.'); ?></h2>
                    </div>
                    <div class="keuangan-detail">
                        <p>Jumlah Produk</p>
                        <h2><?php echo $jumlahProduk; ?></h2>
                    </div>
                    <div class="keuangan-detail">
                        <p>Keuntungan</p>
                        <h2><?php echo number_format($totalKeuntungan, 0, ',', '.'); ?></h2>
                    </div>
                </div>
                <div class="user-info">
                    <div class="user-info-detail">
                        <div class="icon"><i class="fa-solid fa-users"></i></div>
                        <div class="information">
                            <p>user yang sudah registrasi</p>
                            <h2><?php echo $jumlahPengguna; ?></h2>
                        </div>
                    </div>
                    <div class="user-info-detail">
                        <div class="icon"><i class="fa-solid fa-basket-shopping"></i></div>
                        <div class="information">
                            <p>Jumlah Pesanan</p>
                            <h2><?php echo $jumlahPesanan; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://kit.fontawesome.com/73bcd336f4.js" crossorigin="anonymous"></script>
</body>

</html>