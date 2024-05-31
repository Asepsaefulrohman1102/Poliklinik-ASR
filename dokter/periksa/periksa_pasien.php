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
                        <h4 class="card-title">Data Periksa</h4>
                        <p class="card-description"> Daftar periksa pasien yang terdaftar </p>
                        <?php
                        // Koneksi ke database
                        $conn = mysqli_connect("localhost", "root", "", "poliklinik");
                        if (!$conn) {
                            die("Koneksi gagal: " . mysqli_connect_error());
                        }
                        
                        // Ambil nama dokter dari cookie
                        $nama_dokter = $_COOKIE['name']; // Ubah 'name' sesuai dengan nama yang digunakan dalam cookie
                        // echo '<h1>Selamat datang, ' . $nama_dokter . '</h1>';

                        // Ubah kueri SQL untuk mengambil ID poli dokter berdasarkan nama
                        $dokter_result = mysqli_query($conn, "SELECT ID_POLI, ID_DOKTER FROM dokter WHERE NAMA_DOKTER = '$nama_dokter'");
                        if (!$dokter_result) {
                            die("Kueri dokter gagal: " . mysqli_error($conn));
                        }
                        $dokter_data = mysqli_fetch_array($dokter_result);
                        $id_poli_dokter = $dokter_data['ID_POLI'];
                        $id_dokter = $dokter_data['ID_DOKTER'];
                        // echo 'ID Poli Dokter: ' . $id_poli_dokter . '<br>';
                        // echo 'ID Dokter: ' . $dokter_data['ID_DOKTER'] . '<br>';

                        // Ubah kueri SQL untuk mengambil data dari tabel daftar poli yang memiliki ID poli yang sama dengan dokter tersebut
                        $result = mysqli_query($conn, "SELECT daftar_poli.*, pasien.nama_pasien, periksa.id_periksa 
                        FROM daftar_poli 
                        JOIN pasien ON daftar_poli.ID_PASIEN = pasien.ID 
                        LEFT JOIN periksa ON daftar_poli.id_poli = periksa.id_daftar_poli 
                        JOIN jadwal_periksa ON daftar_poli.ID_JADWAL = jadwal_periksa.ID 
                        WHERE jadwal_periksa.ID_DOKTER = '$id_dokter'");

                        if (!$result) {
                            die("Kueri data gagal: " . mysqli_error($conn));
                        }
                        ?>
                        
                        <div class="table-responsive mt-3">
                            <table class="table dataTable">
                                <thead>
                                    <tr>
                                        <th> No Urut </th>
                                        <th> Nama Pasien </th>
                                        <th> keluhan </th>
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
                                            <td> <?php echo $data['keluhan']; ?> </td>
                                            <td>
                                            <?php if ($data['id_periksa'] != NULL) { ?>
                                                <a href="periksa/edit_periksa.php?id_periksa=<?php echo $data['id_periksa']; ?>&id_poli=<?php echo $data['id_poli']; ?>&nama_pasien=<?php echo $data['nama_pasien']; ?>&keluhan=<?php echo $data['keluhan']; ?>&no_antrian=<?php echo $data['no_antrian']; ?>" class="btn btn-warning">Edit</a>
                                            <?php } else { ?>
                                                <a href="periksa/periksa_tindakan.php?id_poli=<?php echo $data['id_poli']; ?>&nama_pasien=<?php echo $data['nama_pasien']; ?>&keluhan=<?php echo $data['keluhan']; ?>&no_antrian=<?php echo $data['no_antrian']; ?>" class="btn btn-primary">Periksa</a>
                                            <?php } ?>

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
