<?php 
ob_start();
session_start();
include '../../function/connectDB.php';

if (!isset($_SESSION['status']) || ($_SESSION['role'] !== "guru" && $_SESSION['role'] !== "admin")) {
    // Redirect ke halaman login jika bukan guru atau admin
    header("Location:/BK/users/index.php");
    exit;
}
$selectedClass = $_POST['kelas'] ?? 'all';

// Tentukan SQL berdasarkan kelas yang dipilih
if ($selectedClass === 'all') {
    $sql = "SELECT siswa.id AS siswa_id, siswa.nis, siswa.name AS nama_siswa, siswa.jk, siswa.phone, kelas.class_name, guru.name AS nama_guru
            FROM siswa 
            JOIN kelas ON siswa.kelas_id = kelas.id
            JOIN guru ON kelas.guru_id = guru.id
            ORDER BY 
                CAST(SUBSTRING_INDEX(kelas.class_name, ' ', 1) AS UNSIGNED) ASC,  -- Urutkan berdasarkan angka kelas
                SUBSTRING(kelas.class_name, LENGTH(SUBSTRING_INDEX(kelas.class_name, ' ', -1)) + 2) ASC,  -- Urutkan berdasarkan huruf kelas
                siswa.nis ASC";
} else {
    $sql = "SELECT siswa.id AS siswa_id, siswa.nis, siswa.name AS nama_siswa, siswa.jk, siswa.phone, kelas.class_name, guru.name AS nama_guru
            FROM siswa 
            JOIN kelas ON siswa.kelas_id = kelas.id
            JOIN guru ON kelas.guru_id = guru.id
            WHERE kelas.class_name = '$selectedClass'
            ORDER BY siswa.nis ASC";
}

$datas = $conn->prepare($sql);
$datas->execute();
$resulSiswa = $datas->get_result();

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
    <title>Daftar Siswa</title>
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
            <h3>DAFTAR SISWA <?php echo $selectedClass === 'all' ? 'SEMUA KELAS' : "Kelas $selectedClass"; ?></h3>
            <table class="table" id="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">L/P</th>
                        <th scope="col">No Telepon</th>
                        <th scope="col">Guru Pengampu</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $rowNumber = 1;
                while ($row = $resulSiswa->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $rowNumber++; ?></td>
                        <td><?php echo htmlspecialchars($row['nis']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                        <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['jk']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_guru']); ?></td>
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
        
        $mpdf->Output("Daftar Siswa.pdf", "D");
    ?>
</body>
</html>
