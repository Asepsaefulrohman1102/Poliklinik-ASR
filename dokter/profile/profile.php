

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Permata Poli - Dokter</title>
  <link rel="stylesheet" href="../../vendors/feather/feather.css">
  <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../../js/select.dataTables.min.css">
  <link rel="stylesheet" href="../../assets/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../../images/logo-1.png" />

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
<body>

<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="row">
            <div class="col">
                <h3 class="card-header">Profile Dokter</h3>

                <div class="card-body">

                <?php

                    // Check if user is logged in
                    if (!isset($_SESSION['loggedin'])) {
                        header('Location: ../../index.php');
                        exit();
                    }

                    // Connect to database
                    $conn = mysqli_connect("localhost", "root", "", "poliklinik");
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Initialize variables
                    $id_dokter = '';
                    $nama_dokter = '';
                    $alamat = '';
                    $no_telp = '';

                    // Fetch doctor data based on cookie or `id_dokter` in URL
                    if (isset($_GET['id_dokter'])) {
                        $id_dokter = mysqli_real_escape_string($conn, $_GET['id_dokter']);
                        $result = mysqli_query($conn, "SELECT * FROM dokter WHERE id_dokter='$id_dokter'");
                        if ($data = mysqli_fetch_array($result)) {
                            $nama_dokter = $data['nama_dokter'];
                            $alamat = $data['alamat'];
                            $no_telp = $data['no_hp'];
                        }
                    } elseif (isset($_COOKIE['name'])) {
                        $nama_dokter_cookie = mysqli_real_escape_string($conn, $_COOKIE['name']);
                        $result = mysqli_query($conn, "SELECT * FROM dokter WHERE nama_dokter='$nama_dokter_cookie'");
                        if ($data = mysqli_fetch_array($result)) {
                            $id_dokter = $data['id_dokter'];
                            $nama_dokter = $data['nama_dokter'];
                            $alamat = $data['alamat'];
                            $no_telp = $data['no_hp'];
                        }
                    }

                    // Handle form submission
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $id_dokter = mysqli_real_escape_string($conn, $_POST['id_dokter']);
                        $nama_dokter = mysqli_real_escape_string($conn, $_POST['nama_dokter']);
                        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
                        $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

                        if (!empty($id_dokter)) {
                            $query = "UPDATE dokter SET nama_dokter='$nama_dokter', alamat='$alamat', no_hp='$no_telp' WHERE id_dokter='$id_dokter'";
                            if (mysqli_query($conn, $query)) {
                                echo "<script>
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: 'Berhasil mengubah data dokter',
                                            showConfirmButton: true
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.cookie = 'name=' + encodeURIComponent('$nama_dokter') + '; path=/'; // Update cookie with new doctor name
                                                window.location.href = 'dashboard.php?page=profile/profile';
                                            }
                                        });
                                    </script>";
                            } else {
                                echo "<script>
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: 'Gagal mengubah data dokter',
                                            showConfirmButton: true
                                        });
                                    </script>";
                            }
                        }
                    }
                    ?>


                    <h4 class="card-header bg-primary text-white">Edit Profile Dokter</h4>
                    <form name="myForm" action="" method="post" onsubmit="return validate()">
                        <div class="form-group">
                            <input type="hidden" id="id_dokter" name="id_dokter" value="<?php echo $id_dokter; ?>">
                        </div>
                        <div class="form-group">
                            <label for="nama_dokter">Nama Dokter</label>
                            <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" value="<?php echo $nama_dokter; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="no_telp">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" value="<?php echo $no_telp; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../vendors/js/vendor.bundle.base.js"></script>
<script src="../../vendors/chart.js/Chart.min.js"></script>
<script src="../../vendors/datatables.net/jquery.dataTables.js"></script>
<script src="../../vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="../../js/dataTables.select.min.js"></script>
<script src="../../js/off-canvas.js"></script>
<script src="../../js/hoverable-collapse.js"></script>
<script src="../../js/template.js"></script>
<script src="../../js/settings.js"></script>
<script src="../../js/todolist.js"></script>
<script src="../../js/dashboard.js"></script>
<script src="../../js/Chart.roundedBarCharts.js"></script>
<script src="../../js/custom.js"></script>

<script>
// Custom JavaScript to handle cookie data
document.addEventListener("DOMContentLoaded", function() {
    // Function to get the value of a specific cookie
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift()); // Decode the cookie value
    }

    const nama_dokter = getCookie('name');
    if (nama_dokter) {
        document.getElementById('nama_dokter').value = nama_dokter;
    }
});
</script>

</body>
</html>
