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

// Fungsi untuk mendapatkan nomor antrian baru
function getNewQueueNumber($conn) {
    $query_last_queue_number = "SELECT MAX(no_antrian) as no_antrian_terakhir FROM daftar_poli";
    $result_last_queue_number = mysqli_query($conn, $query_last_queue_number);
    if (!$result_last_queue_number) {
        die("Kueri gagal: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result_last_queue_number);
    $last_queue_number = $row['no_antrian_terakhir'];
    $new_queue_number = ($last_queue_number !== null) ? $last_queue_number + 1 : 1;
    return $new_queue_number;
}

$new_queue_number = getNewQueueNumber($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pasien = $_SESSION['id'];
    $id_jadwal = $_POST['jadwal_periksa'];
    $keluhan = $_POST['keluhan'];
    $no_antrian = $new_queue_number;

    $query_insert_data = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian) 
                          VALUES ('$id_pasien', '$id_jadwal', '$keluhan', '$no_antrian')";

    $result_insert_data = mysqli_query($conn, $query_insert_data);

    if ($result_insert_data) {
        // Hancurkan sesi setelah pendaftaran berhasil
        session_destroy();
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pendaftaran berhasil.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../../';
                }
            });
        });
        </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Pages / Login - Permata Poli Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="../assets/assets_login/img/favicon.png" rel="icon">
  <link href="../assets/assets_login/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="../assets/assets_login/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/assets_login/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/assets_login/css/style.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <?php
                                $no_rekam_medis = $_SESSION['no_rekam_medis'] ?? '';
                                ?>
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Daftar Poli</h5>
                                </div>
                                <form class="row g-3 needs-validation" method="POST" action="" novalidate>
                                    <div class="col-12">
                                        <label for="no_rekam_medis" class="form-label">No Rekam Medis</label>
                                        <div class="input-group has-validation">
                                            <input type="text" class="form-control" id="no_rekam_medis" name="no_rekam_medis" value="<?php echo $no_rekam_medis; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="poli">Poli</label>
                                        <select class="form-control" id="poli" name="poli" required>
                                            <option value="" selected>Pilih Poli</option>
                                            <?php
                                            $conn = mysqli_connect("localhost", "root", "", "poliklinik");
                                            if (!$conn) {
                                                die("Koneksi gagal: " . mysqli_connect_error());
                                            }
                                            $query = mysqli_query($conn, "SELECT * FROM poli");
                                            while ($data = mysqli_fetch_array($query)) {
                                            ?>
                                                <option value="<?php echo $data['id']; ?>"><?php echo $data['nama_poli']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="jadwal_periksa">Pilih Jadwal</label>
                                        <select class="form-control" id="jadwal_periksa" name="jadwal_periksa" required>
                                            <option value="" selected>Pilih Jadwal</option>
                                            <!-- Options akan diisi oleh JavaScript -->
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="keluhan" class="form-label">Keluhan</label>
                                        <textarea class="form-control" id="keluhan" name="keluhan" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="no_antrian" class="form-label">No Antrian</label>
                                        <input type="text" class="form-control" id="no_antrian" name="no_antrian" value="<?php echo $new_queue_number; ?>" readonly>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Daftar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="credits">
                            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
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
</body>
</html>
