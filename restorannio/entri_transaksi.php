<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    echo "<script>alert('Akses ditolak!'); window.location.href='login.php';</script>";
    exit;
}

$pesan = '';
$kembalian = null;

// Hapus transaksi
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM transaksi WHERE idtransaksi = $id");
    header("Location: entri_transaksi.php");
    exit;
}

// Simpan transaksi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpesanan = $_POST['idpesanan'];
    $total = $_POST['total'];
    $bayar = $_POST['bayar'];

    if ($bayar < $total) {
        $pesan = "Uang bayar kurang!";
    } else {
        $status = 'Sudah Dibayar';
        mysqli_query($conn, "INSERT INTO transaksi (idpesanan, total, bayar, status) VALUES ('$idpesanan', '$total', '$bayar', '$status')");
        $kembalian = $bayar - $total;
    }
}

// Data pesanan
$pesanan = mysqli_query($conn, "SELECT p.idpesanan, m.namamenu, p.jumlah, m.harga, (p.jumlah * m.harga) AS total 
                                FROM pesanan p 
                                JOIN menu m ON p.idmenu = m.idmenu");

// Riwayat transaksi
$riwayat = mysqli_query($conn, "SELECT t.*, m.namamenu 
                                FROM transaksi t 
                                JOIN pesanan p ON t.idpesanan = p.idpesanan 
                                JOIN menu m ON p.idmenu = m.idmenu 
                                ORDER BY t.idtransaksi DESC");
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

        .pesan {
            padding: 10px;
            background-color: #ffdddd;
            border: 1px solid #ff5c5c;
            color: #a94442;
            border-radius: 5px;
            margin-top: 15px;
        }

        .kembalian {
            padding: 10px;
            background-color: #ddffdd;
            border: 1px solid #5cb85c;
            color: #3c763d;
            border-radius: 5px;
            margin-top: 15px;
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
        <h2>Entri Transaksi</h2>
        <form method="POST">
            <label>Pilih Pesanan</label>
            <select name="idpesanan" required>
                <option value="">Pilih Pesanan</option>
                <?php mysqli_data_seek($pesanan, 0); while ($p = mysqli_fetch_assoc($pesanan)) : ?>
                    <option value="<?= $p['idpesanan'] ?>" data-total="<?= $p['total'] ?>">
                        <?= $p['namamenu'] ?> x <?= $p['jumlah'] ?> = Rp<?= number_format($p['total']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Total (Rp)</label>
            <input type="number" name="total" required>

            <label>Bayar (Rp)</label>
            <input type="number" name="bayar" required>

            <button type="submit">Simpan Transaksi</button>
        </form>

        <?php if ($pesan): ?>
            <div class="pesan"><?= $pesan ?></div>
        <?php elseif ($kembalian !== null): ?>
            <div class="kembalian">Transaksi berhasil. Kembalian: <strong>Rp<?= number_format($kembalian) ?></strong></div>
            <?php
                $last = mysqli_query($conn, "SELECT MAX(idtransaksi) AS id FROM transaksi");
                $lastRow = mysqli_fetch_assoc($last);
                $idtransaksi = $lastRow['id'];
            ?>
            <form action="cetak_struk.php" method="get" target="_blank">
                <input type="hidden" name="idtransaksi" value="<?= $idtransaksi ?>">
                <button type="submit" style="margin-top: 10px;">Cetak Struk</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Riwayat Transaksi</h2>
        <table>
            <tr>
                <th>ID Transaksi</th>
                <th>Menu</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php while ($r = mysqli_fetch_assoc($riwayat)) : ?>
                <tr>
                    <td><?= $r['idtransaksi'] ?></td>
                    <td><?= $r['namamenu'] ?></td>
                    <td>Rp<?= number_format($r['total']) ?></td>
                    <td>Rp<?= number_format($r['bayar']) ?></td>
                    <td><?= $r['status'] ?></td>
                    <td>
                        <a class="hapus" href="?hapus=<?= $r['idtransaksi'] ?>" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<!-- Script untuk otomatis mengisi total -->
<script>
    const pesananSelect = document.querySelector('select[name="idpesanan"]');
    const totalInput = document.querySelector('input[name="total"]');

    pesananSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const total = selected.getAttribute('data-total');
        totalInput.value = total ? total : '';
    });
</script>
</body>
</html>
