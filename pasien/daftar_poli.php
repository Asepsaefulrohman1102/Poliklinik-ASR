<?php
session_start();
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "poliklinik");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek apakah user sudah login dan
if (!isset($_SESSION['loggedin']) ) {
    header('Location: login.php');
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
        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <?php
                    $no_rekam_medis = $_SESSION['no_rekam_medis'] ?? '';

                                
                    function getNewQueueNumber($conn, $id_jadwal) {
                        // Gunakan transaksi untuk memastikan keamanan
                        mysqli_begin_transaction($conn);
                        
                        try {
                            // Ambil nomor antrian terakhir untuk jadwal poli tertentu
                            $query_last_queue_number = "SELECT MAX(no_antrian) as no_antrian_terakhir FROM daftar_poli WHERE id_jadwal = $id_jadwal FOR UPDATE";
                            $result_last_queue_number = mysqli_query($conn, $query_last_queue_number);
                            
                            if (!$result_last_queue_number) {
                                throw new Exception("Kueri gagal: " . mysqli_error($conn));
                            }
                            
                            $row = mysqli_fetch_assoc($result_last_queue_number);
                            $last_queue_number = $row['no_antrian_terakhir'];
                            
                            // Jika tidak ada nomor antrian sebelumnya, nomor antrian baru dimulai dari 1
                            $new_queue_number = ($last_queue_number !== null) ? $last_queue_number + 1 : 1;
                    
                            // Lakukan penyisipan data baru
                            $query_insert_data = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian) 
                                                  VALUES (?, ?, ?, ?)";
                            $stmt = mysqli_prepare($conn, $query_insert_data);
                            mysqli_stmt_bind_param($stmt, "iisi", $_SESSION['id'], $id_jadwal, $_POST['keluhan'], $new_queue_number);
                            
                            if (!mysqli_stmt_execute($stmt)) {
                                throw new Exception("Error: " . mysqli_error($conn));
                            }
                            
                            // Commit transaksi jika semua operasi berhasil
                            mysqli_commit($conn);
                    
                            return $new_queue_number;
                        } catch (Exception $e) {
                            // Rollback transaksi jika ada kesalahan
                            mysqli_rollback($conn);
                            die("Error: " . $e->getMessage());
                        }
                    }
                    
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $id_jadwal = $_POST['jadwal_periksa'];
                        $new_queue_number = getNewQueueNumber($conn, $id_jadwal);
                    
                        echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Pendaftaran berhasil dengan nomor antrian $new_queue_number.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '../../';
                                    }
                                });
                            });
                            </script>";
                    }
                    


                ?>
                <div class="card-body">
                <p class="card-title text-center pb-2 fs-4">Daftar Poli</p>
                    <form class="row g-3 needs-validation" method="POST" action="" novalidate>
                        <div class="col-12 mb-3">
                            <label for="no_rekam_medis" class="form-label">No Rekam Medis</label>
                            <div class="input-group has-validation">
                                <input type="text" class="form-control" id="no_rekam_medis" name="no_rekam_medis" value="<?php echo htmlspecialchars($no_rekam_medis); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="poli" class="form-label">Poli</label>
                            <select class="form-control" id="poli" name="poli" required>
                                <option value="" selected>Pilih Poli</option>
                                <?php
                                $conn = mysqli_connect("localhost", "root", "", "poliklinik");
                                if (!$conn) {
                                    die("Koneksi gagal: " . mysqli_connect_error());
                                }
                                $query = mysqli_query($conn, "SELECT * FROM poli");
                                while ($data = mysqli_fetch_array($query)) {
                                    echo '<option value="' . htmlspecialchars($data['id']) . '">' . htmlspecialchars($data['nama_poli']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="jadwal_periksa" class="form-label">Pilih Jadwal</label>
                            <select class="form-control" id="jadwal_periksa" name="jadwal_periksa" required>
                                <option value="" selected>Pilih Jadwal</option>
                                <!-- Options will be filled by JavaScript -->
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="keluhan" class="form-label">Keluhan</label>
                            <textarea class="form-control" id="keluhan" name="keluhan" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <button class="btn btn-primary w-100" type="submit">Daftar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body" id="tabelDaftarPoli">
                    <p class="card-title mb-0">Riwayat Daftar Poli</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                            <th>No</th>
                            <th>Nama Poli</th>
                            <th>Nama Dokter</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>No Antrian</th>
                            <th>Aksi</th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php
                            $conn = mysqli_connect("localhost", "root", "", "poliklinik");
                            if (!$conn) {
                                die("Koneksi gagal: " . mysqli_connect_error());
                            }
                            // Memastikan bahwa ID pasien ada dalam session
                            if (isset($_SESSION['id'])) {
                            $id_pasien = $_SESSION['id'];

                            $query = mysqli_query($conn, "SELECT daftar_poli.no_antrian, poli.nama_poli, jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, dokter.nama_dokter, poli.id AS id_poli
                                FROM daftar_poli
                                JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
                                JOIN dokter ON jadwal_periksa.id_dokter = dokter.id_dokter
                                JOIN poli ON dokter.id_poli = poli.id
                                WHERE daftar_poli.id_pasien = '$id_pasien'");


                            if (!$query) {
                                die("Query gagal: " . mysqli_error($conn));
                            }

                            $no = 1;
                            while ($data = mysqli_fetch_array($query)) {
                                echo '<tr>';
                                echo '<td>' . $no++ . '</td>';
                                echo '<td>' . htmlspecialchars($data['nama_poli']) . '</td>';
                                echo '<td>' . htmlspecialchars($data['nama_dokter']) . '</td>';
                                echo '<td>' . htmlspecialchars($data['hari']) . '</td>';
                                echo '<td>' . htmlspecialchars($data['jam_mulai']) . '</td>';
                                echo '<td>' . htmlspecialchars($data['jam_selesai']) . '</td>';
                                echo '<td>' . htmlspecialchars($data['no_antrian']) . '</td>';
                                // DETAIL
                                echo '<td><a href="detail_riwayat.php?id_poli=' . htmlspecialchars($data['id_poli']) . '&no_antrian=' . htmlspecialchars($data['no_antrian']) . '&nama_poli=' . htmlspecialchars($data['nama_poli']) . '&nama_dokter=' . htmlspecialchars($data['nama_dokter']) . '&hari=' . htmlspecialchars($data['hari']) . '&jam_mulai=' . htmlspecialchars($data['jam_mulai']) . '&jam_selesai=' . htmlspecialchars($data['jam_selesai']) . '" class="btn btn-primary">Detail</a></td>';
                            }
                        } else {
                            echo '<tr><td colspan="8">ID pasien tidak ditemukan dalam session.</td></tr>';
                        }
                            ?>
                        </tbody>
                        </table>
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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<script src="../assets/assets_login/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/assets_login/vendor/simple-datatables/simple-datatables.js"></script>
<script src="../assets/assets_login/js/main.js"></script>
<script>
$(document).ready(function() {
    $('#poli').change(function() {
        var poli_id = $(this).val();
        if (poli_id) {
            $.ajax({
                type: 'POST',
                url: 'lihat_jadwal.php',
                data: { poli_id: poli_id },
                success: function(response) {
                    console.log("Response from server: ", response);
                    var jadwal = JSON.parse(response);
                    console.log("Parsed schedule: ", jadwal);
                    $('#jadwal_periksa').empty();
                    $('#jadwal_periksa').append('<option value="" selected>Pilih Jadwal</option>');
                    $.each(jadwal, function(index, value) {
                        $('#jadwal_periksa').append('<option value="' + value.id + '">' + value.nama_dokter + ' | ' + value.hari + ' | ' + value.jam_mulai + '</option>');
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("AJAX error: ", textStatus, " : ", errorThrown);
                }
            });
        } else {
            $('#jadwal_periksa').empty();
            $('#jadwal_periksa').append('<option value="" selected>Pilih Jadwal</option>');
        }
    });
});
</script>

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