<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Poli</title>
  <!-- Include SweetAlert2 JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="row">
        <div class="col">
          <div class="card-body">
            <h4 class="card-title">Data Poli</h4>
            <p class="card-description"> Data Poli yang terdaftar </p>
            <div id="tambah-poli-btn" class="btn btn-primary mt-2">Kelola Poli</div>
            <?php
              // Koneksi ke database
              $conn = mysqli_connect("localhost", "root", "", "poliklinik");
              if (!$conn) {
                  die("Koneksi gagal: " . mysqli_connect_error());
              }

              $nama = '';
              $keterangan = '';
              $id = '';

              // Handle form submission
              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
                  $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
                  $id = mysqli_real_escape_string($conn, $_POST['id']);

                  if (!empty($id)) {
                      $query = "UPDATE poli SET nama_poli='$nama', keterangan='$keterangan' WHERE id='$id'";
                      if (mysqli_query($conn, $query)) {
                        echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Berhasil mengubah data poli',
                                    showConfirmButton: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'dashboard.php?page=pages/admin/poli';
                                    }
                                });
                              </script>";
                    } else {
                        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal mengubah data poli',
                                    showConfirmButton: true
                                });
                              </script>";
                    }
                  } else {
                      $query = "INSERT INTO poli (nama_poli, keterangan) VALUES ('$nama', '$keterangan')";
                      if (mysqli_query($conn, $query)) {
                        echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Berhasil menambahkan data poli',
                                    showConfirmButton: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'dashboard.php?page=pages/admin/poli';
                                    }
                                });
                              </script>";
                    } else {
                        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal menambahkan data poli',
                                    showConfirmButton: true
                                });
                              </script>";
                    }
                  }

              } elseif (isset($_GET['id'])) {
                  $id = mysqli_real_escape_string($conn, $_GET['id']);
                  $result = mysqli_query($conn, "SELECT * FROM poli WHERE id='$id'");
                  if ($data = mysqli_fetch_array($result)) {
                      $nama = $data['nama_poli'];
                      $keterangan = $data['keterangan'];
                  }
              }

              // delete data
              if (isset($_GET['id_hapus'])) {
                  $id = mysqli_real_escape_string($conn, $_GET['id_hapus']);
                  $query = "DELETE FROM poli WHERE id='$id'";
                  if (mysqli_query($conn, $query)) {
                      echo "<script>
                              Swal.fire({
                                  icon: 'success',
                                  title: 'Berhasil',
                                  text: 'Berhasil menghapus data poli',
                                  showConfirmButton: true
                              }).then((result) => {
                                  if (result.isConfirmed) {
                                      window.location.href = 'dashboard.php?page=pages/admin/poli';
                                  }
                              });
                            </script>";
                  } else {
                      echo "<script>
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Gagal',
                                  text: 'Gagal menghapus data poli',
                                  showConfirmButton: true
                              });
                            </script>";
                  }
              }

              // Fetch data from database based on ID
              if (isset($_GET['action']) && $_GET['action'] == 'fetch' && isset($_GET['id'])) {
                  $id = mysqli_real_escape_string($conn, $_GET['id']);
                  $result = mysqli_query($conn, "SELECT * FROM poli WHERE id='$id'");
                  $data = mysqli_fetch_array($result);
                  echo json_encode($data);
                  exit(); // Stop further execution
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
                        text: 'Nama Poli harus diisi',
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

            <div class="mt-3" id="tambah-poli-form">
              <form action="" method="POST" name="myForm" onsubmit="return(validate());" style="display: block;">

                <input type="hidden" name="id" id="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                <div class="form-group">
                  <label for="nama">Nama Poli</label>
                  <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                </div>
                <div class="form-group">
                  <label for="keterangan">Keterangan</label>
                  <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required><?php echo $keterangan; ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
              </form>
            </div>

            <div class="table-responsive mt-3">
              <table class="table">
                <thead>
                  <tr>
                    <th> No </th>
                    <th> Nama </th>
                    <th> Keterangan </th>
                    <th> Aksi </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Koneksi ke database
                  $no = 1;
                  $query = mysqli_query($conn, "SELECT * FROM poli");
                  while ($data = mysqli_fetch_array($query)) {
                  ?>
                      <tr>
                          <td> <?php echo $no++; ?> </td>
                          <td> <?php echo $data['nama_poli']; ?> </td>
                          <td> <?php echo $data['keterangan']; ?> </td>
                          <td>
                              <a href="dashboard.php?page=pages/admin/poli&id_hapus=<?php echo $data['id']; ?>" class="btn btn-danger">Hapus</a>
                              <a href="dashboard.php?page=pages/admin/poli&id=<?php echo $data['id']; ?>" id="edit-poli-btn" class="btn btn-warning">Edit</a>
                          </td>
                      </tr>
                  <?php
                  }
                  ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>


