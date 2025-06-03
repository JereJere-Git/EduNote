<?php
session_start();

// Redirect ke dashboard jika sudah login
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

$message = "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - EduNote</title>
    <link rel="stylesheet" href="css/style.css">
    </head>
<body>
    <header class="header">
        <div class="container">
            <div class="brand-logo">
                <a href="index.php">
                    <span class="logo-icon">📚</span> EduNote </a>
            </div>
            <nav class="main-nav" id="mainNav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </nav>
            <div class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <main>
        <section class="hero-home-section">
            <div class="container text-center">
                <h2 class="hero-home-title">Kelola Tugas dan Mata Kuliah Anda dengan Mudah!</h2>
                <p class="hero-home-description">EduNote adalah platform yang dirancang untuk membantu mahasiswa dan pengajar mengelola tugas, mata kuliah, dan catatan dengan lebih efisien.</p>
                <div class="hero-home-buttons">
                    <a href="login.php" class="button primary-button">Mulai Sekarang (Login)</a>
                    <a href="register.php" class="button secondary-button">Daftar Akun Baru</a>
                </div>
            </div>
        </section>

        <section class="features-section">
            <div class="container">
                <h3 class="section-heading">Fitur Utama EduNote</h3>
                <div class="features-grid">
                    <div class="feature-item reveal-item">
                        <span class="icon">📝</span> <h4>Pencatatan Tugas</h4>
                        <p>Catat dan lacak tugas-tugas kuliah dengan mudah, atur prioritas, dan jangan pernah lewatkan deadline penting.</p>
                    </div>
                    <div class="feature-item reveal-item">
                        <span class="icon">📚</span> <h4>Manajemen Mata Kuliah</h4>
                        <p>Kelola daftar mata kuliah Anda, dan atur semua tugas serta materi berdasarkan mata kuliah masing-masing.</p>
                    </div>
                    <div class="feature-item reveal-item">
                        <span class="icon">📱</span> <h4>Akses Multi-Perangkat</h4>
                        <p>Akses EduNote dari mana saja, kapan saja. Tetap terhubung dengan kegiatan akademik Anda melalui berbagai perangkat.</p>
                    </div>
                    <div class="feature-item reveal-item">
                        <span class="icon">🧑‍🎓👩‍🏫</span> <h4>Dukungan Multi-Peran</h4>
                        <p>EduNote dirancang untuk mendukung peran mahasiswa dan admin, memungkinkan pengelolaan tugas yang komprehensif dan terorganisir.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 EduNote. All rights reserved.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>