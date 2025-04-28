<?php
require('fpdf/fpdf.php');
include 'koneksi.php';

// Terima filter dari form
$filter = isset($_POST['filter']) ? $_POST['filter'] : 'semua';

// SQL base
$sql = "SELECT 
    t.idtransaksi, 
    t.total, 
    t.bayar, 
    t.status,
    p.idpesanan,
    m.namamenu,
    p.jumlah,
    pl.namapelanggan,
    t.created_at
    FROM transaksi t
    JOIN pesanan p ON t.idpesanan = p.idpesanan
    JOIN menu m ON p.idmenu = m.idmenu
    JOIN pelanggan pl ON p.idpelanggan = pl.idpelanggan
";

// Tambahkan filter berdasarkan pilihan
if ($filter == 'hariini') {
    $sql .= " WHERE DATE(t.created_at) = CURDATE()";
} elseif ($filter == 'mingguini') {
    $sql .= " WHERE YEARWEEK(t.created_at, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($filter == 'bulanini') {
    $sql .= " WHERE YEAR(t.created_at) = YEAR(CURDATE()) AND MONTH(t.created_at) = MONTH(CURDATE())";
}

// Urutkan yang terbaru
$sql .= " ORDER BY t.idtransaksi DESC";

// Query database
$query = mysqli_query($conn, $sql);

// Buat PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Judul laporan sesuai filter
$judul = 'Laporan Transaksi';
if ($filter == 'hariini') {
    $judul .= ' - Hari Ini';
} elseif ($filter == 'mingguini') {
    $judul .= ' - Minggu Ini';
} elseif ($filter == 'bulanini') {
    $judul .= ' - Bulan Ini';
}

$pdf->Cell(190, 10, $judul, 0, 1, 'C');
$pdf->Ln(5);

// Header table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Menu', 1);
$pdf->Cell(20, 10, 'Jumlah', 1);
$pdf->Cell(40, 10, 'Pelanggan', 1);
$pdf->Cell(30, 10, 'Total', 1);
$pdf->Cell(40, 10, 'Bayar', 1);
$pdf->Ln();

// Isi tabel
$pdf->SetFont('Arial', '', 12);
while ($row = mysqli_fetch_assoc($query)) {
    $pdf->Cell(20, 10, $row['idtransaksi'], 1);
    $pdf->Cell(40, 10, substr($row['namamenu'], 0, 20), 1);
    $pdf->Cell(20, 10, $row['jumlah'], 1);
    $pdf->Cell(40, 10, substr($row['namapelanggan'], 0, 20), 1);
    $pdf->Cell(30, 10, 'Rp' . number_format($row['total']), 1);
    $pdf->Cell(40, 10, 'Rp' . number_format($row['bayar']), 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output('D', 'laporan_transaksi.pdf');
?>
