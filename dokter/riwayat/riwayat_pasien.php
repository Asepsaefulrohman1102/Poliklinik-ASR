<?php
session_start(); // Pastikan ini adalah baris pertama sebelum output apapun

// koneksikan ke database
require('../../koneksi.php');

// Cek apakah user sudah login dan rolenya adalah Dokter
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
                <a class="nav-link" href="../../dokter/riwayat/riwayat_pasien.php">
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
                <a class="nav-link" href="../logout.php">
                    <i class="fas fa-sign-out-alt menu-icon"></i>
                    <span class="menu-title">Logout</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="main-panel">
        <div class="container">
            <div class="col">
                <div class="card-body">
                    <h4 class="card-title">Riwayat Pasien</h4>
                    <p class="card-description">Daftar riwayat pasien yang terdaftar</p>

                    <?php

                    // Koneksi ke database
                    $conn = mysqli_connect("localhost", "root", "", "poliklinik");
                    if (!$conn) {
                        die("Koneksi gagal: " . mysqli_connect_error());
                    }

                    // Ambil nama dokter dari cookie
                    if (isset($_COOKIE['name'])) {
                        $nama_dokter = $_COOKIE['name'];
                    } else {
                        die("Cookie 'name' tidak ditemukan.");
                    }

                    // Query untuk mendapatkan ID dokter berdasarkan nama dokter
                    $query_id_dokter = "SELECT id_dokter FROM dokter WHERE nama_dokter = '$nama_dokter'";
                    $result_id_dokter = mysqli_query($conn, $query_id_dokter);

                    if ($result_id_dokter) {
                        $row_id_dokter = mysqli_fetch_assoc($result_id_dokter);
                        $id_dokter = $row_id_dokter['id_dokter'];
                    } else {
                        die("Error: " . mysqli_error($conn));
                    }

                    // Query untuk menampilkan data pasien yang sudah pernah diperiksa berdasarkan dokter yang melakukan pemeriksaan
                    $query = "SELECT DISTINCT pasien.id, pasien.nama_pasien, pasien.alamat, pasien.no_ktp, pasien.no_hp, pasien.no_rekam_medis
                                FROM periksa
                                INNER JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id_poli
                                INNER JOIN pasien ON daftar_poli.id_pasien = pasien.id
                                INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
                                INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id_dokter
                                WHERE periksa.id_periksa IS NOT NULL
                                AND dokter.id_dokter = $id_dokter
                                GROUP BY pasien.id";

                    $result = mysqli_query($conn, $query);
                    ?>

                    <div class="table-responsive mt-3">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Alamat</th>
                                    <th>No. KTP</th>
                                    <th>No. Telepon</th>
                                    <th>No. RM</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($data = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $data['nama_pasien']; ?></td>
                                        <td><?php echo $data['alamat']; ?></td>
                                        <td><?php echo $data['no_ktp']; ?></td>
                                        <td><?php echo $data['no_hp']; ?></td>
                                        <td><?php echo $data['no_rekam_medis']; ?></td>
                                        <td>
                                            <button class="btn btn-primary detail-btn"
                                                    data-id="<?php echo $data['id']; ?>"
                                                    data-toggle="modal"
                                                    data-target="#detailModal">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal for displaying details -->
                    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel">Detail Riwayat Pasien</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table" id="detailTable">
                                        <!-- Isi tabel detail akan dimuat secara dinamis melalui AJAX -->
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        $('.detail-btn').click(function() {
            var idPasien = $(this).data('id');
            $.ajax({
                url: 'detail.php?action=riwayat_pasien&id_pasien=' + idPasien, // Ubah sesuai dengan file ini, bukan riwayat_pasien.php
                type: 'GET',
                success: function(response) {
                    $('#detailModal .modal-body').html(response); // Isi modal-body dengan response dari AJAX
                    $('#detailModal').modal('show'); // Tampilkan modal
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', status, error);
                    $('#detailModal .modal-body').html('<p>Error mengambil data.</p>'); // Tampilkan pesan error jika terjadi kesalahan
                    $('#detailModal').modal('show'); // Tampilkan modal
                }
            });
        });
    });
</script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>