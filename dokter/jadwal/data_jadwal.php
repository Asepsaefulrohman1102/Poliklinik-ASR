<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "poliklinik");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_GET['nama_dokter'])) {
    $nama_dokter = mysqli_real_escape_string($conn, $_GET['nama_dokter']);
    $query = mysqli_query($conn, "SELECT dokter.*, jadwal_periksa.* FROM jadwal_periksa JOIN dokter ON jadwal_periksa.id_dokter = dokter.id_dokter WHERE dokter.nama_dokter='$nama_dokter'");
    $data = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
}
?>
