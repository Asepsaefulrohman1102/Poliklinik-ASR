<?php
session_start();

// Pastikan ini adalah baris pertama sebelum output apapun
require('../../koneksi.php');

// Cek apakah user sudah login
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../../');
    exit();
}


?>
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
                            <?php
                            // Koneksi ke database
                            $conn = mysqli_connect("localhost", "root", "", "poliklinik");
                            if (!$conn) {
                                die("Koneksi gagal: " . mysqli_connect_error());
                            }

                            // Cek apakah tanggal terakhir kali edit sudah disimpan dalam sesi atau cookie
                            if (isset($_SESSION['last_edit_date'])) {
                                // Jika tanggal hari ini sama dengan tanggal terakhir kali edit, tolak edit
                                if ($_SESSION['last_edit_date'] === date('Y-m-d')) {
                                    // Tampilkan pesan atau lakukan aksi yang sesuai, misalnya:
                                    echo "<script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Gagal',
                                                text: 'Anda hanya dapat mengedit sekali dalam sehari',
                                                showConfirmButton: true
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href = '../dashboard.php?page=jadwal/jadwal_periksa';
                                                }
                                            });
                                        </script>";
                                    exit(); // Hentikan proses edit
                                }
                            }

                            $id_dokter = '';
                            $hari = '';
                            $jam_mulai = '';
                            $jam_selesai = '';
                            $id_jadwal = '';

                            // Handle form submission
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $id_dokter = mysqli_real_escape_string($conn, $_POST['id_dokter']);
                                $hari = mysqli_real_escape_string($conn, $_POST['hari']);
                                $jam_mulai = mysqli_real_escape_string($conn, $_POST['jam_mulai']);
                                $jam_selesai = mysqli_real_escape_string($conn, $_POST['jam_selesai']);
                                $id_jadwal = mysqli_real_escape_string($conn, $_POST['id_jadwal']);

                                if (!empty($id_jadwal)) {
                                    $query = "UPDATE jadwal_periksa SET id_dokter='$id_dokter', hari='$hari', jam_mulai='$jam_mulai', jam_selesai='$jam_selesai' WHERE id='$id_jadwal'";
                                    if (mysqli_query($conn, $query)) {
                                        echo "<script>
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil',
                                                    text: 'Berhasil mengubah data jadwal_periksa',
                                                    showConfirmButton: true
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = '../dashboard.php?page=jadwal/jadwal_periksa';
                                                    }
                                                });
                                            </script>";
                                    } else {
                                        echo "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Gagal',
                                                    text: 'Gagal mengubah data jadwal_periksa',
                                                    showConfirmButton: true
                                                });
                                            </script>";
                                    }
                                    
                                    // Simpan tanggal terakhir kali edit
                                    $_SESSION['last_edit_date'] = date('Y-m-d');
                                } else {
                                    $query = "INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai) VALUES ('$id_dokter', '$hari', '$jam_mulai', '$jam_selesai')";
                                    if (mysqli_query($conn, $query)) {
                                        echo "<script>
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil',
                                                    text: 'Berhasil menambahkan data jadwal_periksa',
                                                    showConfirmButton: true
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = '../dashboard.php?page=jadwal/jadwal_periksa';
                                                    }
                                                });
                                            </script>";
                                    } else {
                                        echo "<script>
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Gagal',
                                                    text: 'Gagal menambahkan data jadwal_periksa',
                                                    showConfirmButton: true
                                                });
                                            </script>";
                                    }
                                }
                            } elseif (isset($_GET['id'])) {
                                $id_jadwal = mysqli_real_escape_string($conn, $_GET['id']);
                                $result = mysqli_query($conn, "SELECT * FROM jadwal_periksa WHERE id='$id_jadwal'");
                                if ($data = mysqli_fetch_array($result)) {
                                    $id_dokter = $data['id_dokter'];
                                    $hari = $data['hari'];
                                    $jam_mulai = $data['jam_mulai'];
                                    $jam_selesai = $data['jam_selesai'];
                                    $id_jadwal = $data['id'];
                                }
                            }

                            // delete data
                            // if (isset($_GET['id_hapus'])) {
                            //     $id_jadwal = mysqli_real_escape_string($conn, $_GET['id_hapus']);
                            //     $query = "DELETE FROM jadwal_periksa WHERE id='$id_jadwal'";
                            //     if (mysqli_query($conn, $query)) {
                            //         echo "<script>
                            //                 Swal.fire({
                            //                     icon: 'success',
                            //                     title: 'Berhasil',
                            //                     text: 'Berhasil menghapus data jadwal_periksa',
                            //                     showConfirmButton: true
                            //                 }).then((result) => {
                            //                     if (result.isConfirmed) {
                            //                         window.location.href = 'dashboard.php?page=jadwal/jadwal_periksa';
                            //                     }
                            //                 });
                            //             </script>";
                            //     } else {
                            //         echo "<script>
                            //                 Swal.fire({
                            //                     icon: 'error',
                            //                     title: 'Gagal',
                            //                     text: 'Gagal menghapus data jadwal_periksa',
                            //                     showConfirmButton: true
                            //                 });
                            //             </script>";
                            //     }
                            // }

                            // Fetch data from database based on ID
                            if (isset($_GET['action']) && $_GET['action'] == 'fetch' && isset($_GET['id_jadwal'])) {
                                $id_jadwal = mysqli_real_escape_string($conn, $_GET['id_jadwal']);
                                $result = mysqli_query($conn, "SELECT * FROM jadwal_periksa WHERE id='$id_jadwal'");
                                $data = mysqli_fetch_array($result);
                                echo json_encode($data);
                                exit(); // Stop further execution
                            }
                            ?>

                            <script>
                            function validate() {
                                var nama = document.forms["myForm"]["id_dokter"].value;
                                var hari = document.forms["myForm"]["hari"].value;
                                var jam_mulai = document.forms["myForm"]["jam_mulai"].value;
                                var jam_selesai = document.forms["myForm"]["jam_selesai"].value;
                                if (nama == "") {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Nama dokter harus diisi',
                                        showConfirmButton: true
                                    });
                                    return false;
                                }
                                if (hari == "") {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Hari harus diisi',
                                        showConfirmButton: true
                                    });
                                    return false;
                                }
                                if (jam_mulai == "") {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Jam Mulai harus diisi',
                                        showConfirmButton: true
                                    });
                                    return false;
                                }
                                if (jam_selesai == "") {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Jam Selesai harus diisi',
                                        showConfirmButton: true
                                    });
                                    return false;
                                }
                                return true;
                            }

                            function getCookie(name) {
                                let cookieArr = document.cookie.split(";");

                                for(let i = 0; i < cookieArr.length; i++) {
                                    let cookiePair = cookieArr[i].split("=");

                                    if(name == cookiePair[0].trim()) {
                                        return decodeURIComponent(cookiePair[1]);
                                    }
                                }
                                return null;
                            }

                            document.addEventListener("DOMContentLoaded", function() {
                                const savedName = getCookie("name");
                                if (savedName) {
                                    const selectElement = document.getElementById("nama_dokter");
                                    for (let i = 0; i < selectElement.options.length; i++) {
                                        if (selectElement.options[i].text === savedName) {
                                            selectElement.selectedIndex = i;
                                            break;
                                        }
                                    }
                                }

                                $('#nama_dokter').select2({
                                    placeholder: 'Pilih Dokter',
                                    allowClear: true
                                });
                            });
                            </script>

                            <div class="mt-3" id="edit-jadwal-form">
                                <form action="" method="POST" name="myForm" onsubmit="return(validate());">
                                    <!-- Tambahkan input hidden untuk menyimpan ID jadwal -->
                                    <input type="hidden" name="id_jadwal" id="id_jadwal" value="<?php echo $id_jadwal; ?>">
                                    <!-- Tambahkan judul yang sesuai -->
                                    <h4 class="card-title">Edit Jadwal Periksa</h4>

                                    <!-- Sisipkan logika PHP untuk menampilkan data jadwal yang sesuai -->
                                    <?php
                                    // Query database untuk mendapatkan data jadwal berdasarkan ID
                                    $result = mysqli_query($conn, "SELECT * FROM jadwal_periksa WHERE id='$id_jadwal'");
                                    $data = mysqli_fetch_array($result);
                                    ?>

                                    <!-- Tampilkan formulir dengan data jadwal yang sesuai -->
                                    <div class="form-group">
                                        <label for="nama_dokter">Nama Dokter</label>
                                        <!-- Isi nilai default dengan data dari database -->
                                        <?php
                                        $sql = "SELECT * FROM dokter WHERE id_dokter = " . $data["id_dokter"];
                                        $result = $conn->query($sql);
                                        $dokter = $result->fetch_assoc();
                                        ?>
                                        <input type="text" class="form-control" id="nama_dokter" value="<?php echo $dokter['nama_dokter']; ?>" readonly>
                                        <input type="hidden" name="id_dokter" value="<?php echo $data['id_dokter']; ?>">
                                    </div>

                                    <!-- Sisipkan logika PHP untuk menandai hari yang dipilih -->
                                    <div class="form-group">
                                        <label for="hari">Hari</label>
                                        <select class="form-control" id="hari" name="hari" required>
                                            <option value="">Pilih Hari</option>
                                            <option value="Senin" <?php echo ($hari == 'Senin') ? 'selected' : ''; ?>>Senin</option>
                                            <option value="Selasa" <?php echo ($hari == 'Selasa') ? 'selected' : ''; ?>>Selasa</option>
                                            <option value="Rabu" <?php echo ($hari == 'Rabu') ? 'selected' : ''; ?>>Rabu</option>
                                            <option value="Kamis" <?php echo ($hari == 'Kamis') ? 'selected' : ''; ?>>Kamis</option>
                                            <option value="Jumat" <?php echo ($hari == 'Jumat') ? 'selected' : ''; ?>>Jumat</option>
                                            <option value="Sabtu" <?php echo ($hari == 'Sabtu') ? 'selected' : ''; ?>>Sabtu</option>
                                            <option value="Minggu" <?php echo ($hari == 'Minggu') ? 'selected' : ''; ?>>Minggu</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="jam_mulai">Jam Mulai</label>
                                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" value="<?php echo $data['jam_mulai']; ?>" required>
                                    </div>

                                    <!-- Sisipkan logika PHP untuk mengisi nilai jam selesai -->
                                    <div class="form-group">
                                        <label for="jam_selesai">Jam Selesai</label>
                                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="<?php echo $data['jam_selesai']; ?>" required>
                                    </div>

                                    <!-- Tombol untuk mengirim formulir -->
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021.  Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
            </div>
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a href="https://www.themewagon.com/" target="_blank">Themewagon</a></span> 
            </div>
        </footer>
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

