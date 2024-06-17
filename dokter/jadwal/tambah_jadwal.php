<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Jadwal Periksa</title>
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
            <h4 class="card-title">Tambah Jadwal Periksa</h4>
            <div class="btn btn-primary mt-2">Kelola Dokter</div>

            <?php
            // Koneksi ke database
            $conn = mysqli_connect("localhost", "root", "", "poliklinik");
            if (!$conn) {
                die("Koneksi gagal: " . mysqli_connect_error());
            }

            $id_dokter = '';
            $hari = '';
            $jam_mulai = '';
            $jam_selesai = '';

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id_dokter = mysqli_real_escape_string($conn, $_POST['id_dokter']);
                $hari = mysqli_real_escape_string($conn, $_POST['hari']);
                $jam_mulai = mysqli_real_escape_string($conn, $_POST['jam_mulai']);
                $jam_selesai = mysqli_real_escape_string($conn, $_POST['jam_selesai']);

                // Cek apakah hari sudah digunakan untuk dokter yang sama
                $cek_query = "SELECT hari FROM jadwal_periksa WHERE hari='$hari'";
                $result = mysqli_query($conn, $cek_query);

                if (mysqli_num_rows($result) > 0) {
                    // Jika hari sudah digunakan, tampilkan pesan error dengan hari yang dipilih
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Dokter ini sudah memiliki jadwal pada hari $hari.',
                                showConfirmButton: true
                            });
                        </script>";
                } else {
                    // Jika hari belum digunakan, masukkan data baru
                    $query = "INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai) VALUES ('$id_dokter', '$hari', '$jam_mulai', '$jam_selesai')";
                    if (mysqli_query($conn, $query)) {
                        echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Berhasil menambahkan data jadwal periksa',
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
                                    text: 'Gagal menambahkan data jadwal_periksa',
                                    showConfirmButton: true
                                });
                            </script>";
                    }
                }
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

            <div class="mt-3" id="tambah-dokter-form">
                <form name="myForm" action="" method="POST" onsubmit="return validate()">
                    <div class="form-group">
                        <label for="nama_dokter">Nama Dokter</label>
                        <select class="form-control" id="nama_dokter" name="id_dokter" required>
                            <option value="">Pilih Dokter</option>
                            <?php
                            $sql = "SELECT * FROM dokter";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["id_dokter"] . "'>" . $row["nama_dokter"] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Tidak ada dokter</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <select class="form-control" id="hari" name="hari" required>
                            <option value="">Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                    </div>

                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai</label>
                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
