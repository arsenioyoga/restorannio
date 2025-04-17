<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'waiter'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];
$menu = mysqli_query($conn, "SELECT * FROM menu");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namamenu = $_POST['namamenu'];
    $harga = $_POST['harga'];
    mysqli_query($conn, "INSERT INTO menu (namamenu, harga) VALUES ('$namamenu', '$harga')");
    header("Location: entri_barang.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM menu WHERE idmenu = $id");
    header("Location: entri_barang.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Entri Barang</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            background-color: #ffffff;
            color: #333;
        }

        .sidebar {
            width: 240px;
            background-color: #343a40;
            padding: 30px 20px;
            min-height: 100vh;
            color: white;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: #ccc;
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 8px;
        }

        .sidebar a:hover {
            background-color: #007bff;
            color: white;
        }

        .main {
            flex: 1;
            padding: 40px;
        }

        .card {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: white;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .hapus {
            color: #dc3545;
            text-decoration: none;
        }

        .hapus:hover {
            text-decoration: underline;
        }

        .link-meja {
            margin-top: 20px;
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
        }

        .link-meja:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2><?= ucfirst($role) ?> Panel</h2>
    <a href="dashboard_admin.php">Dashboard</a>
    <a href="entri_barang.php">Entri Barang</a>
    <?php if ($role === 'waiter') : ?>
        <a href="entri_order.php">Entri Order</a>
    <?php endif; ?>
    <?php if ($role === 'admin') : ?>
        <a href="entri_meja.php">Entri Meja</a>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="card">
        <h2>Tambah Menu</h2>
        <form method="POST">
            <label>Nama Menu</label>
            <input type="text" name="namamenu" required>
            <label>Harga</label>
            <input type="number" name="harga" required>
            <button type="submit">Simpan</button>
        </form>
    </div>

    <div class="card">
        <h2>Daftar Menu</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
            <?php while ($m = mysqli_fetch_assoc($menu)) : ?>
            <tr>
                <td><?= $m['idmenu'] ?></td>
                <td><?= $m['namamenu'] ?></td>
                <td>Rp<?= number_format($m['harga']) ?></td>
                <td><a class="hapus" href="?hapus=<?= $m['idmenu'] ?>" onclick="return confirm('Yakin?')">Hapus</a></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <?php if ($role === 'admin') : ?>
            <a class="link-meja" href="entri_meja.php">Ke Entri Meja</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
