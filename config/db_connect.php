<?php
$host = "sql211.infinityfree.com";
$user = "if0_39146366";
$pass = "28g4Pw91sJ";
$dbname = "if0_39146366_ProjectEdu";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
