<?php
require 'dompdf/autoload.inc.php'; // Import manual

use Dompdf\Dompdf;

include 'koneksi.php';
session_start();

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

$html = '
<h2 style="text-align:center;">Laporan Transaksi</h2>
<table border="1" cellspacing="0" cellpadding="8" width="100%">
    <thead>
        <tr style="background-color: #007bff; color: white;">
            <th>ID Transaksi</th>
            <th>Pesanan</th>
            <th>Jumlah</th>
            <th>Pelanggan</th>
            <th>Total</th>
            <th>Bayar</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
';

while ($row = mysqli_fetch_assoc($transaksi)) {
    $html .= '
        <tr>
            <td>'.$row['idtransaksi'].'</td>
            <td>'.$row['namamenu'].'</td>
            <td>'.$row['jumlah'].'</td>
            <td>'.$row['namapelanggan'].'</td>
            <td>Rp'.number_format($row['total']).'</td>
            <td>Rp'.number_format($row['bayar']).'</td>
            <td>'.$row['status'].'</td>
        </tr>
    ';
}

$html .= '
    </tbody>
</table>
';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('laporan_transaksi.pdf', array('Attachment' => 1));
?>
