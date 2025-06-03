<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "config/db_connect.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$role = $user['role'];
$username = $user['username']; // Get the username from the session

// Ambil data statistik untuk admin
if ($role == 'admin') {
    $total_users_query = "SELECT COUNT(id) AS total_users FROM users";
    $total_users_result = mysqli_query($conn, $total_users_query);
    $total_users = mysqli_fetch_assoc($total_users_result)['total_users'];

    $total_tasks_query = "SELECT COUNT(id) AS total_tasks FROM tasks";
    $total_tasks_result = mysqli_query($conn, $total_tasks_query);
    $total_tasks = mysqli_fetch_assoc($total_tasks_result)['total_tasks'];

    $total_subjects_query = "SELECT COUNT(id) AS total_subjects FROM subjects";
    $total_subjects_result = mysqli_query($conn, $total_subjects_query);
    $total_subjects = mysqli_fetch_assoc($total_subjects_result)['total_subjects'];

    // Ambil semua mata kuliah untuk ditampilkan di tabel admin
    $all_subjects_query = "SELECT id, name FROM subjects ORDER BY name ASC";
    $all_subjects_result = mysqli_query($conn, $all_subjects_query);
}

// --- START FILTER & SORT LOGIC ---
$status_filter = $_GET['status'] ?? ''; // Default empty
$subject_filter = $_GET['subject_id'] ?? ''; // Default empty
$sort_by = $_GET['sort_by'] ?? 'deadline'; // Default sort by deadline
$sort_order = $_GET['sort_order'] ?? 'ASC'; // Default ascending

// Siapkan klausa WHERE
$where_clauses = [];
if ($role == 'mahasiswa') {
    $where_clauses[] = "tasks.user_id = $user_id";
}

if (!empty($status_filter) && ($status_filter == 'belum' || $status_filter == 'selesai')) {
    $where_clauses[] = "tasks.status = '$status_filter'";
}
if (!empty($subject_filter)) {
    $where_clauses[] = "tasks.subject_id = '$subject_filter'";
}

$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Ambil daftar mata kuliah untuk filter (diperlukan untuk kedua peran)
// Untuk mahasiswa, hanya mata kuliah yang terkait dengan tugas mereka yang ditampilkan di filter,
// namun untuk penambahan mata kuliah, mereka bisa menambahkan mata kuliah baru yang belum ada.
// Kita perlu mengambil semua mata kuliah yang sudah ada untuk dropdown filter.
$subjects_query_filter = "SELECT id, name FROM subjects ORDER BY name ASC";
$subjects_result_filter = mysqli_query($conn, $subjects_query_filter);


// Bangun query utama untuk tugas
if ($role == 'admin') {
    $query = "SELECT tasks.*, users.username, subjects.name as subject 
              FROM tasks 
              JOIN users ON tasks.user_id = users.id 
              JOIN subjects ON tasks.subject_id = subjects.id
              $where_sql
              ORDER BY $sort_by $sort_order";
} else { // mahasiswa
    $query = "SELECT tasks.*, subjects.name as subject 
              FROM tasks 
              JOIN subjects ON tasks.subject_id = subjects.id
              $where_sql
              ORDER BY $sort_by $sort_order";
}

$result = mysqli_query($conn, $query);
// --- END FILTER & SORT LOGIC ---

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EduNote</title>
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

    <main class="container">
        <h1>Dashboard Tugas</h1>
        <p class="welcome-message">Selamat datang, *<?= htmlspecialchars($username) ?>*!</p>

        <?php if ($role == 'admin'): ?>
            <div class="admin-dashboard-overview">
                <div class="stats-cards">
                    <div class="stat-card">
                        <h3>Total Pengguna</h3>
                        <p class="stat-number"><?= $total_users ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Tugas</h3>
                        <p class="stat-number"><?= $total_tasks ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Mata Kuliah</h3>
                        <p class="stat-number"><?= $total_subjects ?></p>
                    </div>
                </div>

                <h3 style="margin-top: 40px; margin-bottom: 20px; color: #4CAF50;">Manajemen Mata Kuliah</h3>
                <div class="dashboard-actions">
                    <a href="add_subject.php" class="button primary-button">Tambah Mata Kuliah Baru (Admin)</a>
                </div>

                <?php if (mysqli_num_rows($all_subjects_result) > 0): ?>
                    <table class="data-table subjects-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Mata Kuliah</th>
                                <th class="actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($subject = mysqli_fetch_assoc($all_subjects_result)): ?>
                                <tr>
                                    <td><?= $subject['id'] ?></td>
                                    <td><?= $subject['name'] ?></td>
                                    <td class="actions">
                                        <a href="edit_subject.php?id=<?= $subject['id'] ?>">Edit</a>
                                        <a href="delete_subject.php?id=<?= $subject['id'] ?>" onclick="return confirm('Yakin ingin menghapus mata kuliah ini? Semua tugas yang terkait juga akan dihapus!')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center">Belum ada mata kuliah yang ditambahkan.</p>
                <?php endif; ?>

                <h3 style="margin-top: 40px; margin-bottom: 20px; color: #4CAF50;">Semua Tugas Pengguna</h3>
            <?php else: /* Untuk Mahasiswa */ ?>
                <div class="dashboard-actions">
                    <a href="add_task.php" class="button primary-button">Tambah Tugas Baru</a>
                    <a href="add_subject_mahasiswa.php" class="button primary-button">Tambah Mata Kuliah Sendiri</a>
                </div>
            <?php endif; ?>

            <form method="GET" class="filter-sort-form">
                <div class="filter-controls-group">
                    <div class="filter-item">
                        <label for="status" class="sr-only">Status:</label>
                        <select id="status" name="status" class="filter-select">
                            <option value="">Status (Semua)</option>
                            <option value="belum" <?= ($status_filter == 'belum' ? 'selected' : '') ?>>Belum Selesai</option>
                            <option value="selesai" <?= ($status_filter == 'selesai' ? 'selected' : '') ?>>Selesai</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="subject_id" class="sr-only">Mata Kuliah:</label>
                        <select id="subject_id" name="subject_id" class="filter-select">
                            <option value="">Mata Kuliah (Semua)</option>
                            <?php mysqli_data_seek($subjects_result_filter, 0); // Reset pointer result set ?>
                            <?php while ($subject = mysqli_fetch_assoc($subjects_result_filter)): ?>
                                <option value="<?= $subject['id'] ?>" <?= ($subject_filter == $subject['id'] ? 'selected' : '') ?>>
                                    <?= $subject['name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="sort_by" class="sr-only">Urutkan Berdasarkan:</label>
                        <select id="sort_by" name="sort_by" class="filter-select">
                            <option value="deadline" <?= ($sort_by == 'deadline' ? 'selected' : '') ?>>Urutkan: Deadline</option>
                            <option value="title" <?= ($sort_by == 'title' ? 'selected' : '') ?>>Urutkan: Judul</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="sort_order" class="sr-only">Urutan:</label>
                        <select id="sort_order" name="sort_order" class="filter-select">
                            <option value="ASC" <?= ($sort_order == 'ASC' ? 'selected' : '') ?>>Urutan: Ascending</option>
                            <option value="DESC" <?= ($sort_order == 'DESC' ? 'selected' : '') ?>>Urutan: Descending</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="button primary-button filter-button">Terapkan</button>
                </div>
            </form>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <?php if ($role == 'admin') echo "<th>Pengguna</th>"; ?>
                            <th>Mata Kuliah</th>
                            <th>Judul Tugas</th>
                            <th>Deskripsi</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th class="actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <?php if ($role == 'admin') echo "<td>{$row['username']}</td>"; ?>
                                <td><?= $row['subject'] ?></td>
                                <td><?= $row['title'] ?></td>
                                <td><?= $row['description'] ?></td>
                                <td><?= $row['deadline'] ?></td>
                                <td><?= $row['status'] ?></td>
                                <td class="actions">
                                    <a href="edit_task.php?id=<?= $row['id'] ?>">Edit</a>
                                    <a href="delete_task.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Tidak ada tugas/mata kuliah untuk ditampilkan dengan filter ini.</p>
            <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 EduNote. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>