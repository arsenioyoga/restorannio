<?php
session_start();

// Validasi session dan akses role
$allowed_roles = ['admin', 'waiter', 'kasir', 'owner'];
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];
$role_cap = ucfirst($role);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard <?= $role_cap ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20230929/pngtree-3d-rendering-of-restaurant-signboard-in-mockup-image_13526069.png');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
            background-color: rgba(255, 255, 255, 0.95);
        }

        .sidebar {
            width: 250px;
            background-color: #212529;
            color: white;
            padding: 20px 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .sidebar a {
            color: #ddd;
            text-decoration: none;
            padding: 12px;
            display: block;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: 0.3s;
            font-size: 16px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #007bff;
            color: white;
        }

        .content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .content h1 {
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
        }

        .content p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .logout-link {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            display: block;
            text-align: center;
        }

        .logout-link:hover {
            text-decoration: underline;
        }

        .sidebar img {
            width: 50px;
            margin-bottom: 20px;
            display: block;
            margin: 0 auto;
        }

        .logout-link {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <div>
                <!-- Logo Sidebar (Optional) -->
                <img src="https://yourlogo.png" alt="Logo" />
                <h2><?= $role_cap ?> Panel</h2>

                <!-- Menampilkan menu sesuai dengan role -->
                <?php if ($role === 'admin') : ?>
                    <a href="entri_barang.php">📦 Entri Barang</a>
                    <a href="entri_meja.php">🪑 Entri Meja</a>
                    <a href="register.php">👥 Kelola User</a>
                <?php elseif ($role === 'waiter') : ?>
                    <a href="entri_barang.php">📦 Entri Barang</a>
                    <a href="entri_order.php">📝 Entri Order</a>
                    <a href="laporan.php">📊 Manajemen Laporan</a>
                <?php elseif ($role === 'kasir') : ?>
                    <a href="entri_transaksi.php">💸 Entri Transaksi</a>
                    <a href="laporan.php">📊 Manajemen Laporan</a>
                <?php elseif ($role === 'owner') : ?>
                    <a href="laporan.php">📊 Manajemen Laporan</a>
                <?php endif; ?>
            </div>
            <a class="logout-link" href="logout.php">🚪 Logout</a>
        </div>

        <div class="content">
            <div class="card">
                <h1>Selamat Datang, <?= htmlspecialchars($nama) ?>!</h1>
                <p>Anda login sebagai <strong><?= $role_cap ?></strong>.</p>
                <p>Gunakan menu di sebelah kiri untuk mengakses fitur yang tersedia untuk peran Anda.</p>
            </div>
        </div>
    </div>
</body>
</html>
