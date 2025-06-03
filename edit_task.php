<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "config/db_connect.php"; // Menggunakan path yang Anda inginkan

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$role = $user['role'];
$message = ""; // Tambahkan variabel message

$id = $_GET['id'] ?? null; // Gunakan null coalescing operator untuk menghindari undefined index

if (!$id) {
    die("ID Tugas tidak ditemukan.");
}

// Ambil data tugas
// PERINGATAN: Rentan SQL Injection karena $id dimasukkan langsung ke query
$query_task = "SELECT * FROM tasks WHERE id = $id";
$result_task = mysqli_query($conn, $query_task);
$task = mysqli_fetch_assoc($result_task);

// Jika tugas tidak ditemukan
if (!$task) {
    die("Tugas tidak ditemukan.");
}

// Cek kepemilikan (mahasiswa hanya bisa edit tugasnya sendiri)
// Admin bisa mengedit semua tugas
if ($role == 'mahasiswa' && $task['user_id'] != $user_id) {
    die("Akses ditolak.");
}

// Ambil daftar mata kuliah
$subjects_query = "SELECT id, name FROM subjects"; // Asumsi nama tabel 'subjects'
$subjects_result = mysqli_query($conn, $subjects_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = $_POST['subject_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];

    // Update tugas
    // PERINGATAN: Rentan SQL Injection! Tidak ada sanitasi atau Prepared Statements.
    $update_query = "UPDATE tasks SET subject_id='$subject_id', title='$title', description='$description', deadline='$deadline', status='$status' WHERE id=$id";
    if (mysqli_query($conn, $update_query)) {
        $message = '<div class="message success">Tugas berhasil diperbarui.</div>';
        // Opsional: Refresh data tugas setelah update agar form menampilkan nilai terbaru
        $result_task = mysqli_query($conn, $query_task);
        $task = mysqli_fetch_assoc($result_task);
        // header("Location: dashboard.php"); // Bisa redirect atau tampilkan pesan
        // exit;
    } else {
        $message = '<div class="message error">Gagal memperbarui tugas: ' . mysqli_error($conn) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas - EduNote</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="logged-in-background">
    <header class="header">
        <div class="container">
            <div class="brand-logo">
                <a href="index.php">
                    <span class="logo-icon">📚</span> EduNote </a>
            </div>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
            <div class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <main class="container form-container">
        <h2>Edit Tugas</h2>
        <?php echo $message; ?>
        <form method="POST">
            <label for="subject_id">Mata Kuliah:</label>
            <select id="subject_id" name="subject_id" required>
                <?php mysqli_data_seek($subjects_result, 0); // Reset pointer result set ?>
                <?php while ($row = mysqli_fetch_assoc($subjects_result)): ?>
                    <option value="<?= $row['id'] ?>" <?= ($row['id'] == $task['subject_id'] ? 'selected' : '') ?>>
                        <?= $row['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="title">Judul Tugas:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>

            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description"><?= htmlspecialchars($task['description']) ?></textarea>

            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" value="<?= htmlspecialchars($task['deadline']) ?>" required>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="belum" <?= ($task['status'] == 'belum' ? 'selected' : '') ?>>Belum Selesai</option>
                <option value="selesai" <?= ($task['status'] == 'selesai' ? 'selected' : '') ?>>Selesai</option>
            </select>

            <button type="submit">Perbarui Tugas</button>
        </form>
        <p class="text-center" style="margin-top: 15px;">
            <a href="dashboard.php">Kembali ke Dashboard</a>
        </p>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 EduNote. All rights reserved.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>