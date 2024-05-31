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
            <h4 class="card-title">Data Dokter</h4>
            <p class="card-description"> Data Dokter yang terdaftar </p>
            <div class=" btn btn-primary mt-2">Kelola Dokter</div>

            <?php
            // Koneksi ke database
            $conn = mysqli_connect("localhost", "root", "", "poliklinik");
            if (!$conn) {
                die("Koneksi gagal: " . mysqli_connect_error());
            }

            $nama_dokter = '';
            $alamat = '';
            $no_hp = '';
            $id_poli = '';
            $id_dokter = '';

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama_dokter = mysqli_real_escape_string($conn, $_POST['nama_dokter']);
                $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
                $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
                $id_poli = mysqli_real_escape_string($conn, $_POST['poli']);
                $id_dokter = mysqli_real_escape_string($conn, $_POST['id_dokter']);

                if (!empty($id_dokter)) {
                    $query = "UPDATE dokter SET nama_dokter='$nama_dokter', alamat='$alamat', no_hp='$no_hp', id_poli='$id_poli' WHERE id_dokter='$id_dokter'";
                    if (mysqli_query($conn, $query)) {
                        echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Berhasil mengubah data dokter',
                                    showConfirmButton: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'dashboard.php?page=pages/admin/dokter';
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
                } else {
                    $query = "INSERT INTO dokter (nama_dokter, alamat, no_hp, id_poli) VALUES ('$nama_dokter', '$alamat', '$no_hp', '$id_poli')";
                    if (mysqli_query($conn, $query)) {
                        echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Berhasil menambahkan data dokter',
                                    showConfirmButton: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'dashboard.php?page=pages/admin/dokter';
                                    }
                                });
                              </script>";
                    } else {
                        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal menambahkan data dokter',
                                    showConfirmButton: true
                                });
                              </script>";
                    }
                }
            } elseif (isset($_GET['id'])) {
                $id_dokter = mysqli_real_escape_string($conn, $_GET['id']);
                $result = mysqli_query($conn, "SELECT * FROM dokter WHERE id_dokter='$id_dokter'");
                if ($data = mysqli_fetch_array($result)) {
                    $nama_dokter = $data['nama_dokter'];
                    $alamat = $data['alamat'];
                    $no_hp = $data['no_hp'];
                    $id_poli = $data['id_poli'];
                }
            }

            // delete data
            if (isset($_GET['id_hapus'])) {
                $id_dokter = mysqli_real_escape_string($conn, $_GET['id_hapus']);
                $query = "DELETE FROM dokter WHERE id_dokter='$id_dokter'";
                if (mysqli_query($conn, $query)) {
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Berhasil menghapus data dokter',
                                showConfirmButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'dashboard.php?page=pages/admin/dokter';
                                }
                            });
                          </script>";
                } else {
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menghapus data dokter',
                                showConfirmButton: true
                            });
                          </script>";
                }
            }

            // Fetch data from database based on ID
            if (isset($_GET['action']) && $_GET['action'] == 'fetch' && isset($_GET['id'])) {
                $id_dokter = mysqli_real_escape_string($conn, $_GET['id']);
                $result = mysqli_query($conn, "SELECT * FROM dokter WHERE id_dokter='$id_dokter'");
                $data = mysqli_fetch_array($result);
                echo json_encode($data);
                exit(); // Stop further execution
            }
            ?>

            <script>
            function validate() {
                var nama = document.forms["myForm"]["nama_dokter"].value;
                var alamat = document.forms["myForm"]["alamat"].value;
                var no_hp = document.forms["myForm"]["no_hp"].value;
                var poli = document.forms["myForm"]["poli"].value;
                if (nama == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Nama dokter harus diisi',
                        showConfirmButton: true
                    });
                    return false;
                }
                if (alamat == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Alamat harus diisi',
                        showConfirmButton: true
                    });
                    return false;
                }
                if (no_hp == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Nomor telepon harus diisi',
                        showConfirmButton: true
                    });
                    return false;
                }
                if (poli == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Poli harus diisi',
                        showConfirmButton: true
                    });
                    return false;
                }
                return true;
            }
            </script>

            <div class="mt-3" id="tambah-dokter-form">
                <form action="" method="POST" name="myForm" onsubmit="return(validate());">
                <input type="hidden" name="id_dokter" id="id_dokter" value="<?php echo $id_dokter; ?>">
                    <div class="form-group">
                        <label for="nama_dokter">Nama Dokter</label>
                        <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" required value="<?php echo $nama_dokter; ?>">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required value="<?php echo $alamat; ?>">
                    </div>
                    <div class="form-group">
                        <label for="no_hp">Nomor Telepon</label>
                        <input type="number" class="form-control" id="no_hp" name="no_hp" required value="<?php echo $no_hp; ?>" minlength="10" maxlength="15">
                    </div>
                    <div class="form-group">
                        <label for="poli">Poli</label>
                        <select class="form-control" id="poli" name="poli" required>
                            <option value="" selected>Pilih Poli</option>
                            <?php
                            $query = mysqli_query($conn, "SELECT * FROM poli");
                            while ($data = mysqli_fetch_array($query)) {
                            ?>
                                <option value="<?php echo $data['id']; ?>" <?php echo $id_poli == $data['id'] ? 'selected' : ''; ?>><?php echo $data['nama_poli']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th> No </th>
                            <th> Nama </th>
                            <th> Alamat </th>
                            <th> Poli</th>
                            <th> No. Telp </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($conn, "SELECT dokter.*, poli.nama_poli as id_poli FROM dokter JOIN poli ON dokter.id_poli = poli.id");
                        while ($data = mysqli_fetch_array($query)) {
                        ?>
                            <tr>
                                <td> <?php echo $no++; ?> </td>
                                <td> <?php echo $data['nama_dokter']; ?> </td>
                                <td> <?php echo $data['alamat']; ?> </td>
                                <td> <?php echo $data['id_poli']; ?> </td>
                                <td> <?php echo $data['no_hp']; ?> </td>
                                <td>
                                    <a href="dashboard.php?page=pages/admin/dokter&id=<?php echo $data['id_dokter']; ?>" class="btn btn-warning">Edit</a>
                                    <a href="dashboard.php?page=pages/admin/dokter&id_hapus=<?php echo $data['id_dokter']; ?>" class="btn btn-danger">Hapus</a>
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
  </div>
</body>
</html>
