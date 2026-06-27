<?php
require_once '../config/database.php';
require_once '../models/Maintenance.php';

$selected_cabang = $_GET['id_cabang'] ?? '';
$selected_bulan = $_GET['bulan'] ?? date('m');
$selected_tahun = $_GET['tahun'] ?? date('Y');

if (!$selected_cabang) {
    die("Pilih cabang terlebih dahulu.");
}

$maintenanceModel = new Maintenance($conn);
$stats = $maintenanceModel->getReportStats($selected_cabang, $selected_bulan, $selected_tahun);
$summaryDivisi = $maintenanceModel->getSummaryPerDivisi($selected_cabang, $selected_bulan, $selected_tahun);
$detailedMaintenance = $maintenanceModel->getDetailedByMonth($selected_cabang, $selected_bulan, $selected_tahun);

// Fetch Branch Name
$stmt = $conn->prepare("SELECT nama_cabang FROM cabang WHERE id = ?");
$stmt->execute([$selected_cabang]);
$branchName = $stmt->fetchColumn();

// Check for DomPDF
if (!file_exists('../vendor/autoload.php')) {
    die("<h4>Library DomPDF tidak ditemukan!</h4>
         <p>Silakan jalankan perintah berikut di terminal Anda:</p>
         <code>composer require dompdf/dompdf</code>
         <br><br>
         <button onclick='window.history.back()' class='btn btn-secondary'>Kembali</button>");
}

require_once '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$months = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
$namaBulan = $months[$selected_bulan];

$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: "Helvetica", sans-serif; font-size: 11px; color: #334155; line-height: 1.4; }
        .page-header { width: 100%; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px; }
        .page-title { font-size: 20px; font-weight: bold; color: #0f172a; margin: 0; letter-spacing: -0.5px; }
        .page-subtitle { font-size: 11px; color: #64748b; margin: 4px 0 0; }
        .badge { font-size: 10px; font-weight: bold; color: #4361ee; background: #e0e7ff; padding: 4px 10px; border-radius: 10px; text-transform: uppercase; }
        .info-card { width: 100%; background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .info-card td { padding: 4px 0; font-size: 11px; }
        .info-label { color: #64748b; }
        .info-value { color: #0f172a; font-weight: bold; }
        .section-title { font-size: 11px; font-weight: bold; color: #0f172a; border-bottom: 2px solid #4361ee; padding-bottom: 4px; margin-top: 25px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .stats-grid { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .stats-grid td { width: 25%; padding: 12px; border: 1px solid #e2e8f0; text-align: center; background: #ffffff; }
        .stats-grid .label { font-size: 9px; color: #64748b; display: block; margin-bottom: 4px; text-transform: uppercase; font-weight: bold; }
        .stats-grid .value { font-size: 16px; font-weight: bold; color: #4361ee; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th { background: #4361ee; color: #ffffff; font-weight: bold; font-size: 11px; padding: 8px; border: 1px solid #4361ee; }
        .data-table td { border: 1px solid #e2e8f0; padding: 8px; font-size: 10.5px; color: #334155; }
        .data-table tr:nth-child(even) { background: #f8fafc; }
        .conclusion-card { padding: 15px; border-left: 4px solid #4361ee; background: #f8fafc; border-radius: 4px; margin-bottom: 25px; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; }
        .conclusion-title { margin: 0 0 5px; color: #4361ee; font-weight: bold; font-size: 11px; }
        .conclusion-text { margin: 0; line-height: 1.5; color: #334155; }
        .footer-table { width: 100%; margin-top: 40px; }
        .footer-table td { width: 50%; text-align: center; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>
    <div style="background: #4361ee; height: 5px; margin-bottom: 15px;"></div>
    
    <table class="page-header">
        <tr>
            <td style="width: 70%;">
                <h1 class="page-title">CHECKLIST MAINTENANCE PC</h1>
                <p class="page-subtitle">Rekap IT &bull; Sistem Manajemen Aset & Maintenance</p>
            </td>
            <td style="width: 30%; text-align: right; vertical-align: middle;">
                <span class="badge">Laporan Bulanan</span>
            </td>
        </tr>
    </table>

    <table class="info-card">
        <tr>
            <td width="18%" class="info-label">Periode Laporan</td>
            <td width="32%" class="info-value">: ' . $namaBulan . ' ' . $selected_tahun . '</td>
            <td width="18%" class="info-label">Kantor / Cabang</td>
            <td width="32%" class="info-value">: ' . $branchName . '</td>
        </tr>
        <tr>
            <td class="info-label">Petugas IT</td>
            <td class="info-value">: Hafizh</td>
            <td class="info-label">Nomor Dokumen</td>
            <td class="info-value" style="font-family: monospace;">: MIS/' . $selected_bulan . '/' . $selected_tahun . '</td>
        </tr>
    </table>

    <div class="section-title">RINGKASAN STATISTIK</div>
    <table class="stats-grid">
        <tr>
            <td>
                <span class="label">Total Asset</span>
                <span class="value">' . $stats['total_asset'] . '</span>
            </td>
            <td>
                <span class="label">Total Maintenance</span>
                <span class="value">' . $stats['total_maintenance'] . '</span>
            </td>
            <td>
                <span class="label">Penyelesaian</span>
                <span class="value">' . $stats['persentase'] . '%</span>
            </td>
            <td>
                <span class="label">Total Temuan</span>
                <span class="value">' . $stats['total_temuan'] . '</span>
            </td>
        </tr>
    </table>

    <div class="section-title">RINGKASAN PER DIVISI</div>
    <table class="data-table">
        <thead>
            <tr>
                <th align="left">Divisi</th>
                <th align="center" width="20%">Total Perangkat</th>
                <th align="center" width="20%">Selesai</th>
                <th align="center" width="20%">Belum Selesai</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($summaryDivisi as $sd) {
            $html .= '<tr>
                <td>' . ($sd['nama_divisi'] ?? 'Tanpa Divisi') . '</td>
                <td align="center">' . $sd['total_perangkat'] . '</td>
                <td align="center" style="color: green; font-weight: bold;">' . $sd['selesai'] . '</td>
                <td align="center" style="color: ' . ($sd['belum'] > 0 ? 'red' : '#334155') . '; font-weight: ' . ($sd['belum'] > 0 ? 'bold' : 'normal') . ';">' . $sd['belum'] . '</td>
            </tr>';
        }
$html .= '
        </tbody>
    </table>

    <div class="section-title">DETAIL CHECKLIST MAINTENANCE</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">Tanggal</th>
                <th width="15%">Kode Aset</th>
                <th width="18%">User (Nama)</th>
                <th width="30%">Aksi / Tindakan</th>
                <th width="13%">Status</th>
                <th width="12%" align="center">Jml Cek</th>
            </tr>
        </thead>
        <tbody>';
        foreach ($detailedMaintenance as $dm) {
            $status = $dm['status'] ?? 'Baik';
            if ($status === 'Baik') {
                $statusHtml = '<span style="color: green; font-weight: bold;">OK</span>';
            } elseif ($status === 'Perlu Perbaikan') {
                $statusHtml = '<span style="color: orange; font-weight: bold;">PERBAIKAN</span>';
            } else {
                $statusHtml = '<span style="color: red; font-weight: bold;">RUSAK</span>';
            }
            $html .= '<tr>
                <td>' . date('d/m/y', strtotime($dm['tanggal'])) . '</td>
                <td><strong>' . $dm['kode_aset'] . '</strong></td>
                <td>' . ($dm['nama_karyawan'] ?? '-') . '</td>
                <td><small>' . ($dm['tindakan'] ?: 'Pengecekan Rutin') . '</small></td>
                <td align="center">' . $statusHtml . '</td>
                <td align="center">' . ($dm['frekuensi'] ?? 1) . 'x</td>
            </tr>';
        }
        if (empty($detailedMaintenance)) {
            $html .= '<tr><td colspan="6" align="center">Tidak ada data maintenance untuk periode ini.</td></tr>';
        }
$html .= '
        </tbody>
    </table>

    <div class="section-title">KESIMPULAN & REKOMENDASI</div>
    <div class="conclusion-card">
        <div class="conclusion-title"><i class="bi bi-info-circle-fill"></i> Analisis Hasil Pemeriksaan</div>
        <p class="conclusion-text">' . generateConclusionPdf($stats, $selected_bulan, $selected_tahun, $branchName) . '</p>
    </div>

    <table class="footer-table">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p style="margin-top: 5px; font-weight: bold; color: #0f172a;">Kepala Cabang / Operasional</p>
                <div class="signature-space"></div>
                <p>( __________________________ )</p>
            </td>
            <td>
                <p>Dilaporkan Oleh,</p>
                <p style="margin-top: 5px; font-weight: bold; color: #0f172a;">Petugas MIS & IT</p>
                <div class="signature-space"></div>
                <p><strong>Hafizh</strong><br><span style="color: #64748b; font-size: 10px;">MIS & IT Department</span></p>
            </td>
        </tr>
    </table>
</body>
</html>';

function generateConclusionPdf($stats, $bulan, $tahun, $branchName) {
    $months = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $namaBulan = $months[$bulan];
    return "Pada bulan $namaBulan $tahun telah dilakukan maintenance terhadap {$stats['total_maintenance']} perangkat dari total {$stats['total_asset']} perangkat yang terdaftar di $branchName. Tingkat penyelesaian maintenance mencapai {$stats['persentase']}%. Sebagian besar perangkat dalam kondisi baik dan antivirus aktif. Beberapa perangkat memerlukan tindak lanjut untuk optimalisasi performa dan pembaruan sistem keamanan.";
}

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Laporan_Maintenance_" . $branchName . ".pdf", ["Attachment" => false]);
