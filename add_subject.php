<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include __DIR__ . "/config/db_connect.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$message = ""; // Tambahkan variabel message untuk feedback

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']); // Hapus spasi di awal/akhir

    if (empty($name)) {
        $message = '<div class="message error">Nama mata kuliah tidak boleh kosong.</div>';
    } else {
        try {
            // MENGGUNAKAN PREPARED STATEMENT UNTUK KEAMANAN DAN PENANGANAN ERROR DUPLIKASI
            $stmt = $conn->prepare("INSERT INTO subjects (name) VALUES (?)");
            $stmt->bind_param("s", $name); // 's' berarti string
            
            $stmt->execute(); // Jalankan statement
            $message = '<div class="message success">Mata kuliah berhasil ditambahkan.</div>';
            // Opsional: Redirect ke dashboard setelah berhasil
            // header("Location: dashboard.php");
            // exit;
        } catch (mysqli_sql_exception $e) {
            // Tangkap eksepsi SQL
            if ($e->getCode() == 1062) { // Error code 1062 adalah untuk "Duplicate entry"
                $message = '<div class="message error">Nama mata kuliah sudah ada.</div>';
            } else {
                $message = '<div class="message error">Gagal menambahkan mata kuliah: ' . $e->getMessage() . '</div>';
            }
        } finally {
            // Pastikan statement ditutup terlepas dari error atau tidak
            if (isset($stmt)) {
                $stmt->close(); 
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mata Kuliah - EduNote</title>
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
        <h2>Tambah Mata Kuliah</h2>
        <?php echo $message; ?>
        <form method="POST">
            <label for="name">Nama Mata Kuliah:</label>
            <input type="text" id="name" name="name" placeholder="Nama Mata Kuliah" required>
            <button type="submit">Simpan</button>
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