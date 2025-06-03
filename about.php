<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - EduNote</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Gaya tambahan untuk kartu anggota */
        .card {
            display: block; /* Tetap blok agar konten di dalamnya menumpuk (foto di atas, teks di bawah) */
            text-align: center; /* Menengahkan teks dan gambar di dalam kartu */
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 12px;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            
            /* Properti untuk Flexbox pada kartu */
            flex-basis: 300px; /* Lebar dasar untuk setiap kartu (sesuaikan jika perlu) */
            flex-shrink: 0; /* Mencegah kartu mengecil di bawah lebar dasarnya */
            margin-bottom: 30px; /* Jarak antar kartu saat wrap atau untuk konsistensi */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin: 0 auto 20px auto; /* Tengahkan foto dan beri jarak bawah */
            border: 4px solid #f0f0f0;
        }

        .card h2 {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 8px;
        }

        .card p {
            font-size: 1.1em;
            color: #555;
            margin-top: 0;
        }

        /* Penyesuaian untuk container agar kartu berjajar sebaris */
        .card-list-container {
            display: flex; /* Mengatur item di dalam container berjajar horizontal */
            flex-wrap: wrap; /* Mengizinkan item untuk pindah baris jika layar terlalu kecil */
            justify-content: center; /* Menengahkan item secara horizontal */
            gap: 30px; /* Memberi jarak antar kartu */
            max-width: 1000px; /* Lebar maksimum container agar muat 3 kartu + jarak */
            margin: 30px auto; /* Tengahkan container */
            padding: 0 15px;
        }

        .intro-text {
            text-align: center; /* Mengembalikan teks intro ke tengah */
            margin-bottom: 40px;
            font-size: 1.1em;
            color: #666;
        }

        h1 {
            text-align: center; /* Pastikan judul utama juga di tengah */
        }
    </style>
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

    <main class="container card-list-container">
        <h1>Tentang Kami: Tim di Balik EduNote</h1>
        <p class="intro-text">Kami adalah tim pengembang di balik EduNote, sebuah platform yang didedikasikan untuk mempermudah pengalaman belajarmu. Kenali kami lebih dekat:</p>

        <div class="card">
            <img src="images/WhatsApp Image 2025-05-07 at 22.41.56_ec373773.jpg" alt="Foto Jeremia Lelemboto">
            <h2>Jeremia Lelemboto</h2>
            <p><strong>NIM:</strong> 230211060037</p>
        </div>

        <div class="card">
            <img src="images/WhatsApp Image 2025-06-03 at 20.33.36_796fd3ea.jpg" alt="Foto Joy Timoty Mantik">
            <h2>Joy Timoty Mantik</h2>
            <p><strong>NIM:</strong> 220211060363</p>
        </div>
        
        <div class="card">
            <img src="images/WhatsApp Image 2025-06-03 at 20.49.21_0581108e.jpg" alt="Foto Alya Lumombo">
            <h2>Alya Lumombo</h2>
            <p><strong>NIM:</strong> 220211060144</p>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 EduNote. All rights reserved.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>