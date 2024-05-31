<?php
// Mulai sesi
session_start();

include 'koneksi.php';

// Periksa jika sudah login
if (isset($_SESSION['no_ktp'])) {
    header("location:index.php");
    exit();
}

// Fungsi untuk menghasilkan nomor rekam medis otomatis
function generateNoRekamMedis($conn) {
    static $counter = 0;
    $potential_no_rekam_medis = 'RM' . date('Ym') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
    $query = "SELECT COUNT(*) FROM pasien WHERE no_rekam_medis='$potential_no_rekam_medis'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_fetch_row($result)[0];

    while ($count > 0) {
        $counter++;
        $potential_no_rekam_medis = 'RM' . date('Ym') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
        $query = "SELECT COUNT(*) FROM pasien WHERE no_rekam_medis='$potential_no_rekam_medis'";
        $result = mysqli_query($conn, $query);
        $count = mysqli_fetch_row($result)[0];
    }

    return $potential_no_rekam_medis;
}

$no_rekam_medis = generateNoRekamMedis($conn); // Panggil fungsi generate nomor rekam medis otomatis


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Register - Permata Poli</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../assets/assets_login/img/favicon.png" rel="icon">
    <link href="../assets/assets_login/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/assets_login/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/assets_login/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/assets_login/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/assets_login/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/assets_login/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/assets_login/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/assets_login/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../assets/assets_login/css/style.css" rel="stylesheet">

    <!-- Include SweetAlert2 JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

<main>
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-center py-4">
                            <a href="index.html" class="logo d-flex align-items-center w-auto">
                                <img src="../../images/logo-1.png" alt="">
                                <span class="d-none d-lg-block">Permata Poli</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Daftar Akun Baru Pasien</h5>
                                </div>

                                <?php
                                // Melakukan daftar akun
                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    $nama = mysqli_real_escape_string($conn, $_POST['namaLengkap']);
                                    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
                                    $no_ktp = mysqli_real_escape_string($conn, $_POST['noKtp']);
                                    $no_hp = mysqli_real_escape_string($conn, $_POST['noHp']);

                                    $query = "INSERT INTO pasien (nama_pasien, alamat, no_ktp, no_hp, no_rekam_medis) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rekam_medis')";
                                    if (mysqli_query($conn, $query)) {
                                        echo "<script>
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil',
                                                    text: 'Berhasil Daftar Akun Pasien',
                                                    showConfirmButton: true
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = 'login.php';
                                                    }
                                                });
                                            </script>";
                                    } else {
                                        echo "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Gagal',
                                                    text: 'Gagal Daftar Akun Pasien',
                                                    showConfirmButton: true
                                                });
                                            </script>";
                                    }
                                }
                                ?>

                                <form class="row g-3 needs-validation" action="" method="POST">
                                    <div class="form-group">
                                        <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="namaLengkap" name="namaLengkap" required>
                                        <div class="invalid-feedback">
                                            Tolong masukkan nama lengkap
                                        </div>
                                    </div>

                                    <!-- Alamat -->
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                                        <div class="invalid-feedback">
                                            Tolong masukkan alamat
                                        </div>
                                    </div>

                                    <!-- No.Ktp -->
                                    <div class="form-group">
                                        <label for="noKtp" class="form-label">No. KTP</label>
                                        <input type="text" class="form-control" id="noKtp" name="noKtp" required>
                                        <div class="invalid-feedback">
                                            Tolong masukkan No. KTP
                                        </div>
                                    </div>

                                    <!-- No.Hp -->
                                    <div class="form-group">
                                        <label for="noHp" class="form-label">No. HP</label>
                                        <input type="text" class="form-control" id="noHp" name="noHp" required>
                                        <div class="invalid-feedback">
                                            Tolong masukkan No. HP
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit" name="submit">Daftar Akun</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">Sudah punya akun? <a href="login.php">Login</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main><!-- End #main -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="../assets/assets_login/vendor/apexcharts/apexcharts.min.js"></script>
<script src="../assets/assets_login/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/assets_login/vendor/chart.js/chart.umd.js"></script>
<script src="../assets/assets_login/vendor/echarts/echarts.min.js"></script>
<script src="../assets/assets_login/vendor/quill/quill.min.js"></script>
<script src="../assets/assets_login/vendor/simple-datatables/simple-datatables.js"></script>
<script src="../assets/assets_login/vendor/tinymce/tinymce.min.js"></script>
<script src="../assets/assets_login/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="../assets/assets_login/js/main.js"></script>

<script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
</body>

</html>
