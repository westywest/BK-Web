<?php 
ob_start();
session_start();
include '../../function/connectDB.php';
$id = $_GET['id'];
$sql = "SELECT guru.id AS guru_id, guru.nip, guru.name, guru.phone, guru.user_id, users.id AS user_id, users.username
    FROM guru JOIN users on guru.user_id = users.id
    WHERE guru.id = ?";

$datas = $conn->prepare($sql);
$datas->bind_param("i",$id);
$datas->execute();
$resultGuru = $datas->get_result();

if ($data = $resultGuru->fetch_assoc()) {
    $nip = $data['nip'];
    $username = $data['username'];
    $name = $data['name'];
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
    <title>Data Guru - <?php echo htmlspecialchars($name) ?></title>
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
            <h3>DATA GURU BK</h3>
            <table>
                <tr>
                    <td><b>NIP</b></td>
                    <td><?php echo htmlspecialchars($nip); ?></td>
                </tr>
                <tr>
                    <td><b>Username</b></td>
                    <td><?php echo htmlspecialchars($username); ?></td>
                </tr>
                <tr>
                    <td><b>Nama</b></td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                </tr>
                <tr>
                    <td><b>No. Telepon</b></td>
                    <td><?php echo htmlspecialchars($phone); ?></td>
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
        
        $content = $mpdf->Output("Data Guru - ".htmlspecialchars($name).".pdf", "D");
    ?>
</body>
</html>
