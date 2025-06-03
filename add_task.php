<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "config/db_connect.php"; // Menggunakan path yang Anda inginkan

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'mahasiswa') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$message = ""; // Tambahkan variabel message

// Mengambil mata kuliah (asumsi dari tabel 'subjects' dan kolom 'name')
$subjects_query = "SELECT id, name FROM subjects ORDER BY name ASC"; // Urutkan untuk tampilan yang lebih baik
$subjects_result = mysqli_query($conn, $subjects_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = $_POST['subject_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $deadline = $_POST['deadline'];
    
    // Validasi input
    if (empty($subject_id) || empty($title) || empty($deadline)) {
        $message = '<div class="message error">Mata kuliah, judul, dan deadline tidak boleh kosong.</div>';
    } else {
        // MENGGUNAKAN PREPARED STATEMENTS UNTUK KEAMANAN
        $query = "INSERT INTO tasks (user_id, subject_id, title, description, deadline, status) 
                  VALUES (?, ?, ?, ?, ?, 'belum')"; // 'belum' adalah status default

        $stmt = $conn->prepare($query);
        // 'iisss' berarti integer (user_id), integer (subject_id), string (title), string (description), string (deadline)
        $stmt->bind_param("iisss", $user_id, $subject_id, $title, $description, $deadline);
        
        if ($stmt->execute()) {
            $message = '<div class="message success">Tugas berhasil ditambahkan!</div>';
            // Opsional: Redirect ke dashboard setelah berhasil
            // header("Location: dashboard.php");
            // exit;
        } else {
            $message = '<div class="message error">Gagal menambahkan tugas: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas - EduNote</title>
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
        <h2>Tambah Tugas</h2>
        <?php echo $message; ?>
        <form method="POST">
            <label for="subject_id">Mata Kuliah:</label>
            <select id="subject_id" name="subject_id" required>
                <?php if (mysqli_num_rows($subjects_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($subjects_result)): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">Belum ada mata kuliah. Tambahkan mata kuliah terlebih dahulu.</option>
                <?php endif; ?>
            </select>

            <label for="title">Judul Tugas:</label>
            <input type="text" id="title" name="title" placeholder="Judul Tugas" required>

            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description" placeholder="Deskripsi Tugas"></textarea>

            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" required>

            <button type="submit">Simpan Tugas</button>
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