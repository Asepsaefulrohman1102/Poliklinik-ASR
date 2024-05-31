<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat Pasien</title>
    <!-- Include SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="row">
                <div class="col">
                    <div class="card-body">
                        <h4 class="card-title">Detail Riwayat Pasien</h4>
                        <p class="card-description"> Detail riwayat pemeriksaan pasien </p>
                        <?php
                            // Koneksi ke database
                            $conn = mysqli_connect("localhost", "root", "", "poliklinik");
                            if (!$conn) {
                                die("Koneksi gagal: " . mysqli_connect_error());
                            }

                            // Ambil ID pasien dari parameter URL
                            $id_pasien = $_GET['id_pasien'];

                            // Query untuk mendapatkan detail riwayat berdasarkan ID pasien
                            $query_detail = "SELECT periksa.tgl_periksa, pasien.nama_pasien, dokter.nama_dokter, periksa.keluhan, periksa.catatan, periksa.obat, periksa.biaya_periksa
                                            FROM periksa
                                            INNER JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id_poli
                                            INNER JOIN pasien ON daftar_poli.id_pasien = pasien.id
                                            INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
                                            INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id_dokter
                                            WHERE pasien.id = $id_pasien";

                            $result_detail = mysqli_query($conn, $query_detail);

                            if (mysqli_num_rows($result_detail) > 0) {
                                while ($data_detail = mysqli_fetch_assoc($result_detail)) {
                                    ?>
                                    <div class="table-responsive mt-3">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Tanggal Periksa</th>
                                                    <td><?php echo $data_detail['tgl_periksa']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Nama Pasien</th>
                                                    <td><?php echo $data_detail['nama_pasien']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Nama Dokter</th>
                                                    <td><?php echo $data_detail['nama_dokter']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Keluhan</th>
                                                    <td><?php echo $data_detail['keluhan']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Catatan</th>
                                                    <td><?php echo $data_detail['catatan']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Obat</th>
                                                    <td><?php echo $data_detail['obat']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Biaya Periksa</th>
                                                    <td><?php echo $data_detail['biaya_periksa']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php }
                            } else {
                                echo "Detail riwayat tidak ditemukan.";
                            }
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
