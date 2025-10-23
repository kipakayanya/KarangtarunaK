<?php
// db.php - koneksi ke MySQL (port 3307)
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = ''; // ganti jika root punya password
$DB_NAME = 'db_karangtaruna';


$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME,);
if ($conn->connect_errno) {
    die("Koneksi DB gagal: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>