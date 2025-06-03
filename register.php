<?php
session_start();
include __DIR__ . '/config/db_connect.php';

$message = "";

if (isset($_POST['submit_register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    // --- Perubahan di sini: Menggunakan plain text password ---
    $password = $conn->real_escape_string($_POST['password']); // Kata sandi disimpan sebagai plain text
    // --------------------------------------------------------
    $role = $conn->real_escape_string($_POST['role']);

    // Cek apakah username sudah ada
    $check_user_sql = "SELECT id FROM users WHERE username = '$username'";
    $check_user_result = $conn->query($check_user_sql);

    if ($check_user_result->num_rows > 0) {
        $message = "<div class='error-message'>Username sudah ada. Pilih username lain.</div>";
    } else {
        // Query INSERT disesuaikan untuk menyimpan plain text password
        $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            $message = "<div class='success-message'>Registrasi berhasil! Silakan <a href='login.php'>Login</a>.</div>";
        } else {
            $message = "<div class='error-message'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EduNote</title>
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

    <main class="container register-form-container">
        <h2>Daftar Akun</h2>
        <?php echo $message; ?>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Daftar Sebagai:</label>
            <select id="role" name="role">
                <option value="mahasiswa">Mahasiswa</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit" name="submit_register">Daftar</button>
        </form>
        <p style="margin-top: 15px; font-size: 0.9em; text-align: center;">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 EduNote. All rights reserved.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>