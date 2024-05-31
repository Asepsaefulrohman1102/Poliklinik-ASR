<?php
session_start(); // Pastikan ini adalah baris pertama sebelum output apapun

// koneksikan ke database
require('../../koneksi.php');

// Cek apakah user sudah login dan rolenya adalah Dokter
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Dokter') {
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <link rel="stylesheet" href="../../vendors/feather/feather.css">
  <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../../js/select.dataTables.min.css">
  <style>
        .select2-selection__choice {
            background-color: #6959CB !important;
            border: 1px solid #6959CB !important;
            color: #FFFFFF !important;
            font-size: 14px !important;
        }
        .select2-container .select2-selection--multiple {
            min-height: 35px;
            font-size: 14px;
        }
        .select2-container .select2-dropdown {
            font-size: 14px;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            font-size: 14px;
        }
        .select2-container--default .select2-results>.select2-results__options {
            font-size: 14px;
        }
        .select2-container {
            width: 100% !important;
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
                <a class="nav-link" href="?page=riwayat/riwayat_pasien">
                    <i class="fas fa-history menu-icon"></i>
                    <span class="menu-title">Riwayat Pasien</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=profile/profile_dokter">
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
                            <h4 class="card-title">Edit Periksa Pasien</h4>
                            <div class="mt-3" id="periksa-pasien-form">
                            <?php
                                $conn = mysqli_connect("localhost", "root", "", "poliklinik");
                                if (!$conn) {
                                    die("Koneksi gagal: " . mysqli_connect_error());
                                }

                                $id_periksa = $_GET['id_periksa'];
                                $id_poli = $_GET['id_poli'];

                                // Ambil data dari tabel daftar_poli, periksa dan pasien
                                $query = "
                                SELECT dp.*, p.tgl_periksa, p.catatan, ps.nama_pasien 
                                FROM daftar_poli dp 
                                JOIN periksa p ON dp.id_poli = p.id_daftar_poli 
                                JOIN pasien ps ON dp.id_pasien = ps.id
                                WHERE p.id_periksa = '$id_periksa' AND dp.id_poli = '$id_poli'";

                            $result = mysqli_query($conn, $query);
                            if ($result) {
                                $data = mysqli_fetch_assoc($result);
                                $nama_pasien = $data['nama_pasien']; // Menggunakan nama_pasien dari tabel pasien
                                $keluhan = $data['keluhan'];
                                $no_antrian = $data['no_antrian'];
                                $tgl_periksa = $data['tgl_periksa'];
                                $catatan = $data['catatan'];
                            } else {
                                echo "Error: " . mysqli_error($conn);
                            }

                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $id_periksa = $_POST['id_periksa'];
                                $tgl_periksa = $_POST['tgl_periksa'];
                                $catatan = $_POST['catatan'];
                                $obat_ids = $_POST['obat'];

                                // Hitung biaya berdasarkan obat yang dipilih
                                $biaya = 0;
                                foreach ($obat_ids as $id_obat) {
                                    $query = "SELECT harga FROM obat WHERE id_obat = '$id_obat'";
                                    $result = mysqli_query($conn, $query);
                                    if ($row = mysqli_fetch_assoc($result)) {
                                        $biaya += $row['harga'];
                                    }
                                }

                                // Update data ke tabel periksa
                                $query = "UPDATE periksa SET tgl_periksa = '$tgl_periksa', catatan = '$catatan', biaya_periksa = '$biaya' WHERE id_periksa = '$id_periksa'";
                                if (mysqli_query($conn, $query)) {
                                    // Update juga data ke tabel detail_periksa
                                    foreach ($obat_ids as $id_obat) {
                                        $query_detail = "INSERT INTO detail_periksa (id_periksa, id_obat) VALUES ('$id_periksa', '$id_obat')";
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
                                            text: 'Terjadi kesalahan saat menyimpan data: " . mysqli_error($conn) . "',
                                            icon: 'error'
                                        });
                                    </script>";
                                }
                            }

                             // Ambil ID obat yang dipilih untuk periksa tertentu dari tabel detail_periksa
                            $query_obat_terpilih = "SELECT id_obat FROM detail_periksa WHERE id_periksa = '$id_periksa'";
                            $result_obat_terpilih = mysqli_query($conn, $query_obat_terpilih);
                            $obat_terpilih = array();
                            while ($row_obat_terpilih = mysqli_fetch_assoc($result_obat_terpilih)) {
                                $obat_terpilih[] = $row_obat_terpilih['id_obat'];
                            }
                        ?>

                            <form name="myForm" action="" method="POST" onsubmit="return validate()">
                                <input type="hidden" name="id_periksa" value="<?php echo $id_periksa; ?>">

                                <div class="form-group">
                                    <label for="nama_pasien">Nama Pasien</label>
                                    <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" value="<?php echo $nama_pasien; ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <!-- tanggal periksa datetime -->
                                    <label for="tanggal_periksa">Tanggal Periksa</label>
                                    <input type="datetime-local" class="form-control" id="tgl_periksa" name="tgl_periksa" value="<?php echo date('Y-m-d\TH:i', strtotime($tgl_periksa)); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="catatan_periksa">Catatan Periksa</label>
                                    <textarea class="form-control" id="catatan" name="catatan" rows="3" required><?php echo $catatan; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="obat">Obat</label>
                                    <select class="form-control js-example-basic-multiple" multiple="multiple" name="obat[]" id="obat" required>
                                        <?php
                                        $sql = "SELECT * FROM obat";
                                        $result = mysqli_query($conn, $sql);
                                        while ($data = mysqli_fetch_assoc($result)) {
                                            $selected = (in_array($data['id_obat'], $obat_terpilih)) ? 'selected' : ''; // Tandai opsi obat yang telah dipilih
                                            echo "<option class='form-control' value='" . $data['id_obat'] . "' $selected>" . $data['nama_obat'] . ' - ' . $data['kemasan']  . ' - Rp.' . $data['harga'] ."</option>"; 
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


<script>
$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>
</body>
</html>

