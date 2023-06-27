<?php
include('../connections.php');
session_start();
if (!$_SESSION['login'] == 1 && !isset($_SESSION['adminId'])) {
    header('location: ../login/login_user.php');
    exit;
}
if (isset($_GET['status'])) {
    $status = $_GET['status'];
} else {
    $status = '';
}

// Mengambil data usernmae_admin dari tabel admin
$query = "SELECT USERNAME_ADMIN FROM admin LIMIT 1";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$usernameAdmin = $result['USERNAME_ADMIN'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN PRODUCT</title>
    <link rel="stylesheet" href="css/admin-product.css">
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
                    <li class=""><i class="fa-solid fa-house"></i><a href="admin-dashboard.php">Dashboard</a></li>
                    <li class="active"><i class="fa-solid fa-bag-shopping"></i><a href="admin-product.php"> Product</a></li>
                    <li class=""><i class="fa-solid fa-stopwatch"></i><a href="admin-recent.php"> Recent Activity</a></li>
                    <li class=""><i class="fa-solid fa-flag"></i><a href="admin-report.php">Report</a></li>
                </ul>
            </div>
            <div class="logout"><a href="../login/login_user.php"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a></div>
        </div>
        <div class="container-main">
            <?php
            if ($status == 'ok') {
                echo '<b>Berhasil ditambahkan</b>';
            } elseif ($status == 'ok_hapus') {
                echo '<b>Berhasil Dihapus</b>';
            } elseif ($status == 'err_hapus') {
                echo '<b>Gagal Dihapus/Produk masih ada di transaksi</b>';
            } elseif ($status == 'ok_update') {
                echo '<b>Berhasil DiUpdate</b>';
            } elseif ($status == 'err') {
                echo '<b>Gagal</b>';
            }
            ?>
            <div class="add-product">
                <a href="admin-AddProduct.php">Add Product <i class="fa-solid fa-cart-shopping"></i></a>
            </div>
            <div class="list-product">
                <?php $query = "SELECT * FROM product";
                $result = $connection->query($query);

                ?>
                <?php while ($data = $result->fetch(PDO::FETCH_ASSOC)) : ?>
                    <div class="product">
                        <div class="product-img">
                            <img src="../resource/product/img/<?php echo $data['GAMBAR_PRODUCT']; ?>" alt="">
                        </div>
                        <div class="product-info">
                            <h2><?php echo $data['NAME_PRODUCT']; ?></h2>
                            <p>Rp. <?php echo $data['PRICE_PRODUCT']; ?></p>
                        </div>
                        <div class="product-action">
                            <form action="admin-deleteProduct.php" method="post">
                                <input type="hidden" name="id_product" value="<?php echo $data['ID_PRODUCT']; ?>">
                                <button type="submit" onclick="return confirm('Anda yakin ingin menghapus produk ini?')">Delete</button>
                            </form>
                            <a href="admin-updateProduct.php?id=<?php echo $data['ID_PRODUCT']; ?>">Edit</a>
                        </div>
                    </div>
                <?php endwhile ?>

            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/73bcd336f4.js" crossorigin="anonymous"></script>
</body>

</html>