<?php 
ob_start();
include '../function/connectDB.php';
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
        $name_siswa = $data['siswa_name'];
        $nis = $data['nis'];
        $keluhan = $data['keluhan'];
        $tanggal_konseling = $data['tanggal_konseling'];
        $status = $data['status'];
        $tindak_lanjut = $data['tindak_lanjut'];
        $class_name = $data['class_name'];
        $guru_name = $data['guru_name'];
        $tanggalCetak = date('d F Y, H:i:s');

        // Konversi ke DateTime
        $datetime = new DateTime($tanggal_konseling);

        // Set locale ke Indonesia
        setlocale(LC_TIME, 'id_ID.utf8');

        // Format tanggal menjadi "26 Desember 2024"
        $tanggal = strftime('%d %B %Y', $datetime->getTimestamp());

        // Format jam tetap "14:30:00"
        $jam = $datetime->format('H:i:s');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[<?= $tanggal ?>] Daftar Konseling - <?= $name_siswa ?></title>
    <link rel="stylesheet" href="../assets/css/style_cetak.css">
</head>
<body>
    <div class="container">
        <header>
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        <img src="../assets/images/logo.jpg" alt="Logo" class="logo">
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
                    <td><?php echo $nis; ?></td>
                </tr>
                <tr>
                    <td><b>Nama</b></td>
                    <td><?php echo $name_siswa; ?></td>
                </tr>
                <tr>
                    <td><b>Kelas</b></td>
                    <td><?php echo $class_name; ?></td>
                </tr>
                <tr>
                    <td><b>Keluhan</b></td>
                    <td><?php echo $keluhan; ?></td>
                </tr>
                <tr>
                    <td><b>Guru BK</b></td>
                    <td><?php echo $guru_name; ?></td>
                </tr>
                <tr>
                    <td><b>Tanggal Konseling</b></td>
                    <td><?php echo $tanggal; ?> Pukul <?php echo $jam ?></td>
                </tr>
                <tr>
                    <td><b>Tindak Lanjut</b></td>
                    <td><?php echo $tindak_lanjut; ?></td>
                </tr>
                <tr>
                    <td><b>Status Konseling</b></td>
                    <td><?php echo $status; ?></td>
                </tr>
            </table>
            <p class="tglCetak">Dicetak pada <?php echo $tanggalCetak ?></p>
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
        $mpdf->WriteHTML(utf8_encode($html));
        
        $content = $mpdf->Output("[".$tanggal."] Daftar Konseling - ".$name_siswa.".pdf", "D");
    ?>
</body>
</html>
