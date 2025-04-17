<?php
session_start();
include 'koneksi.php';

// Hanya kasir yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];

// Ambil daftar ID pesanan
$pesanan = mysqli_query($conn, "SELECT idpesanan FROM pesanan");

// Variabel transaksi yang baru dimasukkan
$transaksiBaru = null;

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpesanan = $_POST['idpesanan'];
    $total = $_POST['total'];
    $bayar = $_POST['bayar'];

    // Simpan ke tabel transaksi
    mysqli_query($conn, "INSERT INTO transaksi (idpesanan, total, bayar) VALUES ('$idpesanan', '$total', '$bayar')");

    // Ambil transaksi yang baru dimasukkan
    $transaksiBaru = [
        'idtransaksi' => mysqli_insert_id($conn), // ID transaksi yang baru dimasukkan
        'idpesanan' => $idpesanan,
        'total' => $total,
        'bayar' => $bayar
    ];

    // Simpan ke tabel laporan
    $tanggal = date('Y-m-d');
    $keterangan = "Transaksi Pesanan #$idpesanan - Total: Rp" . number_format($total) . ", Bayar: Rp" . number_format($bayar);
    mysqli_query($conn, "INSERT INTO laporan (keterangan, tanggal) VALUES ('$keterangan', '$tanggal')");
}

// Jika ada transaksi yang dihapus
if (isset($_GET['hapus'])) {
    $idtransaksi = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM transaksi WHERE idtransaksi = $idtransaksi");
    header("Location: entri_transaksi.php"); // Refresh halaman setelah penghapusan
    exit;
}

// Ambil semua riwayat transaksi (tanpa transaksi yang baru dimasukkan)
$transaksi = mysqli_query($conn, "
    SELECT t.*, p.idpesanan 
    FROM transaksi t 
    JOIN pesanan p ON t.idpesanan = p.idpesanan
    WHERE t.idtransaksi != '" . ($transaksiBaru ? $transaksiBaru['idtransaksi'] : '') . "'
    ORDER BY t.idtransaksi DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Entri Transaksi</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            background-color: #f4f4f4;
            color: #333;
        }

        .sidebar {
            width: 220px;
            background-color: #343a40;
            padding: 20px;
            min-height: 100vh;
            color: white;
        }

        .sidebar h2 {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            text-decoration: none;
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
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        h2 {
            margin-top: 0;
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
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .hapus {
            color: #ff6b6b;
        }

        .hapus:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Kasir Panel</h2>
    <a href="dashboard_admin.php">Dashboard</a>
    <a href="entri_transaksi.php">Entri Transaksi</a>
    <a href="laporan.php">Laporan</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="card">
        <h2>Tambah Transaksi</h2>
        <form method="POST">
            <label>ID Pesanan</label>
            <select name="idpesanan" required>
                <option value="">-- Pilih --</option>
                <?php while($row = mysqli_fetch_assoc($pesanan)) : ?>
                    <option value="<?= $row['idpesanan'] ?>"><?= $row['idpesanan'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Total</label>
            <input type="number" name="total" required>

            <label>Bayar</label>
            <input type="number" name="bayar" required>

            <button type="submit">Simpan</button>
        </form>
    </div>

    <?php if ($transaksiBaru): ?>
    <div class="card">
        <h2>Transaksi Terbaru</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>ID Pesanan</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $transaksiBaru['idtransaksi'] ?></td>
                    <td><?= $transaksiBaru['idpesanan'] ?></td>
                    <td>Rp<?= number_format($transaksiBaru['total']) ?></td>
                    <td>Rp<?= number_format($transaksiBaru['bayar']) ?></td>
                    <td><a class="hapus" href="?hapus=<?= $transaksiBaru['idtransaksi'] ?>" onclick="return confirm('Yakin?')">Hapus</a></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="card">
        <h2>Riwayat Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>ID Pesanan</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($transaksi)) : ?>
                <tr>
                    <td><?= $row['idtransaksi'] ?></td>
                    <td><?= $row['idpesanan'] ?></td>
                    <td>Rp<?= number_format($row['total']) ?></td>
                    <td>Rp<?= number_format($row['bayar']) ?></td>
                    <td><a class="hapus" href="?hapus=<?= $row['idtransaksi'] ?>" onclick="return confirm('Yakin?')">Hapus</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
