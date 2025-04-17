<?php
include 'koneksi.php';

if (!isset($_GET['idtransaksi'])) {
    echo "ID transaksi tidak ditemukan.";
    exit;
}

$id = $_GET['idtransaksi'];

// Ambil detail transaksi
$query = mysqli_query($conn, "SELECT t.*, m.namamenu, p.jumlah, m.harga
    FROM transaksi t 
    JOIN pesanan p ON t.idpesanan = p.idpesanan 
    JOIN menu m ON p.idmenu = m.idmenu
    WHERE t.idtransaksi = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            width: 300px;
            margin: auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .btn-print {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
        }
    </style>
</head>
<body onload="window.print()">
    <h2>Struk Pembayaran</h2>
    <div class="line"></div>
    <p>
        ID Transaksi: <?= $data['idtransaksi'] ?><br>
        Menu: <?= $data['namamenu'] ?><br>
        Harga: Rp<?= number_format($data['harga']) ?><br>
        Jumlah: <?= $data['jumlah'] ?><br>
        Total: Rp<?= number_format($data['total']) ?><br>
        Bayar: Rp<?= number_format($data['bayar']) ?><br>
        Kembalian: Rp<?= number_format($data['bayar'] - $data['total']) ?><br>
        Status: <?= $data['status'] ?><br>
    </p>
    <div class="line"></div>
    <p class="text-center">Terima kasih!</p>
</body>
</html>
