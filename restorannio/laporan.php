<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['waiter', 'kasir', 'owner'])) {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

$transaksi = mysqli_query($conn, "SELECT 
    t.idtransaksi, 
    t.total, 
    t.bayar, 
    t.status,
    p.idpesanan,
    m.namamenu,
    p.jumlah,
    pl.namapelanggan
    FROM transaksi t
    JOIN pesanan p ON t.idpesanan = p.idpesanan
    JOIN menu m ON p.idmenu = m.idmenu
    JOIN pelanggan pl ON p.idpelanggan = pl.idpelanggan
    ORDER BY t.idtransaksi DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
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
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        h2 {
            margin-bottom: 20px;
        }

        .pdf-button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            margin-bottom: 15px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .status {
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Laporan</h2>
    <a href="dashboard_<?php echo $_SESSION['role']; ?>.php">Dashboard</a>
    <?php if ($_SESSION['role'] == 'waiter') : ?>
        <a href="entri_barang.php">Entri Barang</a>
        <a href="entri_order.php">Entri Order</a>
    <?php endif; ?>
    <a href="laporan.php">Laporan</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="card">
        <h2>Riwayat Transaksi</h2>

        <form action="export_pdf.php" method="post">
            <button type="submit" class="pdf-button">Unduh PDF</button>
        </form>

        <table>
            <tr>
                <th>ID Transaksi</th>
                <th>Pesanan</th>
                <th>Jumlah</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Status</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($transaksi)) : ?>
            <tr>
                <td><?= $row['idtransaksi'] ?></td>
                <td><?= $row['namamenu'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['namapelanggan'] ?></td>
                <td>Rp<?= number_format($row['total']) ?></td>
                <td>Rp<?= number_format($row['bayar']) ?></td>
                <td class="status"><?= $row['status'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>
