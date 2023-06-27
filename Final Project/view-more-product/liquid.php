<?php
include('../connections.php');
session_start();

$query = "SELECT * FROM product WHERE kategori_product = 'liquid' or 'Liquid'";
$stmt = $connection->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$itemCount = 0; // Inisialisasi itemCount dengan nilai awal 0

if (isset($_SESSION["cart_item"]) && is_array($_SESSION["cart_item"])) {
    // Memeriksa apakah $_SESSION["cart_item"] sudah ada dan merupakan array
    $itemCount = count($_SESSION["cart_item"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALL</title>

    <link rel="stylesheet" href="styleviewmore.css">
    <link rel="stylesheet" href="reset.css">

    <!--link font awesome-->
    <script src="https://kit.fontawesome.com/ad6991be8a.js" crossorigin="anonymous"></script>
</head>

<body>
<?php include('../template/navbar.php'); ?>

    <div class="bars">
        <div class="button-choice">
            <a href="all.php">All</a>
            <a href="atomizer.php">Atomizer</a>
            <a href="mod.php">Mod</a>
            <a class="nav-link active" href="liquid.php">Liquid</a>
            <a href="baterai.php">Baterai</a>
        </div>
    </div>

    <div class="container-main">
        <?php foreach ($result as $data) : ?>
            <?php if ($data['KATEGORI_PRODUCT'] == 'Liquid') : ?>
                <div class="box">
                    <div class="card" style="background-color: #b5b0b0;">
                        <div class="image">
                            <img src="../resource/product/img/<?php echo $data['GAMBAR_PRODUCT']; ?>" alt="...">
                        </div>
                        <div class="text">
                            <h4><?php echo $data['NAME_PRODUCT']; ?></h4>
                            <p><?php echo $data['PRICE_PRODUCT']; ?></p>
                        </div>
                        <div class="link">
                            <a href="../transaksi/addToCart.php?action=plus&id=<?php echo $data['ID_PRODUCT']; ?>&link=product">Beli</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="bgfooter">
        <div class="box3">
            <img src="image/logo.png" alt="">
        </div>
        <div class="box4">
            <p>Follow us on</p>
            <div class="sosmed">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </div>

    <footer>
        <p>Copyright 2023 © VAP.COM</p>
    </footer>
</body>

</html>
