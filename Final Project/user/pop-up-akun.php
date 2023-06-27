<?php
//memanggil file conn.php yang berisi koneski ke database
//dengan include, semua kode dalam file conn.php dapat digunakan pada file index.php
include('../connections.php');

$status = '';
$result = '';
//melakukan pengecekan apakah ada variable GET yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['ID_USER'])) {
        //query SQL
        $id_user_upd = $_GET['ID_USER'];
        $query = $connection->prepare("SELECT EMAIL_USER, USERNAME_USER FROM user WHERE ID_USER = :ID_USER");

        //binding data
        $query->bindParam(':ID_USER', $id_user_upd);

        //eksekusi query
        $query->execute();

        //fetch data
        $data = $query->fetch(PDO::FETCH_ASSOC);
    }
}

//melakukan pengecekan apakah ada form yang dipost
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['ID_USER'];
    $email_user = $_POST['EMAIL_USER'];
    $username_user = $_POST['USERNAME_USER'];

    $query = "UPDATE user SET EMAIL_USER=:EMAIL_USER, USERNAME_USER=:USERNAME_USER WHERE ID_USER=:ID_USER";

    $query = $connection->prepare($query);

    $query->bindParam(':ID_USER', $id_user);
    $query->bindParam(':EMAIL_USER', $email_user);
    $query->bindParam(':USERNAME_USER', $username_user);

    //eksekusi query
    if ($query->execute()) {
        $status = 'ok';
    } else {
        $status = 'err';
    }

    //redirect ke halaman lain
    header('Location: user-profil.php?status=' . $status);
}

?>

<?php if (!empty($data)) : ?>
    <div class="popup-overlay" id="popup-akun">
        <div class="popup-content">
            <h2 class="popup-title">Akun Saya</h2>
            <span class="popup-close" onclick="closePopup('popup-akun')"><i class="fas fa-times"></i></span>
            <form class="popup-form" action="pop-up-akun.php" method="POST">
                <input type="hidden" name="ID_USER" value="<?php echo $data['ID_USER']; ?>">
                <label for="ubah-nama">Nama</label>
                <input type="text" id="ubah-nama" name="USERNAME_USER" value="<?php echo $data['USERNAME_USER'];  ?>" required="required">
                <label for="ubah-email">E-mail</label>
                <input type="text" id="ubah-email" name="EMAIL_USER" value="<?php echo $data['EMAIL_USER'];  ?>" required="required">
                <button class="btn-save" type="submit">Simpan</button>
            </form>
        </div>
    </div>
<?php endif; ?>