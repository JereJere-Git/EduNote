<?php
session_start();
include "config/db_connect.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$user_id = $_GET['id'] ?? null;
$user_data = null;
$error = '';
$success = '';

if ($user_id) {
    // Fetch user data
    $stmt = mysqli_prepare($conn, "SELECT id, username, role FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user_data = mysqli_fetch_assoc($result);

    if (!$user_data) {
        $error = "Pengguna tidak ditemukan.";
    }
} else {
    $error = "ID pengguna tidak diberikan.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_data) {
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_role = mysqli_real_escape_string($conn, $_POST['role']);
    $new_password = $_POST['password'];

    if (empty($new_username) || empty($new_role)) {
        $error = "Username dan peran tidak boleh kosong.";
    } else {
        $update_query = "UPDATE users SET username = ?, role = ? WHERE id = ?";
        $params = [$new_username, $new_role, $user_id];
        $types = "ssi";

        if (!empty($new_password)) {
            // PERHATIAN: Saya menambahkan password_hash() untuk keamanan.
            // Jika database Anda menyimpan password tanpa hash, Anda mungkin perlu menyesuaikan ini.
            // Tetapi sangat disarankan untuk selalu mengenkripsi password.
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET username = ?, role = ?, password = ? WHERE id = ?";
            $params = [$new_username, $new_role, $hashed_password, $user_id];
            $types = "sssi";
        }

        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, $types, ...$params);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Data pengguna berhasil diperbarui.";
            // Refresh user data after update
            $stmt = mysqli_prepare($conn, "SELECT id, username, role FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user_data = mysqli_fetch_assoc($result);
        } else {
            $error = "Gagal memperbarui pengguna: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - EduNote</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="brand-logo">
                <a href="index.php">
                    <span class="logo-icon">📚</span> EduNote
                </a>
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

    <main class="container form-container"> <h2>Edit Pengguna</h2> <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php endif; ?>

        <?php if ($user_data): ?>
            <form action="edit_user.php?id=<?= $user_data['id'] ?>" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user_data['username']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="admin" <?= ($user_data['role'] === 'admin' ? 'selected' : '') ?>>Admin</option>
                        <option value="mahasiswa" <?= ($user_data['role'] === 'mahasiswa' ? 'selected' : '') ?>>Mahasiswa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Password (kosongkan jika tidak ingin diubah):</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <button type="submit" class="button primary-button">Update Pengguna</button>
                    <a href="dashboard.php" class="button primary-button">Batal</a>
                </div>
            </form>
        <?php elseif (!$error): ?>
            <p class="text-center">Memuat data pengguna...</p>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 EduNote. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>