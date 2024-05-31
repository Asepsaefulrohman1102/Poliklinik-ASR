
<?php
session_start();
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "poliklinik");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek apakah user sudah login
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

// Cek apakah parameter ID poli tersedia
if (!isset($_GET['id_poli'])) {
    // Jika tidak ada parameter ID poli, redirect ke halaman daftar poli
    header('Location: daftar_poli.php');
    exit();
}

$id_poli = $_GET['id_poli'];
$nama_dokter = $_GET['nama_dokter'];
$nama_poli = $_GET['nama_poli'];
$no_antrian = $_GET['no_antrian'];
$hari = $_GET['hari'];
$jam_mulai = $_GET['jam_mulai'];
$jam_selesai = $_GET['jam_selesai'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Detail Riwayat Poli</title>
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
                  <a class="nav-link" href="index.php">
                      <i class="fas fa-tachometer-alt menu-icon"></i>
                      <span class="menu-title">Dashboard</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="daftar_poli.php">
                      <i class="fas fa-calendar-alt menu-icon"></i>
                      <span class="menu-title">Daftar Poli</span>
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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body" id="tabelDetailPoli">
                    <h2 class="card-title text-center mb-4">Detail Riwayat Poli</h2>
                    <form>
                        <div class="mb-3">
                            <label for="no_antrian" class="form-label">No Antrian</label>
                            <input type="text" class="form-control" id="no_antrian" value="<?php echo $no_antrian; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama_poli" class="form-label">Nama Poli</label>
                            <input type="text" class="form-control" id="nama_poli" value="<?php echo $nama_poli; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="nama_dokter" class="form-label">Nama Dokter</label>
                            <input type="text" class="form-control" id="nama_dokter" value="<?php echo $nama_dokter; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="hari" class="form-label">Hari</label>
                            <input type="text" class="form-control" id="hari" value="<?php echo $hari; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="text" class="form-control" id="jam_mulai" value="<?php echo $jam_mulai; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="text" class="form-control" id="jam_selesai" value="<?php echo $jam_selesai; ?>" readonly>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


      <script src="../vendors/js/vendor.bundle.base.js"></script>
      <script src="../js/template.js"></script>
      <script src="../js/dashboard.js"></script>

      <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
      <script src="../assets/assets_login/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="../assets/assets_login/vendor/simple-datatables/simple-datatables.js"></script>
      <script src="../assets/assets_login/js/main.js"></script>
  </body>
</html>
``
