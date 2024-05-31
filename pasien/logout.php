<?php
session_start();

// Menghapus semua cookie yang tersimpan
setcookie('id', '', time() - 3600, '/');
setcookie('no_ktp', '', time() - 3600, '/');
setcookie('nama', '', time() - 3600, '/');
setcookie('alamat', '', time() - 3600, '/');
setcookie('no_hp', '', time() - 3600, '/');
setcookie('no_rekam_medis', '', time() - 3600, '/');

// Menghapus session
session_unset();
session_destroy();

// Redirect ke halaman logout
header("Location: ../");
exit();
?>