<?php
//memanggil file conn.php yang berisi koneksi ke database
//dengan include, semua kode dalam file conn.php dapat digunakan pada file index.php
include('../connections.php');

$status = '';

//melakukan pengecekan apakah ada form yang dipost
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek apakah ada data pada slide pertama atau slide kedua
    if (isset($_POST['slide1'])) {
        $nama_user = ($_POST["nama_user"]);
        $email_user = ($_POST["email_user"]);
        $username_user = ($_POST["username_user"]);
        $password_user = ($_POST["password_user"]);

        // Menyimpan data slide pertama ke dalam session
        session_start();
        $_SESSION['nama_user'] = $nama_user;
        $_SESSION['email_user'] = $email_user;
        $_SESSION['username_user'] = $username_user;
        $_SESSION['password_user'] = $password_user;
    } elseif (isset($_POST['slide2'])) {
        // Mengambil data dari session (data dari slide pertama)
        session_start();
        $nama_user = $_SESSION['nama_user'];
        $email_user = $_SESSION['email_user'];
        $username_user = $_SESSION['username_user'];
        $password_user = $_SESSION['password_user'];
        $address = ($_POST["address"]);
        $number_phone = ($_POST["number_phone"]);
        $saldo = 0; // Set saldo ke 0 secara otomatis

        //query dengan PDO
        $query = $connection->prepare("INSERT INTO user (nama_user, email_user, username_user, password_user, address, number_phone, saldo) VALUES(:nama_user, :email_user, :username_user, :password_user, :address, :number_phone, :saldo)");

        //binding data
        $query->bindParam(':nama_user',  $nama_user);
        $query->bindParam(':email_user', $email_user);
        $query->bindParam(':username_user', $username_user);
        $query->bindParam(':password_user',  $password_user);
        $query->bindParam(':address',  $address);
        $query->bindParam(':number_phone',  $number_phone);
        $query->bindParam(':saldo',  $saldo);

        //eksekusi query
        if ($query->execute()) {
            $status = 'ok_daftar';
            header('Location: login_user.php?status=' . $status);
            exit();
        } else {
            $status = 'err';
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
    <title>Create account user</title>
    <link rel="stylesheet" type="text/css" href="../login/css/create_acc_user.css">
</head>

<body>
    <div class="background-image"></div>
    <div class="form-container">
        <div class="form">
            <?php
            if ($status == 'ok_daftar') {
                echo '<br><br><div class="alert alert-success" role="alert">Data user berhasil disimpan</div>';
            } elseif ($status == 'err') {
                echo '<br><br><div class="alert alert-danger" role="alert">Data user gagal disimpan</div>';
            }
            ?>
            <?php if (!isset($_POST['slide1']) && !isset($_POST['slide2'])) : ?>
                <h2>Create an account</h2>
                <form action="../login/create_acc_user.php" method="POST">
                    <div class="form-group">
                        <label for="nama_user">Nama:</label>
                        <input type="text" id="nama_user" name="nama_user" placeholder="nama">
                    </div>
                    <div class="form-group">
                        <label for="username_user">Username:</label>
                        <input type="text" id="username_user" name="username_user" placeholder="username">
                    </div>
                    <div class="form-group">
                        <label for="email_user">Email:</label>
                        <input type="email" id="email_user" name="email_user" placeholder="email">
                    </div>
                    <div class="form-group">
                        <label for="password_user">Password:</label>
                        <input type="password" id="password_user" name="password_user" placeholder="password">
                    </div>
                    <button class="next-button" style="width: 500; padding: 10px 20px 10px 20px; background-color: #540F4D; color: white; border: none; border-radius: 20px; cursor: pointer;" name="slide1">Next</button>
                </form>
            <?php elseif (isset($_POST['slide1'])) : ?>
                <h2>Create an account</h2>
                <form action="../login/create_acc_user.php" method="POST">
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" placeholder="address">
                    </div>
                    <div class="form-group">
                        <label for="number_phone">Number:</label>
                        <input type="text" id="number_phone" name="number_phone" placeholder="number phone">
                    </div>
                    <!-- Tidak perlu mengisi kolom saldo -->
                    <button class="submit-button" name="slide2">Submit</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>