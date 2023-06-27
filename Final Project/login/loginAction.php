<?php
include('../connections.php');
session_start();

var_dump($_POST);
if ($_SERVER['REQUEST_METHOD']=='POST') {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $typeUser = $_POST["typeUser"];
    if ($typeUser=='user') {
        $query = "SELECT * FROM user where EMAIL_USER = :email or USERNAME_USER = :username";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':email', $username);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if ($password == $result['PASSWORD_USER']) {
                $_SESSION["login"]= true;
				$_SESSION["userId"]= $result["ID_USER"];
                header('location: ../home/home.php');
				exit;
            }
            else {
                $status = 'err';
                header('Location: login_user.php?status=' . $status);
                exit();
            }
        }
    }elseif ($typeUser=='admin') {
        $query = "SELECT * FROM admin where EMAIL_ADMIN = :email or USERNAME_ADMIN = :username";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':email', $username);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if ($password == $result['PASSWORD_ADMIN']) {
                $_SESSION["login"]= true;
				$_SESSION["adminId"]= $result["ID_ADMIN"];
                header('location: ../admin/admin-dashboard.php');
				exit;
            }
            else {
                $status = 'err';
                header('Location: ../login/login_user.php?status=' . $status);
                exit();
            }
        }else {
            $status = 'err';
            header('Location: ../login/login_user.php?status=' . $status);
            exit();
        }
    }
}
?>