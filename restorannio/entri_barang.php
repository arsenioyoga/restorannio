<?php
session_start();
include 'koneksi.php';

// Cek role login (hanya admin dan waiter yang bisa akses)
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'waiter'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

// Proses tambah menu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namamenu = $_POST['namamenu'];
    $harga = $_POST['harga'];

    $query = "INSERT INTO menu (namamenu, harga) VALUES ('$namamenu', '$harga')";
    mysqli_query($conn, $query);
    echo "<script>alert('Menu berhasil ditambahkan!'); window.location.href='entri_barang.php';</script>";
}

// Proses hapus menu
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $hapus = mysqli_query($conn, "DELETE FROM menu WHERE idmenu = '$id'");

    if ($hapus) {
        echo "<script>alert('Menu berhasil dihapus!'); window.location.href='entri_barang.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus menu!');</script>";
    }
}

// Ambil data menu
$data_menu = mysqli_query($conn, "SELECT * FROM menu ORDER BY idmenu DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Entri Menu</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            display: flex;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            color: #ecf0f1;
        }

        .sidebar ul {
            list-style: none;
            padding-left: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        .content {
            margin-left: 250px;
            padding: 40px;
            width: 100%;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: auto;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            font-weight: bold;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            margin-top: 40px;
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        a.hapus {
            color: red;
            text-decoration: none;
        }

        a.hapus:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Dashboard</h2>
    <ul>
        <li><a href="dashboard_admin.php">Dashboard</a></li>
        <li><a href="entri_barang.php">Entri Menu</a></li>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <li><a href="entri_meja.php">Entri Meja</a></li>
            <li><a href="kelola_user.php">Kelola User</a></li>
        <?php endif; ?>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
    <div class="container">
        <h2>Entri Menu</h2>

        <form method="POST" action="">
            <label for="namamenu">Nama Menu:</label>
            <input type="text" name="namamenu" id="namamenu" required>

            <label for="harga">Harga:</label>
            <input type="number" name="harga" id="harga" required>

            <button type="submit">Tambah Menu</button>
        </form>

        <h3 style="margin-top: 40px;">Daftar Menu</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($data_menu)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['namamenu']) ?></td>
                        <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td><a href="entri_barang.php?hapus=<?= $row['idmenu'] ?>" class="hapus" onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a></td>
                    </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($data_menu) === 0): ?>
                    <tr><td colspan="4">Belum ada menu.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
