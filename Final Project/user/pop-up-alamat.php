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
        $query = $connection->prepare("SELECT NAMA_USER, ADDRESS, NUMBER_PHONE FROM user WHERE ID_USER = :ID_USER");

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
    $nama_user = $_POST['NAMA_USER'];
    $address = $_POST['ADDRESS'];
    $number_phone = $_POST['NUMBER_PHONE'];

    //query SQL
    $query = $connection->prepare("UPDATE user SET NAMA_USER=:NAMA_USER, ADDRESS=:ADDRESS, NUMBER_PHONE=:NUMBER_PHONE WHERE ID_USER=:ID_USER");

    //binding data
    $query->bindParam(':ID_USER', $id_user);
    $query->bindParam(':NAMA_USER', $nama_user);
    $query->bindParam(':ADDRESS', $address);
    $query->bindParam(':NUMBER_PHONE', $number_phone);

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
    <div class="popup-overlay" id="popup-alamat">
        <div class="popup-content">
            <h2 class="popup-title">Alamat Saya</h2>
            <span class="popup-close" onclick="closePopup('popup-alamat')"><i class="fa fa-times"></i></span>
            <form class="popup-form" action="pop-up-alamat.php" method="POST">
                <label for="ubah-nama-penerima">Nama Penerima</label>
                <input type="text" id="ubah-nama-penerima" name="NAMA_USER" value="<?php echo $data['NAMA_USER'];  ?>" required="required">
                <input type="hidden" name="ID_USER" value="<?php echo $data['ID_USER']; ?>">
                <label for="ubah-alamat">Alamat</label>
                <input type="text" id="ubah-alamat" name="ADDRESS" value="<?php echo $data['ADDRESS'];  ?>" required="required">
                <label for="ubah-no-telepon">Nomor telepon</label>
                <input type="text" id="ubah-no-telepon" name="NUMBER_PHONE" value="<?php echo $data['NUMBER_PHONE'];  ?>" required="required">
                <button class="btn-save" type="submit">Simpan</button>
            </form>
        </div>
    </div>
<?php endif; ?>