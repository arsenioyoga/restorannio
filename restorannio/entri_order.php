<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['iduser']) || !in_array($_SESSION['role'], ['waiter'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

$iduser = $_SESSION['iduser'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idmenu = $_POST['idmenu'];
    $idpelanggan = $_POST['idpelanggan'];
    $jumlah = $_POST['jumlah'];

    $query = "INSERT INTO pesanan (idmenu, idpelanggan, jumlah, iduser)
              VALUES ('$idmenu', '$idpelanggan', '$jumlah', '$iduser')";
    mysqli_query($conn, $query);
    header("Location: entri_order.php");
    exit;
}

$menu = mysqli_query($conn, "SELECT * FROM menu");
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
$pesanan = mysqli_query($conn, "
    SELECT p.*, m.namamenu, pl.namapelanggan
    FROM pesanan p
    JOIN menu m ON p.idmenu = m.idmenu
    JOIN pelanggan pl ON p.idpelanggan = pl.idpelanggan
    ORDER BY p.idpesanan DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Entri Order</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
        }

        .sidebar {
            width: 220px;
            background-color: #2d3436;
            color: white;
            min-height: 100vh;
            padding-top: 30px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding-left: 0;
        }

        .sidebar ul li {
            padding: 12px 20px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: white;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #636e72;
        }

        .main {
            margin-left: 220px;
            padding: 30px;
            width: 100%;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 900px;
            margin: auto;
        }

        h2 {
            margin-bottom: 25px;
            text-align: center;
        }

        form label {
            display: block;
            margin-top: 15px;
        }

        form select, form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #0984e3;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #74b9ff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #0984e3;
            color: white;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Waiter Panel</h2>
    <ul>
        <li><a href="dashboard_admin.php">Dashboard</a></li>
        <li><a href="entri_barang.php">Entri Menu</a></li>
        <li><a href="entri_meja.php">Entri Meja</a></li>
        <li><a href="entri_order.php">Entri Order</a></li>
        <li><a href="manajemen_laporan.php">Laporan</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main">
    <div class="card">
        <h2>Form Entri Order</h2>
        <form method="POST">
            <label for="idmenu">Pilih Menu</label>
            <select name="idmenu" required>
                <option value="">-- Pilih Menu --</option>
                <?php while ($m = mysqli_fetch_assoc($menu)) : ?>
                    <option value="<?= $m['idmenu'] ?>"><?= $m['namamenu'] ?> - Rp<?= number_format($m['harga']) ?></option>
                <?php endwhile; ?>
            </select>

            <label for="idpelanggan">Pilih Pelanggan</label>
            <select name="idpelanggan" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php while ($p = mysqli_fetch_assoc($pelanggan)) : ?>
                    <option value="<?= $p['idpelanggan'] ?>"><?= $p['namapelanggan'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="jumlah">Jumlah</label>
            <input type="number" name="jumlah" min="1" required>

            <button type="submit">Simpan Pesanan</button>
        </form>

        <h3 style="margin-top: 40px;">Daftar Pesanan</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Menu</th>
                    <th>Pelanggan</th>
                    <th>Jumlah</th>
                    <th>ID User</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($pesanan)) : ?>
                    <tr>
                        <td><?= $row['idpesanan'] ?></td>
                        <td><?= $row['namamenu'] ?></td>
                        <td><?= $row['namapelanggan'] ?></td>
                        <td><?= $row['jumlah'] ?></td>
                        <td><?= $row['iduser'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
