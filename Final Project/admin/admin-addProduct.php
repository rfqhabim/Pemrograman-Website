<?php
include('../connections.php');
session_start();
if (!$_SESSION['login'] == 1 && !isset($_SESSION['adminId'])) {
    header('location: ../login/login_user.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nameProduct = $_POST['nama_produk'];
    $priceProduct = $_POST['harga_produk'];
    $kategoriProduct = $_POST['kategori_produk'];
    $deskProduct = $_POST['deskripsi_produk'];


    $namaGambar = $_FILES['gambar_produk']['name'];
    $errorGambar = $_FILES['gambar_produk']['error'];
    $tmpGambar = $_FILES["gambar_produk"]["tmp_name"];
    $ekstensiGambar = explode('.', $namaGambar);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    $namaGambarBaru = uniqid() . '.' . $ekstensiGambar;

    if ($errorGambar === 0) {
        $query = $connection->prepare("INSERT INTO product (name_product, price_product, kategori_product, desk_product, gambar_product) VALUES(:NAME_PRODUCT, :PRICE_PRODUCT, :KATEGORI_PRODUCT, :DESK_PRODUCT , :GAMBAR_PRODUCT)");

        //binding data
        $query->bindParam(':NAME_PRODUCT', $nameProduct);
        $query->bindParam(':PRICE_PRODUCT', $priceProduct);
        $query->bindParam(':KATEGORI_PRODUCT', $kategoriProduct);
        $query->bindParam(':DESK_PRODUCT',  $deskProduct);
        $query->bindParam(':GAMBAR_PRODUCT',  $namaGambarBaru);

        if ($query->execute()) {

            move_uploaded_file($tmpGambar, '../resource/product/img/' . $namaGambarBaru);
            $status = 'ok';
            header('location: admin-product.php?status=' . $status);
            exit;
        } else {
            $status = 'err';
            header('location: admin-product.php?status=' . $status);
            exit;
        }
    } else {
        $status = 'err';
        header('location: admin-product.php?status=' . $status);
        exit;
    }
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
    <title>Admin Add Product</title>
    <link rel="stylesheet" href="css/admin-addProduct.css">
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

        <div class="add-product">
            <h1>Tambah Produk</h1>
            <form action="admin-addProduct.php" method="post" enctype="multipart/form-data">
                <div class="add1">
                    <div class="add-gambar-konfr">
                        <div class="add-gambar">
                            <label for="inputGambar">
                                <i id="iconGambar" class="fa-regular fa-image"></i>
                                <img id="gambarPreview" src="#" alt="Preview Gambar" style="display: none;">
                                <p id="gambarNama">Tambah gambar produk</p>
                            </label>
                            <input type="file" name="gambar_produk" id="inputGambar" onchange="previewGambar()" accept="image/*">
                        </div>
                        <div class="button-choice">
                            <button type="submit">Add Produk</button>
                        </div>
                    </div>

                    <div class="addPrdct">
                        <div class="add2">
                            <i class="fa-solid fa-pen"></i>
                            <input type="text" class="form-control" name="nama_produk" id="nama_produk" placeholder="Nama produk">
                        </div>
                        <div class="add2">
                            <i class="fa-solid fa-pen"></i>
                            <input type="text" class="form-control" name="harga_produk" id="harga_produk" placeholder="Harga produk">
                        </div>
                        <div class="add2">
                            <i class="fa-solid fa-pen"></i>
                            <select class="form-control" name="kategori_produk" id="kategori_produk">
                                <option value="" selected disabled>Kategori Produk</option>
                                <option value="Atomizer">Atomizer</option>
                                <option value="Mod">Mod</option>
                                <option value="Liquid">Liquid</option>
                                <option value="Baterai">Baterai</option>
                            </select>
                        </div>
                        <div class="add3">
                            <i class="fa-solid fa-pen"></i>
                            <textarea class="form-control" name="deskripsi_produk" id="deskripsi_produk" placeholder="Deskripsi produk"></textarea>
                        </div>
                        <i class="fa-solid fa-pen-line"></i>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/73bcd336f4.js" crossorigin="anonymous"></script>

    <script>
        function previewGambar() {
            var gambarInput = document.getElementById('inputGambar');
            var gambarPreview = document.getElementById('gambarPreview');
            var gambarNama = document.getElementById('gambarNama');
            var icon = document.getElementById('iconGambar');

            var fileGambar = gambarInput.files[0];
            var namaGambar = fileGambar.name;

            var reader = new FileReader();
            reader.onload = function(e) {
                icon.style.display = 'none';
                gambarPreview.src = e.target.result;
                gambarPreview.style.display = 'block';
                gambarNama.textContent = 'Nama Gambar: ' + namaGambar;
            }
            reader.readAsDataURL(fileGambar);
        }
    </script>
</body>

</html>
