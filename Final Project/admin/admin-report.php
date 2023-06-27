<?php
include("../connections.php");

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
    <link rel="stylesheet" href="../admin/css/admin-report.css">
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
                    <li class=""><i class="fa-solid fa-bag-shopping"></i><a href="admin-product.php"> Product</a></li>
                    <li class=""><i class="fa-solid fa-stopwatch"></i><a href="admin-recent.php"> Recent Activity</a></li>
                    <li class="active"><i class="fa-solid fa-flag"></i><a href="admin-report.php">Report</a></li>
                </ul>
            </div>
            <div class="logout"><a href="../login/login_user.php"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a></div>
        </div>

        <div class="container-main">
            <h1>Report from user</h1>
            <?php
            $query = "SELECT user.NAMA_USER, user.EMAIL_USER, report.DESK_REPORT
                      FROM report
                      INNER JOIN user ON report.ID_USER = user.ID_USER";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($reports as $report) {
                $namaUser = $report['NAMA_USER'];
                $emailUser = $report['EMAIL_USER'];
                $deskReport = $report['DESK_REPORT'];
            ?>
                <div class="report1">
                    <div class="data-report">
                        <img src="img/user-solid.svg" alt="">
                        <div class="nama-report">
                            <h3><?php echo $namaUser; ?></h3>
                            <p><?php echo $emailUser; ?></p>
                        </div>
                    </div>
                    <div class="isi-report">
                        <p><?php echo $deskReport; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/73bcd336f4.js" crossorigin="anonymous"></script>
</body>

</html>