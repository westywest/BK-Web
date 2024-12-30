<?php 
ob_start();
session_start();
include '../../function/connectDB.php';
// Cek apakah user sudah login dan memiliki role 'guru'
if (!isset($_SESSION['status']) || $_SESSION['role'] !== "guru") {
    // Redirect ke halaman login jika bukan guru
    header("Location:/BK/users/index.php");
    exit;
}
$id = $_GET['id'];
$sql = "SELECT konseling.id, 
               konseling.siswa_id, 
               konseling.guru_id,
               konseling.keluhan,
               konseling.tanggal_konseling, 
               konseling.tindak_lanjut, 
               konseling.status,
               siswa.id AS siswa_id,
               siswa.nis,
               siswa.name AS siswa_name,
               siswa.kelas_id,
               kelas.id AS kelas_id,
               kelas.class_name,
               guru.id AS guru_id,
               guru.name AS guru_name 
        FROM konseling
        JOIN siswa ON konseling.siswa_id = siswa.id
        JOIN guru ON konseling.guru_id = guru.id
        JOIN kelas ON siswa.kelas_id = kelas.id
        WHERE konseling.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($data = $result->fetch_assoc()){
        $siswa_name = $data['siswa_name'];
        $nis = $data['nis'];
        $keluhan = $data['keluhan'];
        $tanggal_konseling = $data['tanggal_konseling'];
        $status = $data['status'];
        $tindak_lanjut = $data['tindak_lanjut'];
        $class_name = $data['class_name'];
        $guru_name = $data['guru_name'];
        // Set tanggal cetak
        date_default_timezone_set('Asia/Jakarta'); // Atur timezone ke Jakarta
        $tanggalCetak = date('d-m-Y H:i:s');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[<?= htmlspecialchars(date('d-m-Y', strtotime($tanggal_konseling))); ?>] Daftar Konseling - <?= htmlspecialchars($name_siswa); ?></title>
    <link rel="stylesheet" href="../../assets/css/style_cetak.css">
</head>
<body>
    <div class="container">
        <header>
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        <img src="../../assets/images/logo.jpg" alt="Logo" class="logo">
                    </td>
                    <td class="text-cell">
                        <h1 class="school-name">SMP NEGERI 3 PURWOKERTO</h1>
                        <p class="school-address">
                            Jl. Gereja No.20, Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115
                        </p>
                    </td>
                </tr>
            </table>
            <hr class="divider">
        </header>
        <main>
            <h3>DETAIL PENDAFTARAN KONSELING</h3>
            <table>
                <tr>
                    <td><b>NIS</b></td>
                    <td><?php echo htmlspecialchars($nis); ?></td>
                </tr>
                <tr>
                    <td><b>Nama</b></td>
                    <td><?php echo htmlspecialchars($siswa_name); ?></td>
                </tr>
                <tr>
                    <td><b>Kelas</b></td>
                    <td><?php echo htmlspecialchars($class_name); ?></td>
                </tr>
                <tr>
                    <td><b>Keluhan</b></td>
                    <td><?php echo htmlspecialchars($keluhan); ?></td>
                </tr>
                <tr>
                    <td><b>Guru BK</b></td>
                    <td><?php echo htmlspecialchars($guru_name); ?></td>
                </tr>
                <tr>
                    <td><b>Tanggal Konseling</b></td>
                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($tanggal_konseling))); ?> Pukul <?php echo htmlspecialchars(date('H:i', strtotime($tanggal_konseling))) ?></td>
                </tr>
                <tr>
                    <td><b>Tindak Lanjut</b></td>
                    <td><?php echo htmlspecialchars($tindak_lanjut); ?></td>
                </tr>
                <tr>
                    <td><b>Status Konseling</b></td>
                    <td><?php echo htmlspecialchars($status); ?></td>
                </tr>
            </table>
            <p class="tglCetak">Dicetak pada <?php echo htmlspecialchars($tanggalCetak) ?></p>
        </main>
    </div>
    <?php
        require '../cetak/createpdf/vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf(
            ['mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_top' => 25,
            'margin_bottom' => 25,
            'margin_left' => 25,
            'margin_right' => 25]
        );
        $html = ob_get_contents();

        ob_end_clean();
        $mpdf->WriteHTML($html);
        
        $content = $mpdf->Output("[".htmlspecialchars(date('d-m-Y', strtotime($tanggal_konseling)))."] Daftar Konseling - ".htmlspecialchars($name_siswa).".pdf", "D");
    ?>
</body>
</html>
