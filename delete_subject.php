<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include __DIR__ . '/config/db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$subject_id = $_GET['id'] ?? null;

if (!$subject_id) {
    die("ID Mata Kuliah tidak ditemukan.");
}

// PERINGATAN: Kerentanan SQL Injection. Ini hanya untuk tujuan tugas.
// Langkah ini akan menghapus tugas-tugas yang terkait dengan mata kuliah terlebih dahulu
// Jika tidak, akan terjadi error foreign key constraint jika ada tugas yang masih terhubung.
$delete_tasks_query = "DELETE FROM tasks WHERE subject_id = '$subject_id'";
if (!mysqli_query($conn, $delete_tasks_query)) {
    die("Gagal menghapus tugas terkait: " . mysqli_error($conn));
}

// Hapus mata kuliah itu sendiri
$delete_subject_query = "DELETE FROM subjects WHERE id = '$subject_id'";
if (mysqli_query($conn, $delete_subject_query)) {
    header("Location: dashboard.php");
    exit;
} else {
    die("Gagal menghapus mata kuliah: " . mysqli_error($conn));
}
?>