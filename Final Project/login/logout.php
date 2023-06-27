<?php
session_start();
unset($_SESSION['login']);
// Hapus semua data session
session_destroy();
// Redirect ke halaman login
header('Location: login_user.php');
exit;
?>