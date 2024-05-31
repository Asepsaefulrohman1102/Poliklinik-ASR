<?php
$conn = mysqli_connect("localhost", "root", "", "poliklinik");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_POST['poli_id'])) {
    $poli_id = mysqli_real_escape_string($conn, $_POST['poli_id']);
    $query = "SELECT jadwal_periksa.*, dokter.nama_dokter, jadwal_periksa.jam_mulai
              FROM jadwal_periksa 
              JOIN dokter ON jadwal_periksa.id_dokter = dokter.id_dokter 
              WHERE dokter.id_poli = '$poli_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Kueri gagal: " . mysqli_error($conn));
    }

    $jadwal = [];
    while ($data = mysqli_fetch_array($result)) {
        $jadwal[] = ['id' => $data['id'], 'nama_dokter' => $data['nama_dokter'], 'hari' => $data['hari'], 'jam_mulai' => $data['jam_mulai']];
    }

    echo json_encode($jadwal);
}
?>
