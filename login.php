<?php
session_start();
include __DIR__ . '/config/db_connect.php';

$message = "";

if (isset($_POST['submit_login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; 

    $sql = "SELECT id, username, password, role FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($password == $user['password']) { 
            $_SESSION['user'] = $user; 
            header("Location: dashboard.php"); 
            exit();
        } else {
            $message = "<div class='message error'>Username atau password salah.</div>";
        }
    } else {
        $message = "<div class='message error'>Username atau password salah.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EduNote</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="logged-in-background">
    <header class="header">
        <div class="container">
            <div class="brand-logo">
                <a href="index.php">
                    <span class="logo-icon">📚</span> EduNote
                </a>
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

    <main class="container login-form-container">
        <h2>Login</h2>
        <?php echo $message; ?>
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="submit_login">Login</button>
        </form>
        <p class="text-center margin-top-15 font-small">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        <p class="text-center margin-top-15 font-small">Untuk demonstrasi: Username <code>admin</code>, Password <code>admin123</code></p>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 EduNote. All rights reserved.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
    <script>
        // Tambahkan script ini di setiap halaman yang memiliki navbar dinamis
        // Untuk menandai link aktif berdasarkan URL saat ini
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.main-nav ul li a');

            navLinks.forEach(link => {
                const linkPath = link.getAttribute('href').split('/').pop();
                if (linkPath === currentPath) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active'); // Pastikan tidak ada class active yang tersisa dari halaman lain
                }
            });
        });
    </script>
</body>
</html>