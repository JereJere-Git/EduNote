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

$message = "";
$subject_id = $_GET['id'] ?? null;

if (!$subject_id) {
    die("ID Mata Kuliah tidak ditemukan.");
}

// Ambil data mata kuliah yang akan diedit
$query_subject = "SELECT id, name FROM subjects WHERE id = '$subject_id'"; // Tidak aman, tetapi sesuai permintaan
$result_subject = mysqli_query($conn, $query_subject);
$subject = mysqli_fetch_assoc($result_subject);

if (!$subject) {
    die("Mata Kuliah tidak ditemukan.");
}

// Proses update saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST['name'];

    // Update mata kuliah
    $update_query = "UPDATE subjects SET name = '$new_name' WHERE id = '$subject_id'"; // Tidak aman, tetapi sesuai permintaan
    if (mysqli_query($conn, $update_query)) {
        $message = '<div class="message success">Mata kuliah berhasil diperbarui.</div>';
        // Opsional: Redirect kembali ke dashboard setelah update
        // header("Location: dashboard.php");
        // exit;
    } else {
        $message = '<div class="message error">Gagal memperbarui mata kuliah: ' . mysqli_error($conn) . '</div>';
    }
    // Refresh data mata kuliah setelah update agar form menampilkan nilai terbaru
    $result_subject = mysqli_query($conn, $query_subject);
    $subject = mysqli_fetch_assoc($result_subject);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mata Kuliah - EduNote</title>
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
        <h2>Edit Mata Kuliah</h2>
        <?php echo $message; ?>
        <form method="POST">
            <label for="name">Nama Mata Kuliah:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($subject['name']) ?>" required>
            <button type="submit">Perbarui Mata Kuliah</button>
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