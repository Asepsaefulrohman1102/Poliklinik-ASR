<?php
session_start(); // Pastikan ini adalah baris pertama sebelum output apapun

// koneksikan ke database
require('../../koneksi.php');

// Cek apakah user sudah login dan role-nya adalah Dokter
if (!isset($_SESSION['loggedin']) ) {
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
  <link rel="stylesheet" href="../../assets/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../../images/logo-1.png" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- Include SweetAlert2 JavaScript -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- Include Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  
  <!-- Include jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
  <!-- Include Select2 JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <style>
        .select2-selection__choice {
            background-color: #6959CB !important; /* Ganti warna latar belakang */
            border: 1px solid #6959CB !important; /* Ganti warna border */
            color: #FFFFFF !important; /* Ganti warna teks */
            font-size: 14px !important; /* Ganti ukuran font */
        }

        /* Custom CSS for Select2 container */
        .select2-container .select2-selection--multiple {
            min-height: 35px; /* Ganti tinggi minimum */
            font-size: 14px; /* Ganti ukuran font */
        }

        /* Custom CSS for Select2 dropdown */
        .select2-container .select2-dropdown {
            font-size: 14px; /* Ganti ukuran font */
        }

        /* Custom CSS for Select2 search input */
        .select2-container--default .select2-search--dropdown .select2-search__field {
            font-size: 14px; /* Ganti ukuran font */
        }

        /* Custom CSS for Select2 single option */
        .select2-container--default .select2-results>.select2-results__options {
            font-size: 14px; /* Ganti ukuran font */
        }

        /* Custom CSS for Select2 overall width */
        .select2-container {
            width: 100% !important; /* Atur lebar menjadi 100% */
        }
    </style>
</head>

<body>
  <div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="../../images/logo-1.png" class="mr-2" alt="logo"/>Permata</a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../../images/logo-1.png" alt="logo"/></a>
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
                <a class="nav-link" href="../dashboard.php">
                    <i class="fas fa-tachometer-alt menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../dashboard.php?page=jadwal/jadwal_periksa">
                    <i class="fas fa-calendar-alt menu-icon"></i>
                    <span class="menu-title">Jadwal Periksa</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../dashboard.php?page=periksa/periksa_pasien">
                    <i class="fas fa-user-md menu-icon"></i>
                    <span class="menu-title">Periksa Pasien</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../riwayat/riwayat_pasien.php">
                    <i class="fas fa-history menu-icon"></i>
                    <span class="menu-title">Riwayat Pasien</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../dashboard.php?page=profile/profile">
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
  
 
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="row">
            <div class="col">
              <div class="card-body">
                <h4 class="card-title">Periksa Pasien</h4>

                <?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "poliklinik");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$id_poli = $_GET['id_poli'];
$nama_pasien = $_GET['nama_pasien'];
$keluhan = $_GET['keluhan'];
$no_antrian = $_GET['no_antrian'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_poli = $_POST['id_poli'];
    $tgl_periksa = $_POST['tanggal_periksa'];
    $catatan = $_POST['catatan'];
    $obat_ids = $_POST['obat'];
    
    // Biaya jasa dokter
    $biaya_jasa_dokter = 150000;

    // Hitung biaya berdasarkan obat yang dipilih
    $biaya_obat = 0;
    foreach ($obat_ids as $id_obat) {
        $query = "SELECT harga FROM obat WHERE id_obat = '$id_obat'";
        $result = mysqli_query($conn, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            $biaya_obat += $row['harga'];
        }
    }

    // Total biaya periksa
    $total_biaya = $biaya_jasa_dokter + $biaya_obat;

    // Masukkan data ke tabel periksa
    $query = "INSERT INTO periksa (id_daftar_poli, tgl_periksa, catatan, biaya_periksa) VALUES ('$id_poli', '$tgl_periksa', '$catatan', '$total_biaya')";
    if (mysqli_query($conn, $query)) {
        // Ambil id_periksa yang baru saja dimasukkan
        $id_periksa_baru = mysqli_insert_id($conn);

        // Masukkan data ke tabel detail_periksa untuk setiap obat yang dipilih
        foreach ($obat_ids as $id_obat) {
            $query_detail = "INSERT INTO detail_periksa (id_periksa, id_obat) VALUES ('$id_periksa_baru', '$id_obat')";
            mysqli_query($conn, $query_detail);
        }

        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Data berhasil disimpan!',
                icon: 'success'
            }).then(function() {
                window.location = '../dashboard.php?page=periksa/periksa_pasien';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan data.',
                icon: 'error'
            });
        </script>";
    }
}

?>

<div class="mt-3" id="periksa-pasien-form">
    <form name="myForm" action="" method="POST" onsubmit="return validate()">
        <input type="hidden" name="id_poli" value="<?php echo $id_poli; ?>">

        <div class="form-group">
            <label for="nama_pasien">Nama Pasien</label>
            <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" value="<?php echo $nama_pasien; ?>" readonly>
        </div>

        <div class="form-group">
            <label for="tanggal_periksa">Tanggal Periksa</label>
            <input type="datetime-local" class="form-control" id="tanggal_periksa" name="tanggal_periksa" required>
        </div>

        <div class="form-group">
            <label for="catatan_periksa">Catatan Periksa</label>
            <textarea class="form-control" id="catatan" name="catatan" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="obat">Obat</label>
            <select class="form-control js-example-basic-multiple" multiple="multiple" name="obat[]" id="obat" required>
                <?php
                $sql = "SELECT * FROM obat";
                $result = mysqli_query($conn, $sql);
                while ($data = mysqli_fetch_assoc($result)) {
                    echo "<option class='form-control' value='" . $data['id_obat'] . "'>" . $data['nama_obat'] . ' - ' . $data['kemasan'] . ' - Rp.' . $data['harga'] . "</option>"; 
                }
                ?>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>


  </div>
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

  <script>
    // Inisialisasi Select2
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });
  </script>
</body>
</html>
