<?php

// membuat koneksi ke database
$dbServer = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = "pemwebvape";
session_start();

try {
    //membuat objek PDO untuk koneksi ke database
    $connection = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
    // setting mode error PDO: ada tiga mode yaitu silent, warning, exception
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err) {
    echo "Failed to connect to Database Server: " . $err->getMessage();
}

$nama_user = '';
$email_user = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['login']) || $_SESSION['login'] != 1 || !isset($_SESSION['userId'])) {
        header('location: ../login/login_user.php');
        exit;
    }
    $userId = $_SESSION['userId'];
    $deskripsi = $_POST['issue'];

    // Mencari data nama_user dan email_user berdasarkan ID_USER yang diambil dari session
    $query = "SELECT NAMA_USER, EMAIL_USER FROM user WHERE ID_USER = :userId";
    $statement = $connection->prepare($query);
    $statement->bindValue(':userId', $userId);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $nama_user = $result['NAMA_USER'];
    $email_user = $result['EMAIL_USER'];

    if ($result) {
        $nama_user = $result['NAMA_USER'];
        $email_user = $result['EMAIL_USER'];

        // Menyimpan data ke tabel report
        $query = "INSERT INTO report (ID_USER, DESK_REPORT) VALUES (:userId, :deskripsi)";
        $statement = $connection->prepare($query);
        $statement->bindValue(':userId', $userId);
        $statement->bindValue(':deskripsi', $deskripsi);
        $statement->execute();

        // Menampilkan pesan setelah data berhasil disimpan
        echo "Report submitted successfully!";
    } else {
        echo "User not found!";
    }
} else {
    // Jika belum melakukan POST (misalnya saat pertama kali masuk ke halaman)
    // Mencari data nama_user dan email_user berdasarkan ID_USER yang diambil dari session
    if (isset($_SESSION['login']) && $_SESSION['login'] == 1 && isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];

        $query = "SELECT NAMA_USER, EMAIL_USER FROM user WHERE ID_USER = :userId";
        $statement = $connection->prepare($query);
        $statement->bindValue(':userId', $userId);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $nama_user = $result['NAMA_USER'];
            $email_user = $result['EMAIL_USER'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>

    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="reset.css">

    <!--import gfonts-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400&display=swap');
    </style>

    <!--link font awesome-->
    <script src="https://kit.fontawesome.com/ad6991be8a.js" crossorigin="anonymous"></script>


</head>

<body>
    <?php include('../template/navbar.php');?>

    <div class="jumbotron">
        <img src="image/home.png" alt="">
    </div>

    <div id="aboutus" class="aboutus">
        <div class="box1">
            <img src="image/pod.png" alt="">
        </div>
        <div class="box2">
            <h2>About Us</h2>
            <p>Selamat datang di VapeStore.com, destinasi terpercaya Anda untuk semua kebutuhan vaping!
                Kami adalah toko online yang berkomitmen untuk menyediakan produk-produk vape berkualitas
                tinggi, aksesori terbaru, serta informasi dan layanan pelanggan yang luar biasa. <br><br>
                VapeStore.com, kami memahami bahwa vaping telah menjadi fenomena global yang meraih popularitas
                yang luar biasa. Kami berdedikasi untuk menyediakan platform yang lengkap dan mudah digunakan
                bagi para penggemar vaping dari berbagai kalangan, baik bagi pemula yang tertarik untuk memulai
                vaping maupun bagi vapers berpengalaman yang mencari produk terbaru dan terbaik.</p>
        </div>
    </div>

    <div id="product" class="product">
        <div class="kalimat_kategori">
            <h2>Product Categories</h2>
            <p>Kami bangga menyajikan beragam produk vape berkualitas tinggi di VapeStore.com. Dari rokok
                elektronik inovatif hingga e-liquid dengan rasa terbaik, kami memiliki semua yang Anda butuhkan
                untuk mengalami vaping yang memuaskan.
            </p>
        </div>
        <div class="kategori">
            <a href="../view-more-product/atomizer.php"><img src="image/product1.png" alt=""></a>
            <a href="../view-more-product/mod.php"><img src="image/product2.png" alt=""></a>
            <a href="../view-more-product/liquid.php"><img src="image/product3.png" alt=""></a>
            <a href="../view-more-product/baterai.php"><img src="image/product4.png" alt=""></a>
        </div>
        <div class="view-more">
            <a href="../view-more-product/all.php">View More</a>
        </div>
    </div>

    <div id="report" class="report">
        <div class="box2">
            <div class="box2-1">
                <h2>Report</h2>
                    <p>Kami menghargai setiap saran dan kritik yang Anda miliki untuk meningkatkan pengalaman Anda
                        di Vape Store. Jika Anda ingin melaporkan, jangan ragu untuk. Tim kami siap menjadi
                        penghubung dan merespons setiap umpan balik yang Anda berikan.
                    </p>
            </div>
            <div class="box2-2">
                <form action="" method="POST">
                    <div class="nama">
                        <i class="fa-regular fa-user"></i>
                        <input type="text" name="FN" placeholder="Name" value="<?php echo $nama_user; ?>" readonly>
                    </div>
                    <div class="email">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="text" name="email" at the end of the chat placeholder="Email" value="<?php echo $email_user; ?>" readonly>
                    </div>
                    <div class="desc">
                        <textarea name="issue" rows="4" placeholder="Please describe your issue" required></textarea>
                    </div>
                    <div class="button">
                        <button type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
</div>
</div>


<?php include('../template/footer.php');?>

<script src="https://kit.fontawesome.com/73bcd336f4.js"></script>

</body>

</html>
