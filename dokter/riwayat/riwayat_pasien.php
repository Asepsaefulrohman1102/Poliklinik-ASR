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
                        <h4 class="card-title">Riwayat Pasien</h4>
                        <p class="card-description"> Daftar riwayat pasien yang terdaftar </p>
                        <?php
                            // Koneksi ke database
                            $conn = mysqli_connect("localhost", "root", "", "poliklinik");
                            if (!$conn) {
                                die("Koneksi gagal: " . mysqli_connect_error());
                            }

                            // Ambil nama dokter dari session
                            $nama_dokter = $_SESSION['name'];

                            // Query untuk mendapatkan ID dokter berdasarkan nama dokter
                            $query_id_dokter = "SELECT id_dokter FROM dokter WHERE nama_dokter = '$nama_dokter'";
                            $result_id_dokter = mysqli_query($conn, $query_id_dokter);

                            if ($result_id_dokter) {
                                $row_id_dokter = mysqli_fetch_assoc($result_id_dokter);
                                $id_dokter = $row_id_dokter['id_dokter'];
                            } else {
                                // Handle error jika query tidak berhasil
                                die("Error: " . mysqli_error($conn));
                            }

                            // Query untuk menampilkan data pasien yang sudah pernah diperiksa berdasarkan dokter yang melakukan pemeriksaan
                            $query = "SELECT DISTINCT pasien.id, pasien.nama_pasien, pasien.alamat, pasien.no_ktp, pasien.no_hp, pasien.no_rekam_medis
                            FROM periksa
                            INNER JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id_poli
                            INNER JOIN pasien ON daftar_poli.id_pasien = pasien.id
                            INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
                            INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id_dokter
                            WHERE periksa.id_periksa IS NOT NULL
                            AND dokter.id_dokter = $id_dokter
                            GROUP BY pasien.id";

                            $result = mysqli_query($conn, $query);
                            ?>

                            <div class="table-responsive mt-3">
                                <table class="table dataTable">
                                    <thead>
                                        <tr>
                                            <th> No </th>
                                            <th> Nama Pasien </th>
                                            <th> Alamat </th>
                                            <th> No. KTP </th>
                                            <th> No. Telepon </th>
                                            <th> No. RM </th>
                                            <th> Aksi </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
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
                                                    <a href="riwayat/detail_riwayat.php?id_pasien=<?php echo $data['id']; ?>" class="btn btn-primary">Detail</a>
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
