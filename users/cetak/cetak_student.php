<?php 
ob_start();
session_start();
include '../../function/connectDB.php';

if (!isset($_SESSION['status']) || ($_SESSION['role'] !== "guru" && $_SESSION['role'] !== "admin")) {
    // Redirect ke halaman login jika bukan guru atau admin
    header("Location:/BK/users/index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT siswa.id AS siswa_id, siswa.user_id, siswa.nis, siswa.name AS siswa_name, siswa.jk, siswa.kelas_id, siswa.phone, users.id AS user_id, kelas.id AS kelas_id, kelas.class_name, guru.id AS guru_id, guru.name AS guru_name
FROM siswa 
JOIN users on siswa.user_id = users.id 
JOIN kelas on siswa.kelas_id = kelas.id
JOIN guru on kelas.guru_id = guru.id
WHERE siswa.id = ?";

$datas = $conn->prepare($sql);
$datas->bind_param("i",$id);
$datas->execute();
$resultSiswa = $datas->get_result();

if ($data = $resultSiswa->fetch_assoc()) {
    $nis = $data['nis'];
    $siswa_name = $data['siswa_name'];
    $jk = $data['jk'];
    $kelas_id = $data['kelas_id'];
    $class_name = $data['class_name'];
    $guru_id = $data['guru_id'];
    $guru_name = $data['guru_name'];
    $phone = $data['phone'];
}

// Set tanggal cetak
date_default_timezone_set('Asia/Jakarta');
$tanggalCetak = date('d-m-Y H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - <?php echo htmlspecialchars($siswa_name) ?></title>
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
            <h3>DATA SISWA</h3>
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
                    <td><b>L/P</b></td>
                    <td><?php echo htmlspecialchars($jk); ?></td>
                </tr>
                <tr>
                    <td><b>No Telepon</b></td>
                    <td><?php echo htmlspecialchars($phone); ?></td>
                </tr>
                <tr>
                    <td><b>Guru Pengampu</b></td>
                    <td><?php echo htmlspecialchars($guru_name); ?></td>
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
        
        $content = $mpdf->Output("Data Siswa - ".htmlspecialchars($siswa_name).".pdf", "D");
    ?>
</body>
</html>
