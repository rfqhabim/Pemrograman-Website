<?php 
include('../connections.php');
$status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_user = $_POST["email"];
    $pass_user = $_POST["newPass"];
    $passConfirm_user = $_POST["confirmPass"];
    

    if ($pass_user != $passConfirm_user) {
        $status = 'err';
    }else {
        $query = "SELECT EMAIL_USER FROM user where EMAIL_USER = :email";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':email', $email_user);
        $stmt->execute();
        $result_email = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($result_email['EMAIL_USER']) && $result_email['EMAIL_USER'] ==$email_user) {
            $query = "update user set PASSWORD_USER = :password where EMAIL_USER = :email";
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':password', $pass_user);
            $stmt->bindParam(':email', $email_user);
            $stmt->execute();
            $status ='ok_gantiPass';
            header('Location: login_user.php?status='.$status);
            exit();
        }
        else{
            $status = 'err';
        }
    }
    var_dump($result_email);
    var_dump($status);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password user</title>
    <link rel="stylesheet" href="../login/css/forgot_pass_user.css">
</head>

<body>
    <div class="background-image"></div>
    <div class="form-container">
        <div class="form">
            <?php
            if ($status == 'ok') {
                echo '<br><br>Password Berhasil diganti';
            } elseif ($status == 'err') {
                echo '<br><br>Email Tidak ada/Password Tidak sama';
            }
            ?>
            <h2>Change password</h2>
            <form action="forgot_pass_user.php" method="POST">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="text" id="email" name="email" >
                </div>
                <div class=" form-group">
                    <label for="newPass">New password :</label>
                    <input type="password" id="newPass" name="newPass">
                </div>
                <div class="form-group">
                    <label for="confirmPass">Confirm password:</label>
                    <input type="password" id="confirmPass" name="confirmPass">
                </div>
                <button class="submit-button" type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>
