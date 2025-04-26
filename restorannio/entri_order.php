<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'waiter') {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];
$iduser = $_SESSION['iduser'];

$pesanan = mysqli_query($conn, "SELECT p.idpesanan, p.idmenu, p.jumlah, p.idpelanggan, p.iduser, p.idmeja, m.namamenu, pl.namapelanggan, mj.nomeja 
                                FROM pesanan p
                                JOIN menu m ON p.idmenu = m.idmenu
                                JOIN pelanggan pl ON p.idpelanggan = pl.idpelanggan
                                JOIN meja mj ON p.idmeja = mj.idmeja");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idmenu = $_POST['idmenu'];
    $jumlah = $_POST['jumlah'];
    $idpelanggan = $_POST['idpelanggan'];
    $idmeja = $_POST['idmeja'];

    mysqli_query($conn, "INSERT INTO pesanan (idmenu, jumlah, idpelanggan, iduser, idmeja) VALUES ('$idmenu', '$jumlah', '$idpelanggan', '$iduser', '$idmeja')");
    header("Location: entri_order.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pesanan WHERE idpesanan = $id");
    header("Location: entri_order.php");
    exit;
}

$menu = mysqli_query($conn, "SELECT * FROM menu");
$pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan");
$meja = mysqli_query($conn, "SELECT * FROM meja");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Entri Order</title>
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

        input, select, button {
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
    <h2>Waiter Panel</h2>
    <a href="dashboard_waiter.php">Dashboard</a>
    <a href="entri_barang.php">Entri Barang</a>
    <a href="entri_order.php">Entri Order</a>
    <a href="laporan.php">Laporan</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="card">
        <h2>Tambah Pesanan</h2>
        <form method="POST">
            <label>Pilih Menu</label>
            <select name="idmenu" required>
                <option value="">Pilih Menu</option>
                <?php while ($m = mysqli_fetch_assoc($menu)) : ?>
                    <option value="<?= $m['idmenu'] ?>"><?= $m['namamenu'] ?> - Rp<?= number_format($m['harga']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Jumlah</label>
            <input type="number" name="jumlah" required>

            <label>Pilih Pelanggan</label>
            <select name="idpelanggan" required>
                <option value="">Pilih Pelanggan</option>
                <?php while ($p = mysqli_fetch_assoc($pelanggan)) : ?>
                    <option value="<?= $p['idpelanggan'] ?>"><?= $p['namapelanggan'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Pilih Meja</label>
            <select name="idmeja" required>
                <option value="">Pilih Meja</option>
                <?php while ($mj = mysqli_fetch_assoc($meja)) : ?>
                    <option value="<?= $mj['idmeja'] ?>">Meja <?= $mj['nomeja'] ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Simpan</button>
        </form>
    </div>

    <div class="card">
        <h2>Daftar Pesanan</h2>
        <table>
            <tr>
                <th>ID Pesanan</th>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Pelanggan</th>
                <th>Meja</th>
                <th>User</th>
                <th>Aksi</th>
            </tr>
            <?php while ($p = mysqli_fetch_assoc($pesanan)) : ?>
            <tr>
                <td><?= $p['idpesanan'] ?></td>
                <td><?= $p['namamenu'] ?></td>
                <td><?= $p['jumlah'] ?></td>
                <td><?= $p['namapelanggan'] ?></td>
                <td><?= $p['nomeja'] ?></td>
                <td><?= $p['iduser'] ?></td>
                <td><a class="hapus" href="?hapus=<?= $p['idpesanan'] ?>" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>
