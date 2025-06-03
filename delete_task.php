<?php
session_start();
include __DIR__ . '/config/db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$role = $user['role'];

$id = $_GET['id'];
$query = "SELECT * FROM tasks WHERE id = $id";
$result = mysqli_query($conn, $query);
$task = mysqli_fetch_assoc($result);

// Cek kepemilikan
if ($role == 'mahasiswa' && $task['user_id'] != $user_id) {
    die("Akses ditolak.");
}

mysqli_query($conn, "DELETE FROM tasks WHERE id = $id");
header("Location: dashboard.php");
exit;
?>
