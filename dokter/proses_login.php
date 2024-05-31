<?php
// Lakukan validasi data yang diterima dari permintaan POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap data username dan name
    $username = $_POST['username'];
    $name = $_POST['name'];
    
    // Lakukan pengolahan data sesuai kebutuhan, misalnya menyimpannya ke dalam database
    // Contoh: Simpan data ke dalam database
    // $query = "INSERT INTO users (username, name) VALUES ('$username', '$name')";
    
    // Sekarang kita hanya akan memberikan respons sederhana untuk menunjukkan bahwa data berhasil diterima
    $response = array(
        'status' => 'success',
        'message' => 'Data received successfully.'
    );
    
    // Kembalikan respons dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Jika bukan permintaan POST, kembalikan pesan error
    $response = array(
        'status' => 'error',
        'message' => 'Invalid request method.'
    );
    
    // Kembalikan respons dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
