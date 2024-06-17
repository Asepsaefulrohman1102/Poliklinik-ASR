<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "poliklinik");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_GET['action']) && $_GET['action'] == 'riwayat_pasien') {
    $id_pasien = $_GET['id_pasien'];
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
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    } elseif (mysqli_num_rows($result_detail) > 0) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-striped table-bordered'>";
        echo "<thead class='thead-dark'>
                <tr>
                    <th>Tanggal Periksa</th>
                    <th>Nama Pasien</th>
                    <th>Nama Dokter</th>
                    <th>Keluhan</th>
                    <th>Catatan</th>
                    <th>Obat yang Dibeli</th>
                    <th>Biaya Periksa</th>
                </tr>
            </thead>";
        echo "<tbody>";

        // Array untuk nama bulan dalam bahasa Indonesia
        $bulanIndonesia = [
            1 => 'Januari', 
            2 => 'Februari', 
            3 => 'Maret', 
            4 => 'April', 
            5 => 'Mei', 
            6 => 'Juni', 
            7 => 'Juli', 
            8 => 'Agustus', 
            9 => 'September', 
            10 => 'Oktober', 
            11 => 'November', 
            12 => 'Desember'
        ];

        while ($data_detail = mysqli_fetch_assoc($result_detail)) {
            $timestamp = strtotime($data_detail['tgl_periksa']);
            $tanggal = date('d', $timestamp);
            $bulan = $bulanIndonesia[date('n', $timestamp)];
            $tahun = date('Y', $timestamp);
            $jam = date('H:i', $timestamp);
            $tgl_periksa = "$tanggal $bulan $tahun, $jam";

            echo "
                <tr>
                    <td>{$tgl_periksa}</td>
                    <td>{$data_detail['nama_pasien']}</td>
                    <td>{$data_detail['nama_dokter']}</td>
                    <td>{$data_detail['keluhan']}</td>
                    <td>{$data_detail['catatan']}</td>
                    <td>{$data_detail['obat']}</td>
                    <td>{$data_detail['biaya_periksa']}</td>
                </tr>
            ";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo '<div class="alert alert-warning">Detail riwayat tidak ditemukan.</div>';
    }
} else {
    echo '<div class="alert alert-warning">Tidak ada riwayat pasien yang dipilih.</div>';
}
?>
