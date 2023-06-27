<?php
include('../connections.php');
session_start();
if (!$_SESSION['login'] == 1 && !isset($_SESSION['adminId'])) {
    header('location: ../login/login_user.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET['id'];
    $query = "SELECT * FROM product where ID_PRODUCT = :id";
    $result = $connection->prepare($query);
    $result->bindParam(':id', $id);
    $result->execute();
    $data = $result->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idProduk = $_POST['id_produk'];
    $namaProduk = $_POST['nama_produk'];
    $hargaProduk = $_POST['harga_produk'];
    $kategoriProduct = $_POST['kategori_produk'];
    $deskProduk = $_POST['deskripsi_produk'];
    $gambarLama = $_POST['gambarLama'];

    $namaGambar = $_FILES['gambar_produk']['name'];
    $errorGambar = $_FILES['gambar_produk']['error'];
    $tmpGambar = $_FILES["gambar_produk"]["tmp_name"];

    if ($errorGambar === 4) {
        $namaGambarBaru = $gambarLama;
    } else {
        $ekstensiGambar = explode('.', $namaGambar);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
        $namaGambarBaru = uniqid() . '.' . $ekstensiGambar;
        move_uploaded_file($tmpGambar, '../resource/product/img/' . $namaGambarBaru);
        $pathLama = '../resource/product/img/' . $gambarLama;
        unlink($pathLama);
    }

    $query = $connection->prepare("UPDATE product SET name_product = :NAME_PRODUCT, price_product = :PRICE_PRODUCT, kategori_product = :KATEGORI_PRODUCT, desk_product = :DESK_PRODUCT, gambar_product = :GAMBAR_PRODUCT WHERE ID_PRODUCT = :ID_PRODUCT");

    //binding data
    $query->bindParam(':NAME_PRODUCT', $namaProduk);
    $query->bindParam(':PRICE_PRODUCT', $hargaProduk);
    $query->bindParam(':KATEGORI_PRODUCT', $kategoriProduct);
    $query->bindParam(':DESK_PRODUCT',  $deskProduk);
    $query->bindParam(':GAMBAR_PRODUCT',  $namaGambarBaru);
    $query->bindParam(':ID_PRODUCT', $idProduk);

    if ($query->execute()) {
        $status = 'ok_update';
        header('location: admin-product.php?status=' . $status);
        exit;
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
    <title>Admin Report</title>
    <link rel="stylesheet" href="css/admin-updateProduct.css">
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
            <h1>Edit Produk</h1>
            <form action="admin-updateProduct.php?id=<?php echo $data['ID_PRODUCT']; ?>" method="post" enctype="multipart/form-data">
                <div class="update1">
                    <div class="gmbr-konfr">
                        <div class="ganti-gambar">
                            <label for="inputGambar">
                                <img src="../resource/product/img/<?php echo $data['GAMBAR_PRODUCT']; ?>" alt="" id="gambarPreview">
                                <p id="gambarNama">Ganti gambar produk</p>
                            </label>
                            <input type="file" name="gambar_produk" id="inputGambar" onchange="previewGambar()" accept="image/*" style="display: none;">
                        </div>
                        <button type="submit">KONFIRMASI</button>
                    </div>

                    <div class="isi-update">
                        <div class="add2">
                            <i class="fa-solid fa-pen"></i>
                            <input type="text" class="form-control" name="nama_produk" id="nama_produk" placeholder="Nama produk" value="<?php echo $data['NAME_PRODUCT']; ?>">
                        </div>
                        <div class="add2">
                            <i class="fa-solid fa-pen"></i>
                            <p>Rp.</p>
                            <input type="text" class="form-control" name="harga_produk" id="harga_produk" placeholder="Harga produk" value="<?php echo $data['PRICE_PRODUCT']; ?>">
                        </div>
                        <div class="add2">
                            <i class="fa-solid fa-pen"></i>
                            <select class="form-control" name="kategori_produk" id="kategori_produk">
                                <option value="" selected disabled>Kategori Produk</option>
                                <option value="Atomizer" <?php if ($data['KATEGORI_PRODUCT'] == 'Atomizer') echo 'selected'; ?>>Atomizer</option>
                                <option value="Mod" <?php if ($data['KATEGORI_PRODUCT'] == 'Mod') echo 'selected'; ?>>Mod</option>
                                <option value="Liquid" <?php if ($data['KATEGORI_PRODUCT'] == 'Liquid') echo 'selected'; ?>>Liquid</option>
                                <option value="Baterai" <?php if ($data['KATEGORI_PRODUCT'] == 'Baterai') echo 'selected'; ?>>Baterai</option>
                            </select>
                        </div>
                        <div class="add3">
                            <i class="fa-solid fa-pen"></i>
                            <textarea class="form-control" name="deskripsi_produk" id="deskripsi_produk" placeholder="Deskripsi produk"><?php echo $data['DESK_PRODUCT']; ?></textarea>
                        </div>
                        <i class="fa-solid fa-pen-line"></i>
                    </div>
                </div>
                <input type="hidden" name="id_produk" value="<?php echo $data['ID_PRODUCT']; ?>">
                <input type="hidden" name="gambarLama" value="<?php echo $data['GAMBAR_PRODUCT']; ?>">
            </form>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/73bcd336f4.js" crossorigin="anonymous"></script>
    <script>
        function previewGambar() {
            var gambarInput = document.getElementById('inputGambar');
            var gambarPreview = document.getElementById('gambarPreview');
            var gambarNama = document.getElementById('gambarNama');


            var fileGambar = gambarInput.files[0];
            var namaGambar = fileGambar.name;

            var reader = new FileReader();
            reader.onload = function(e) {
                gambarPreview.src = e.target.result;
                gambarPreview.style.display = 'block';
                gambarNama.textContent = 'Nama Gambar: ' + namaGambar;
            }
            reader.readAsDataURL(fileGambar);
        }
    </script>
</body>

</html>
