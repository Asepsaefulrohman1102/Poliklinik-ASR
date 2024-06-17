<?php
session_start(); // Pastikan ini adalah baris pertama sebelum output apapun
include_once('koneksi.php');

// Cek apakah user sudah login dan role-nya adalah Dokter
if (!isset($_SESSION['loggedin']) && isset($_COOKIE['name'])) {
    $nama_dokter_cookie = mysqli_real_escape_string($conn, $_COOKIE['name']);
    
    // Query untuk memeriksa apakah nama dokter dari cookie ada di tabel dokter
    $sql = "SELECT * FROM dokter WHERE nama_dokter='$nama_dokter_cookie'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Jika ada, set session dan izinkan akses
        $_SESSION['loggedin'] = true;
        $_SESSION['name'] = $nama_dokter_cookie;
    } else {
        // Jika tidak ada, arahkan kembali ke halaman login atau halaman utama
        header('Location: ../');
        exit();
    }
} elseif (!isset($_SESSION['loggedin']) ) {
    // Jika session tidak ada atau role bukan Dokter, arahkan ke halaman utama
    header('Location: ../');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Permata Poli - Dokter</title>
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../images/logo-1.png" />

  <link rel="stylesheet" href="../vendors/feather/feather.css">
  <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../js/select.dataTables.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
  <div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="../images/logo-1.png" class="mr-2" alt="logo"/>Permata</a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../images/logo-1.png" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-start">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>

    <div class="container-fluid page-body-wrapper">
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="?page=dashboard">
                    <i class="fas fa-tachometer-alt menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=jadwal/jadwal_periksa">
                    <i class="fas fa-calendar-alt menu-icon"></i>
                    <span class="menu-title">Jadwal Periksa</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=periksa/periksa_pasien">
                    <i class="fas fa-user-md menu-icon"></i>
                    <span class="menu-title">Periksa Pasien</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="riwayat/riwayat_pasien.php">
                    <i class="fas fa-history menu-icon"></i>
                    <span class="menu-title">Riwayat Pasien</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=profile/profile">
                    <i class="fas fa-user menu-icon"></i>
                    <span class="menu-title">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt menu-icon"></i>
                    <span class="menu-title">Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="main-panel">
        <?php
            if (isset($_GET['page'])) {
                // Sanitize the page parameter to prevent directory traversal attacks
                $page = $_GET['page'];
                $page = str_replace("../", "", $page); // Extra security measure
                $page = str_replace("dashboard", "dashboard_welcome", $page); // Special handling for dashboard
                $nama_dokter = $_COOKIE['name']; // Ubah 'name' sesuai dengan nama yang digunakan dalam cookie
                // Get the full path
                $pagePath = __DIR__ . '/' . $page . '.php';

                if ($page == 'dashboard_welcome') {
                    echo "<div class='col-12 grid-margin stretch-card'>
                    <div class='card'>
                        <div class='row'>
                            <div class='col'>
                                <div class='card-body'>
                            
                            <h1>Selamat datang, Dokter $_COOKIE[name] </h1>
                                
                    ";
                } elseif (file_exists($pagePath)) {
                    include($pagePath);
                } else {
                    echo "Page not found.";
                }
            } else {
                echo "<div class='col-12 grid-margin stretch-card'>
                <div class='card'>
                    <div class='row'>
                        <div class='col'>
                            <div class='card-body'>
                        
                        <h1>Selamat datang, Dokter $_COOKIE[name] </h1>
                            
                ";
            }
        ?>
    </div>
</div>



      <!-- <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021.  Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
          <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
        </div>
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a href="https://www.themewagon.com/" target="_blank">Themewagon</a></span> 
        </div>
      </footer> -->
    <!-- </div> -->
  </div>

  <script src="../vendors/js/vendor.bundle.base.js"></script>
  <script src="../js/template.js"></script>
  <script src="../js/dashboard.js"></script>

  <!-- Custom JavaScript to handle cookie data -->
  <script>
  document.addEventListener("DOMContentLoaded", function() {
      // Function to get the value of a cookie
      function getCookie(name) {
          const value = `; ${document.cookie}`;
          const parts = value.split(`; ${name}=`);
          if (parts.length === 2) return parts.pop().split(';').shift();
      }

      // Retrieve cookie data
      const username = getCookie('username');
      const name = getCookie('name');

      // Select the welcome message element
      const welcomeMessage = document.getElementById('welcomeMessage');

      // If cookie data exists, display the welcome message
      if (username && name && welcomeMessage) {
          welcomeMessage.textContent = `<alert class="alert alert-success">Welcome back, ${name} (${username})!</alert>`;
      }
  });
  </script>
</body>
</html>

