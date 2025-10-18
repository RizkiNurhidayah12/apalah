<?php

//konfigurasi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "frontend_db";

//buat koneksi
$mysqli = new mysqli($host, $user, $pass, $db);

//cek koneksi
if ($mysqli->connect_errno) {
    die("Koneksi gagal: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}   

$mysqli->set_charset("utf8mb4");
?>