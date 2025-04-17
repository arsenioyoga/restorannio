<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];
$meja = mysqli_query($conn, "SELECT * FROM meja");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomeja = $_POST['nomeja'];
    mysqli_query($conn, "INSERT INTO meja (nomeja) VALUES ('$nomeja')");
    header("Location: entri_meja.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM meja WHERE idmeja = $id");
    header("Location: entri_meja.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Entri Meja</title>
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
            background-color: #1e1e2f;
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
            background-color: #f1f1f1;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
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
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
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
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard_admin.php">Dashboard</a>
    <a href="entri_barang.php">Entri Barang</a>
    <a href="entri_meja.php">Entri Meja</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="card">
        <h2>Tambah Meja</h2>
        <form method="POST">
            <label>Nomor Meja</label>
            <input type="text" name="nomeja" required>
            <button type="submit">Simpan</button>
        </form>
    </div>

    <div class="card">
        <h2>Daftar Meja</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nomor Meja</th>
                <th>Aksi</th>
            </tr>
            <?php while ($m = mysqli_fetch_assoc($meja)) : ?>
            <tr>
                <td><?= $m['idmeja'] ?></td>
                <td><?= $m['nomeja'] ?></td>
                <td><a class="hapus" href="?hapus=<?= $m['idmeja'] ?>" onclick="return confirm('Yakin ingin menghapus meja ini?')">Hapus</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>
