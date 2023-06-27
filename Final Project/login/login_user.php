<?php
//memanggil file conn.php yang berisi koneski ke database
//dengan include, semua kode dalam file conn.php dapat digunakan pada file index.php
include('../connections.php');
if (isset($_GET['status'])) {
    $status = $_GET['status'];
} else {
    $status = '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../login/css/login_user.css">
</head>

<body>
    <div class="container">
        <div class="login-box">
            <img class="logo" src="../login/image/logo.png" alt="Logo" style="width: 100px;">

            <div class="user-type-container">
                <div class="user-type user">User</div>
                <div class="user-type admin">Admin</div>
            </div>
            <?php
            if ($status == 'ok_gantipass') {
                echo '<b>Password Berhasil diganti, Silahkan Login</b>';
            } elseif ($status == 'ok_daftar') {
                echo '<b>Daftar Berhasil, Silahkan Login</b>';
            }
            ?>
            <form action="loginAction.php" method="post">
                <div class="form-container">
                    <label for="username">Username/Email:</label><br>
                    <input type="text" id="username" name="username">
                </div>

                <div class="form-container">
                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password">
                    <div class="forgot-password"><a href="../login/forgot_pass_user.php">Forgot Password</a></div>
                </div>

                <div class="checkbox-container">
                    <input type="checkbox" id="age-check" name="age-check">
                    <label for="age-check">I am 18 years or older</label>
                </div>
                <input type="hidden" name="typeUser" value="user">
                <button class="login-button" type="submit">Login</button>
            </form>

            <div class="account-text">
                You don't have an account? <a href="create_acc_user.php">Create account</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userTypes = document.querySelectorAll('.user-type');
            const accountText = document.querySelector('.account-text');
            const forgotPassword = document.querySelector('.forgot-password');
            const typeUserInput = document.querySelector('input[name="typeUser"]');
            userTypes.forEach(userType => {
                userType.addEventListener('click', () => {
                    userTypes.forEach(userType => userType.classList.remove('clicked'));
                    userType.classList.add('clicked');
                    if (userType.classList.contains('admin')) {
                        const checkboxContainer = document.querySelector('.checkbox-container');
                        checkboxContainer.classList.add('hide-checkbox');
                        accountText.style.display = 'none';
                        forgotPassword.style.display = 'none';
                        typeUserInput.value = 'admin';
                        console.log(typeUserInput.value);
                    } else {
                        const checkboxContainer = document.querySelector('.checkbox-container');
                        checkboxContainer.classList.remove('hide-checkbox');
                        accountText.style.display = 'block';
                        forgotPassword.style.display = 'block';
                        typeUserInput.value = 'user';
                        console.log(typeUserInput.value);
                    }
                });
            });

            // Menambahkan kelas 'clicked' ke kolom "User" secara otomatis
            const defaultUserType = document.querySelector('.user-type.user');
            defaultUserType.classList.add('clicked');

            // Menangani klik tombol login
            const loginButton = document.querySelector('.login-button');
            loginButton.addEventListener('click', () => {
                const username = document.querySelector('#username').value;
                const password = document.querySelector('#password').value;
            });
        });
    </script>
</body>

</html>