<?php
session_start(); // Pastikan ini adalah baris pertama sebelum output apapun
include_once('koneksi.php');

// Cek apakah user sudah login dan role-nya adalah Admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Admin') {
    header('Location: ../');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Permata Poli - Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../vendors/feather/feather.css">
  <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../js/select.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/logo-1.png" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
        .nav-item.active .nav-link {
            background-color: #f0f0f0; /* Warna latar belakang untuk item aktif */
            color: #333; /* Warna teks untuk item aktif */
        }
    </style>
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
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
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <?php
            // Mendapatkan halaman saat ini dari parameter 'page'
            $currentPage = isset($_GET['page']) ? basename($_GET['page']) : 'dashboard';

            // Fungsi untuk menentukan kelas 'active'
            function isActive($page, $currentPage) {
                return $page == $currentPage ? 'active' : '';
            }
            ?>
            <li class="nav-item <?php echo isActive('dashboard', $currentPage); ?>">
                <a class="nav-link" href="dashboard">
                    <i class="fas fa-tachometer-alt menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item <?php echo isActive('pages/admin/dokter', $currentPage); ?>">
                <a class="nav-link" href="?page=pages/admin/dokter">
                    <i class="fas fa-user-md menu-icon"></i>
                    <span class="menu-title">Dokter</span>
                </a>
            </li>
            <li class="nav-item <?php echo isActive('pages/admin/pasien', $currentPage); ?>">
                <a class="nav-link" href="?page=pages/admin/pasien">
                    <i class="fas fa-procedures menu-icon"></i>
                    <span class="menu-title">Pasien</span>
                </a>
            </li>
            <li class="nav-item <?php echo isActive('pages/admin/poli', $currentPage); ?>">
                <a class="nav-link" href="?page=pages/admin/poli">
                    <i class="fas fa-hospital menu-icon"></i>
                    <span class="menu-title">Poli</span>
                </a>
            </li>
            <li class="nav-item <?php echo isActive('pages/admin/obat', $currentPage); ?>">
                <a class="nav-link" href="?page=pages/admin/obat">
                    <i class="fas fa-pills menu-icon"></i>
                    <span class="menu-title">Obat</span>
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
            $page = basename($_GET['page']);
            $pageTitle = ucwords(str_replace("-", " ", $page));
            $pagePath = "./$page.php";

            if (file_exists($pagePath)) {
                include($pagePath);
            } else {
                echo "Page not found.";
            }
        } else {
            echo "Selamat Datang di Sistem Informasi Poliklinik Anda Login Sebagai Admin";
        }
        ?>

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <!--   -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>   
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="../vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../vendors/chart.js/Chart.min.js"></script>
  <script src="../vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="../vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="../js/dataTables.select.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../js/off-canvas.js"></script>
  <script src="../js/hoverable-collapse.js"></script>
  <script src="../js/template.js"></script>
  <script src="../js/settings.js"></script>
  <script src="../js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="../js/dashboard.js"></script>
  <script src="../js/Chart.roundedBarCharts.js"></script>
  <!-- End custom js for this page-->
</body>

</html>

