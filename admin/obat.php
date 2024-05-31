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
            <h4 class="card-title">Data Obat</h4>
            <p class="card-description"> Data Obat yang terdaftar </p>
            <div class=" btn btn-primary mt-2">Kelola Obat</div>

            <?php
            // Koneksi ke database
            $conn = mysqli_connect("localhost", "root", "", "poliklinik");
            if (!$conn) {
                die("Koneksi gagal: " . mysqli_connect_error());
            }

            $nama_obat = '';
            $kemasan = '';
            $harga = '';
            $id_obat = '';

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama_obat = mysqli_real_escape_string($conn, $_POST['nama_obat']);
                $kemasan = mysqli_real_escape_string($conn, $_POST['kemasan']);
                $harga = mysqli_real_escape_string($conn, $_POST['harga']);
                $id_obat = mysqli_real_escape_string($conn, $_POST['id_obat']);


                if (!empty($id_obat)) {
                    $query = "UPDATE obat SET nama_obat='$nama_obat', kemasan='$kemasan', harga='$harga' WHERE id_obat='$id_obat'";
                    if (mysqli_query($conn, $query)) {
                        echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Berhasil mengubah data obat',
                                    showConfirmButton: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'dashboard.php?page=pages/admin/obat';
                                    }
                                });
                              </script>";
                    } else {
                        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal mengubah data obat',
                                    showConfirmButton: true
                                });
                              </script>";
                    }
                } else {
                    $query = "INSERT INTO obat (nama_obat, kemasan, harga) VALUES ('$nama_obat', '$kemasan', '$harga')";
                    if (mysqli_query($conn, $query)) {
                        echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Berhasil menambahkan data obat',
                                    showConfirmButton: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'dashboard.php?page=pages/admin/obat';
                                    }
                                });
                              </script>";
                    } else {
                        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Gagal menambahkan data obat',
                                    showConfirmButton: true
                                });
                              </script>";
                    }
                }
            } elseif (isset($_GET['id'])) {
                $id_obat = mysqli_real_escape_string($conn, $_GET['id']);
                $result = mysqli_query($conn, "SELECT * FROM obat WHERE id_obat='$id_obat'");
                if ($data = mysqli_fetch_array($result)) {
                    $nama_obat = $data['nama_obat'];
                    $kemasan = $data['kemasan'];
                    $harga = $data['harga'];
                }
            }

            // delete data
            if (isset($_GET['id_hapus'])) {
                $id_obat = mysqli_real_escape_string($conn, $_GET['id_hapus']);
                $query = "DELETE FROM obat WHERE id_obat='$id_obat'";
                if (mysqli_query($conn, $query)) {
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Berhasil menghapus data obat',
                                showConfirmButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'dashboard.php?page=pages/admin/obat';
                                }
                            });
                        </script>";
                } else {
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menghapus data obat',
                                showConfirmButton: true
                            });
                        </script>";
                }
            }

            // Fetch data from database based on ID
            if (isset($_GET['action']) && $_GET['action'] == 'fetch' && isset($_GET['id_obat'])) {
                $id_obat = mysqli_real_escape_string($conn, $_GET['id_obat']);
                $result = mysqli_query($conn, "SELECT * FROM obat WHERE id_obat='$id_obat'");
                $data = mysqli_fetch_array($result);
                echo json_encode($data);
                exit(); // Stop further execution
            }
            ?>

            <script>
            function validate() {
                var nama = document.forms["myForm"]["nama_obat"].value;
                var kemasan = document.forms["myForm"]["kemasan"].value;
                var harga = document.forms["myForm"]["harga"].value;
                if (nama == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Nama obat harus diisi',
                        showConfirmButton: true
                    });
                    return false;
                }
                if (kemasan == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Kemasan harus diisi',
                        showConfirmButton: true
                    });
                    return false;
                }
                if (harga == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Harga harus diisi',
                        showConfirmButton: true
                    });
                    return false;
                }
                return true;
            }
            </script>

            <div class="mt-3" id="tambah-obat-form">
                <form action="" method="POST" name="myForm" onsubmit="return(validate());">
                <input type="hidden" name="id_obat" id="id_obat" value="<?php echo $id_obat; ?>">
                    <div class="form-group">
                        <label for="nama_obat">Nama obat</label>
                        <input type="text" class="form-control" id="nama_obat" name="nama_obat" required value="<?php echo $nama_obat; ?>">
                    </div>
                    <div class="form-group">
                        <label for="kemasan">Kemasan</label>
                        <input type="text" class="form-control" id="kemasan" name="kemasan" required value="<?php echo $kemasan; ?>">
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required value="<?php echo $harga; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th> No </th>
                            <th> Nama Obat </th>
                            <th> Kemasan </th>
                            <th> Harga </th>
                            <th> Aksi </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($conn, "SELECT * FROM obat");
                        while ($data = mysqli_fetch_array($query)) {
                        ?>
                            <tr>
                                <td> <?php echo $no++; ?> </td>
                                <td> <?php echo $data['nama_obat']; ?> </td>
                                <td> <?php echo $data['kemasan']; ?> </td>
                                <td> <?php echo 'Rp. '.$data['harga']; ?> </td>
                                <td>
                                    <a href="dashboard.php?page=pages/admin/obat&id=<?php echo $data['id_obat']; ?>" class="btn btn-warning">Edit</a>
                                    <a href="dashboard.php?page=pages/admin/obat&id_hapus=<?php echo $data['id_obat']; ?>" class="btn btn-danger">Hapus</a>
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
