<?php
session_start(); // Memulai sesi
session_unset(); // Menghapus semua data sesi
session_destroy(); // Menghancurkan sesi
header("Location: login.php"); // Arahkan kembali ke halaman login setelah logout
exit();
?>
