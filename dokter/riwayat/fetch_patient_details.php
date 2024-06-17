<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "poliklinik");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (!isset($_GET['id_pasien'])) {
    die("ID Pasien tidak ditemukan.");
}

$id_pasien = intval($_GET['id_pasien']);
$nama_dokter = $_SESSION['name'];

$query_detail = "
    SELECT 
        periksa.tgl_periksa, 
        pasien.nama_pasien, 
        dokter.nama_dokter, 
        daftar_poli.keluhan, 
        periksa.catatan, 
        GROUP_CONCAT(obat.nama_obat SEPARATOR ', ') AS obat, 
        periksa.biaya_periksa
    FROM periksa
    INNER JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id_poli
    INNER JOIN pasien ON daftar_poli.id_pasien = pasien.id
    INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
    INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id_dokter
    LEFT JOIN detail_periksa ON periksa.id_periksa = detail_periksa.id_periksa
    LEFT JOIN obat ON detail_periksa.id_obat = obat.id_obat
    WHERE pasien.id = $id_pasien
    AND dokter.nama_dokter = '$nama_dokter'
    GROUP BY periksa.id_periksa, periksa.tgl_periksa, pasien.nama_pasien, dokter.nama_dokter, daftar_poli.keluhan, periksa.catatan, periksa.biaya_periksa
";

$result_detail = mysqli_query($conn, $query_detail);

if (!$result_detail) {
    die("Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result_detail) > 0) {
    while ($data_detail = mysqli_fetch_assoc($result_detail)) {
        echo '<div class="table-responsive mt-3">';
        echo '<table class="table">';
        echo '<tbody>';
        echo '<tr><th>Tanggal Periksa</th><td>' . $data_detail['tgl_periksa'] . '</td></tr>';
        echo '<tr><th>Nama Pasien</th><td>' . $data_detail['nama_pasien'] . '</td></tr>';
        echo '<tr><th>Nama Dokter</th><td>' . $data_detail['nama_dokter'] . '</td></tr>';
        echo '<tr><th>Keluhan</th><td>' . $data_detail['keluhan'] . '</td></tr>';
        echo '<tr><th>Catatan</th><td>' . $data_detail['catatan'] . '</td></tr>';
        echo '<tr><th>Obat yang Dibeli</th><td>' . $data_detail['obat'] . '</td></tr>';
        echo '<tr><th>Biaya Periksa</th><td>' . $data_detail['biaya_periksa'] . '</td></tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
} else {
    echo 'Detail riwayat tidak ditemukan.';
}

mysqli_close($conn);
?>
