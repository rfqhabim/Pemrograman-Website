<?php
include('../connections.php');
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] != 1 || !isset($_SESSION['userId'])) {
    header('location: ../login/login_user.php');
    exit;
}

$query = "SELECT * FROM user WHERE id_user = :userId";
$stmt = $connection->prepare($query);
$stmt->bindParam(':userId', $_SESSION['userId']);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$query_pesanan = "SELECT 
    product.name_product, 
    transaksi.id_transaksi, 
    transaksi.tanggal_transaksi, 
    transaksi_item.jumlah, 
    transaksi_item.harga
    FROM 
        product
    JOIN 
        transaksi_item ON product.id_product = transaksi_item.id_product
    JOIN 
        transaksi ON transaksi.id_transaksi = transaksi_item.id_transaksi
    WHERE
        transaksi.id_user = :userId;";

$stmt_pesanan = $connection->prepare($query_pesanan);
$stmt_pesanan->bindParam(':userId', $_SESSION['userId']);
$stmt_pesanan->execute();
$data_pesanan = $stmt_pesanan->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USER PROFIL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../user/user-profil-style.css">
</head>

<body>
    <div class="sidebar">
        <button class="button" onclick="goBack()">Kembali</button>
        <div class="logo">
            <img src="image/Vape.png" alt="">
        </div>
        <center>
            <img class="user-photo" src="../user/image/user.svg">
            <div class="name">
                <p><?php echo $data['USERNAME_USER']; ?></p>
            </div>
            <div class="balance">
                <i class="fas fa-wallet balance-icon"></i>
                <span>Saldo</span>
                <span class="balance-amount">Rp<?php echo number_format($data['SALDO'], 0, ',', '.'); ?></span>
            </div>

            <div class="boxSaldo">
                <form action="tambah_saldo.php" method="POST">
                    <input type="number" name="saldo" placeholder="Masukkan jumlah saldo" required>
                    <button type="submit">Tambah Saldo</button>
                </form>
            </div>
        </center>
        <div class="navigation">
            <a href="../home/home.php" class="navigation-item">
                <i class="fas fa-home navigation-icon"></i>
                <span>Home</span>
            </a> &nbsp
            <a href="../login/logout.php" class="navigation-item">
                <i class="fas fa-sign-out-alt navigation-icon"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <div class="content">
        <h1 class="welcome-text">Hello, Welcome <?php echo $data['USERNAME_USER']; ?></h1>

        <div class="box">
            <div class="box-row">
                <h2 class="box-title">Akun Saya</h2>
                <button class="box-button" onclick="openPopup('popup-akun')">Ubah</button>
            </div>
            <div class="box-content">
                <p class="box-text"><?php echo $data['USERNAME_USER']; ?></p>
                <p class="box-text"><?php echo $data['EMAIL_USER']; ?></p>
            </div>
        </div>

        <div class="box">
            <div class="box-row">
                <h2 class="box-title">Alamat Saya</h2>
                <button class="box-button" onclick="openPopup('popup-alamat')">Ubah</button>
            </div>
            <div class="box-content">
                <p class="box-text"><?php echo $data['NAMA_USER']; ?></p>
                <p class="box-text"><?php echo $data['ADDRESS']; ?></p>
                <p class="box-text"><?php echo $data['NUMBER_PHONE']; ?></p>
            </div>
        </div>

        <?php if (!empty($data_pesanan)) : ?>
            <div class="box">
                <h2 class="box-title">Pesanan Saya</h2>
                <div class="box-content">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>No Pesanan</th>
                                <th>Dipesan pada</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_pesanan as $pesanan) : ?>
                                <tr>
                                    <td><?php echo $pesanan['id_transaksi']; ?></td>
                                    <td><?php echo $pesanan['tanggal_transaksi']; ?></td>
                                    <td><?php echo $pesanan['name_product']; ?></td>
                                    <td><?php echo $pesanan['jumlah']; ?></td>
                                    <td><?php echo $pesanan['harga']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <?php include('pop-up-akun.php'); ?>
        <?php include('pop-up-alamat.php'); ?>
    </div>

    <script>
        function openPopup(popupId) {
            var popup = document.getElementById(popupId);
            popup.style.display = "block";
        }

        function closePopup(popupId) {
            var popup = document.getElementById(popupId);
            popup.style.display = "none";
        }

        // untuk kembali ke halaman sebelumnya
        function goBack() {
            history.back();
        }
    </script>
</body>

</html>