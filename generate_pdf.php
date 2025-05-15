<?php
require('fpdf/fpdf.php');

// Simulasi data transaksi
$data_transaksi = [
    ['2024-01-01', 'Donasi Jumat', 'Pemasukan', '500000', 'Sukses'],
    ['2024-01-02', 'Pembelian Speaker', 'Pengeluaran', '200000', 'Sukses'],
    ['2024-01-03', 'Donasi Umum', 'Pemasukan', '1000000', 'Sukses'],
];

// Membuat instance PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Header laporan
$pdf->Cell(190, 10, 'Laporan Transaksi Keuangan', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);

// Tabel header
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(40, 10, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Keterangan', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Jenis', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Jumlah', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Status', 1, 1, 'C', true);

// Isi tabel
foreach ($data_transaksi as $row) {
    $pdf->Cell(40, 10, $row[0], 1);
    $pdf->Cell(60, 10, $row[1], 1);
    $pdf->Cell(30, 10, $row[2], 1);
    $pdf->Cell(30, 10, number_format($row[3]), 1, 0, 'R');
    $pdf->Cell(30, 10, $row[4], 1, 1);
}

// Output file PDF
$pdf->Output('D', 'Laporan_Transaksi_Keuangan.pdf');
?>
