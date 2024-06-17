<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Poli</title>
  <!-- Include SweetAlert2 JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

</head>
<body>
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="row">
        <div class="col">
          <div class="card-body">
            <h4 class="card-title">Jadwal Periksa</h4>
            <p class="card-description"> Data Dokter yang terdaftar </p>
            <a href="dashboard.php?page=jadwal/tambah_jadwal" class="btn btn-primary mt-2">Tambah Jadwal</a>

            <?php
           // Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "poliklinik");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Hapus data
if (isset($_GET['id_hapus'])) {
    $id_jadwal = $_GET['id_hapus'];
    // Lakukan pembersihan nilai dan pastikan hanya angka yang diterima
    $id_jadwal = filter_var($id_jadwal, FILTER_VALIDATE_INT);
    if ($id_jadwal !== false) {
        $query = "DELETE FROM jadwal_periksa WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id_jadwal);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Berhasil menghapus data jadwal periksa',
                        showConfirmButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'dashboard.php?page=jadwal/jadwal_periksa';
                        }
                    });
                </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal menghapus data jadwal periksa',
                        showConfirmButton: true
                    });
                </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Parameter ID tidak valid',
                    showConfirmButton: true
                });
            </script>";
    }
}

            ?>

            <script>
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

                $(document).ready(function() {
                    let namaDokter = getCookie("name");
                    if (namaDokter) {
                        $.ajax({
                            url: 'jadwal/data_jadwal.php',
                            method: 'GET',
                            data: { nama_dokter: namaDokter },
                            success: function(response) {
                                let data = JSON.parse(response);
                                let tableBody = $('table tbody');
                                tableBody.empty();
                                data.forEach((item, index) => {
                                    tableBody.append(`
                                        <tr>
                                            <td> ${index + 1} </td>
                                            <td> ${item.nama_dokter} </td>
                                            <td> ${item.hari} </td>
                                            <td> ${item.jam_mulai} </td>
                                            <td> ${item.jam_selesai} </td>
                                            <td>
                                                <a href="jadwal/edit_jadwal.php?id=${item.id}" class="btn btn-warning">Edit</a>
                                                
                                            </td>
                                        </tr>
                                    `);
                                });
                            }
                        });
                    }
                });
            </script>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th> No </th>
                            <th> Nama Dokter </th>
                            <th> Hari </th>
                            <th> Jam Mulai </th>
                            <th> Jam Selesai </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
