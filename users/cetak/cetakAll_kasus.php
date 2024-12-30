<?php 
ob_start();
session_start();
include '../../function/connectDB.php';

$sql = "SELECT kasus.id AS kasus_id,
        kasus.siswa_id, 
        kasus.jenis_id, 
        kasus.date, 
        kasus.note, 
        siswa.id AS siswa_id, 
        siswa.name AS siswa_name, 
        siswa.kelas_id,
        kelas.id AS kelas_id,
        kelas.class_name,
        jenis_layanan.id AS jenis_id, 
        jenis_layanan.jenis 
    FROM kasus
    JOIN siswa ON kasus.siswa_id = siswa.id
    JOIN kelas ON siswa.kelas_id = kelas.id
    JOIN jenis_layanan ON kasus.jenis_id = jenis_layanan.id
    ORDER BY kasus.date DESC";

$kasus = $conn->prepare($sql);
$kasus->execute();
$result = $kasus->get_result();

// Set tanggal cetak
date_default_timezone_set('Asia/Jakarta'); // Atur timezone ke Jakarta
$tanggalCetak = date('d-m-Y H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kasus Siswa</title>
    <link rel="stylesheet" href="../../assets/css/cetakAll.css">
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
            <h3>DAFTAR KASUS SISWA</h3>
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tanggal/Waktu</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Jenis Layanan</th>
                            <th scope="col">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $rowNumber = 1;
                    while ($row = $result->fetch_assoc()) {
                    $badgeClass = '';

                    if ($row['status'] === "completed") {
                    $badgeClass = 'bg-primary';
                    }
                    echo '
                        <tr>
                            <td class="text-right">' . $rowNumber . '</td>
                            <td class="text-right">' . htmlspecialchars(date('d-m-Y', strtotime($row["date"]))) . ' (' . htmlspecialchars(date('H:i', strtotime($row["date"]))) . ')' . '</td>
                            <td>' . htmlspecialchars($row["siswa_name"]) . '</td>
                            <td>' . htmlspecialchars($row["class_name"]) . '</td>
                            <td>' . htmlspecialchars($row["jenis"]) . '</td>
                            <td>' . htmlspecialchars($row["note"]) . '</td>
                        </tr>';
                        $rowNumber++;
                    }
                                            
                    ?>
                    </tbody>
                </table>
            <p class="tglCetak">Dicetak pada <?php echo $tanggalCetak ?></p>
        </main>
    </div>
    <?php
        require '../cetak/createpdf/vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf(
            ['mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_top' => 25,
            'margin_bottom' => 25,
            'margin_left' => 25,
            'margin_right' => 25]
        );
        $html = ob_get_contents();

        ob_end_clean();
        $mpdf->WriteHTML($html);
        
        $content = $mpdf->Output("Daftar Kasus Siswa.pdf", "D");
    ?>
</body>
</html>