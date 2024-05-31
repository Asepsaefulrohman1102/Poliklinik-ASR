<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pasien</title>
  <!-- Include SweetAlert2 JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="row">
        <div class="col">
          <div class="card-body">
            <h4 class="card-title">Data Pasien</h4>
            <p class="card-description"> Data pasien yang terdaftar </p>
            <button id="tambah-pasien-btn" class="btn btn-primary mt-3">Tambah pasien</button>
            <?php
              // Koneksi ke database
              $conn = mysqli_connect("localhost", "root", "", "poliklinik");
              if (!$conn) {
                  die("Koneksi gagal: " . mysqli_connect_error());
              }

              // Fungsi untuk menghasilkan nomor rekam medis otomatis
              function generateNoRekamMedis($conn) {
                // Initialize a counter variable
                static $counter = 0;
            
                // Generate a potential no_rekam_medis using the counter
                $potential_no_rekam_medis = 'RM' . date('Ym') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
            
                // Check if the potential no_rekam_medis already exists in the database
                $query = "SELECT COUNT(*) FROM pasien WHERE no_rekam_medis='$potential_no_rekam_medis'";
                $result = mysqli_query($conn, $query);
                $count = mysqli_fetch_row($result)[0];
            
                // If it exists, increment the counter and try again
                while ($count > 0) {
                    $counter++;
                    $potential_no_rekam_medis = 'RM' . date('Ym') . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
                    $query = "SELECT COUNT(*) FROM pasien WHERE no_rekam_medis='$potential_no_rekam_medis'";
                    $result = mysqli_query($conn, $query);
                    $count = mysqli_fetch_row($result)[0];
                }
            
                // If no duplicates found, return the generated no_rekam_medis
                return $potential_no_rekam_medis;
            }

              $nama = '';
              $alamat = '';
              $no_ktp = '';
              $no_hp = '';
              $no_rekam_medis = generateNoRekamMedis($conn); // Panggil fungsi generate nomor rekam medis otomatis
              $id = '';

              // Handle form submission
              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $nama = mysqli_real_escape_string($conn, $_POST['nama_pasien']);
                  $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
                  $no_ktp = mysqli_real_escape_string($conn, $_POST['no_ktp']);
                  $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
                  $no_rekam_medis = mysqli_real_escape_string($conn, $_POST['no_rekam_medis']);
                  $id = mysqli_real_escape_string($conn, $_POST['id']);

                  if (!empty($id)) {
                    $query = "UPDATE pasien SET nama_pasien='$nama', alamat='$alamat', no_ktp='$no_ktp', no_hp='$no_hp', no_rekam_medis='$no_rekam_medis' WHERE id='$id'";
                    if (mysqli_query($conn, $query)) {
                      echo "<script>
                              Swal.fire({
                                  icon: 'success',
                                  title: 'Berhasil',
                                  text: 'Berhasil mengubah data pasien',
                                  showConfirmButton: true
                              }).then((result) => {
                                  if (result.isConfirmed) {
                                      window.location.href = 'dashboard.php?page=pages/admin/pasien';
                                  }
                              });
                            </script>";
                  } else {
                      echo "<script>
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Gagal',
                                  text: 'Gagal mengubah data pasien',
                                  showConfirmButton: true
                              });
                            </script>";
                  }
                } else {
                    $query = "INSERT INTO pasien (nama_pasien, alamat, no_ktp, no_hp, no_rekam_medis) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rekam_medis')";
                    if (mysqli_query($conn, $query)) {
                      echo "<script>
                              Swal.fire({
                                  icon: 'success',
                                  title: 'Berhasil',
                                  text: 'Berhasil menambahkan data pasien',
                                  showConfirmButton: true
                              }).then((result) => {
                                  if (result.isConfirmed) {
                                      window.location.href = 'dashboard.php?page=pages/admin/pasien';
                                  }
                              });
                            </script>";
                  } else {
                      echo "<script>
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Gagal',
                                  text: 'Gagal menambahkan data pasien',
                                  showConfirmButton: true
                              });
                            </script>";
                  }
                }

                  
              } elseif (isset($_GET['id'])) {
                  $id = mysqli_real_escape_string($conn, $_GET['id']);
                  $result = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$id'");
                  if ($data = mysqli_fetch_array($result)) {
                      $nama = $data['nama_pasien'];
                      $alamat = $data['alamat'];
                      $no_ktp = $data['no_ktp'];
                      $no_hp = $data['no_hp'];
                      $no_rekam_medis = $data['no_rekam_medis'];
                  }

              }

              // delete data
              if (isset($_GET['id_hapus'])) {
                  $id = mysqli_real_escape_string($conn, $_GET['id_hapus']);
                  $query = "DELETE FROM pasien WHERE id='$id'";
                  if (mysqli_query($conn, $query)) {
                      echo "<script>
                              Swal.fire({
                                  icon: 'success',
                                  title: 'Berhasil',
                                  text: 'Berhasil menghapus data pasien',
                                  showConfirmButton: true
                              }).then((result) => {
                                  if (result.isConfirmed) {
                                      window.location.href = 'dashboard.php?page=pages/admin/pasien';
                                  }
                              });
                            </script>";
                  } else {
                      echo "<script>
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Gagal',
                                  text: 'Gagal menghapus data pasien',
                                  showConfirmButton: true
                              });
                            </script>";
                  }
              }


              // Fetch data from database based on ID
              if(isset($_GET['action']) && $_GET['action'] == 'fetch' && isset($_GET['id'])) {
                  $id = mysqli_real_escape_string($conn, $_GET['id']);
                  $result = mysqli_query($conn, "SELECT * FROM pasien WHERE id='$id'");
                  $data = mysqli_fetch_array($result);
                  echo json_encode($data);
              }

            ?>

            <script>
            function validate() {
                var nama = document.forms["myForm"]["nama"].value;
                var keterangan = document.forms["myForm"]["keterangan"].value;
                if (nama == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Nama pasien harus diisi',
                        showConfirmButton: true
                    });
                    return false;
                }
                if (keterangan == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Keterangan harus diisi',
                        showConfirmButton: true
                    });
                    return false;
                }
            }

            </script>

            <div class="mt-3" id="tambah-pasien-form">
              <form action="" method="POST" name="myForm" onsubmit="return validate();">

                <input type="hidden" name="id" id="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">

                <div class="form-group">
                    <label for="nama">Nama Pasien</label>
                    <input type="text" class="form-control" id="nama" name="nama_pasien" value="<?php echo $nama; ?>" required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat; ?>" required>
                </div>

                <div class="form-group">
                    <label for="no_ktp">No KTP</label>
                    <input type="text" class="form-control" id="no_ktp" name="no_ktp" value="<?php echo $no_ktp; ?>" required
                          onkeyup="validateNumber(this)">  <span id="no_ktp_error" class="text-danger"></span>  </div>

                <div class="form-group">
                    <label for="no_hp">No HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo $no_hp; ?>" required
                          onkeyup="validateNumber(this)">  <span id="no_hp_error" class="text-danger"></span>  </div>

                <div class="form-group">
                    <label for="no_rekam_medis">No Rekam Medis</label>
                    <input type="text" class="form-control" id="no_rekam_medis" name="no_rekam_medis" value="<?php echo $no_rekam_medis; ?>" readonly>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>

            <div class="table-responsive mt-3">
              <table class="table">
                <thead>
                  <tr>
                    <th> No </th>
                    <th> Nama </th>
                    <th> Alamat </th>
                    <th> No KTP </th>
                    <th> No HP </th>
                    <th> No Rekam Medis </th>
                    <th> Aksi </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $result = mysqli_query($conn, "SELECT * FROM pasien");
                    $no = 1;
                    while ($data = mysqli_fetch_array($result)) {
                  ?>
                  <tr>
                    <td> <?php echo $no++; ?> </td>
                    <td> <?php echo $data['nama_pasien']; ?> </td>
                    <td> <?php echo $data['alamat']; ?> </td>
                    <td> <?php echo $data['no_ktp']; ?> </td>
                    <td> <?php echo $data['no_hp']; ?> </td>
                    <td> <?php echo $data['no_rekam_medis']; ?> </td>
                    <td>
                      <a href="dashboard.php?page=pages/admin/pasien&id=<?php echo $data['id']; ?>" id="edit-pasien-btn" class="btn btn-warning">Edit</a>
                      <a href="dashboard.php?page=pages/admin/pasien&id_hapus=<?php echo $data['id']; ?>" class="btn btn-danger">Hapus</a>
                    </td>
                  </tr>
                  <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
<script>
  function validateNumber(inputField) {
    // Regular expression for numbers only (no spaces, special characters, etc.)
    const numberRegex = /^\d+$/;

    if (!numberRegex.test(inputField.value)) {
        // Display error message if input is not a number
        const errorId = inputField.id + "_error";
        document.getElementById(errorId).textContent = "Hanya boleh angka";
        return false; // Prevent form submission if validation fails
    } else {
        // Clear error message if input is valid
        const errorId = inputField.id + "_error";
        document.getElementById(errorId).textContent = "";
        return true; // Allow form submission if validation succeeds
    }
}

function validate() {
    // Call validateNumber() for both No KTP and No HP fields
    const noKtpValid = validateNumber(document.getElementById("no_ktp"));
    const noHpValid = validateNumber(document.getElementById("no_hp"));

    // Return true only if both No KTP and No HP are valid
    return noKtpValid && noHpValid;
}
</script>
</body>
</html>


