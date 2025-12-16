<?php
session_start();
require('../libraries/fpdf/fpdf.php');
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'admin') {
    header("location: ../auth/login.php");
    exit();
}

$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(277,10,'LAPORAN DATA PENDAFTARAN SEMINAR',0,1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(277,10,'Daftar Peserta Yang Telah Mendaftar',0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,10,'No',1,0,'C');
$pdf->Cell(50,10,'Nama Peserta',1,0,'C');
$pdf->Cell(60,10,'Email',1,0,'C');
$pdf->Cell(70,10,'Judul Seminar',1,0,'C');
$pdf->Cell(40,10,'Tanggal Daftar',1,0,'C');
$pdf->Cell(40,10,'Status',1,1,'C');

$pdf->SetFont('Arial','',10);

$seminar_id = isset($_GET['seminar_id']) ? $_GET['seminar_id'] : '';
$sql = "SELECT r.*, u.name, u.email, s.title 
        FROM registrations r 
        JOIN users u ON r.user_id = u.id 
        JOIN seminars s ON r.seminar_id = s.id";

if($seminar_id) {
    $sql .= " WHERE r.seminar_id = '$seminar_id'";
}

$query = mysqli_query($conn, $sql);
$no = 1;
while($row = mysqli_fetch_assoc($query)){
    $pdf->Cell(10,10,$no++,1,0,'C');
    $pdf->Cell(50,10,$row['name'],1,0);
    $pdf->Cell(60,10,$row['email'],1,0);
    $pdf->Cell(70,10,substr($row['title'], 0, 35).'...',1,0);
    $pdf->Cell(40,10,date('d-m-Y', strtotime($row['registration_date'])),1,0,'C');
    $pdf->Cell(40,10,$row['status'],1,1,'C');
}

$pdf->Output();
?>