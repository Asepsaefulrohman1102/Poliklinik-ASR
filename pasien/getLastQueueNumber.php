<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "poliklinik");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_poli = $_POST['id_poli'];

    $query = "SELECT no_antrian FROM daftar_poli WHERE id_poli = '$id_poli' ORDER BY no_antrian DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $last_queue_number = $row ? $row['no_antrian'] : 0;

    echo $last_queue_number;
}

// Tutup koneksi database
mysqli_close($conn);
?>
