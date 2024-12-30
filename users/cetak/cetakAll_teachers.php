<?php 
ob_start();
session_start();
include '../../function/connectDB.php';

$sql = "SELECT guru.id AS guru_id, guru.user_id, guru.nip, guru.name, guru.phone, users.id as user_id, users.username, users.role FROM guru 
    JOIN users ON guru.user_id = users.id
    WHERE users.role = 'guru'
    ORDER BY guru_id DESC";
$datas = $conn->prepare($sql);
$datas->execute();
$resulGuru = $datas->get_result();


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
    <title>Daftar Guru BK</title>
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
            <h3>DAFTAR GURU BK</h3>
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NIP</th>
                        <th scope="col">Username</th>
                        <th scope="col">Nama</th>
                        <th scope="col">No Telepon</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $rowNumber = 1;
                while ($row = $resulGuru->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $rowNumber++; ?></td>
                        <td><?php echo htmlspecialchars($row['nip']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    </tr>
                    <?php
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
        
        $mpdf->Output("Daftar Guru BK.pdf", "D");
    ?>
</body>
</html>
