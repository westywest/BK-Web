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

// Ambil username dari session
$username = $_SESSION['username'];

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;



// Query untuk mengambil data guru berdasarkan username yang login
$sql = "SELECT 
        kunjungan_siswa.id AS kunjungan_id, 
        kunjungan_siswa.user_id AS kunjungan_user_id, 
        kunjungan_siswa.guru_id, 
        kunjungan_siswa.keperluan, 
        kunjungan_siswa.date, 
        users.id AS user_id, 
        guru.id AS guru_id, 
        guru.name AS guru_name, 
        siswa.id AS siswa_id, 
        siswa.name AS siswa_name,
        siswa.kelas_id AS siswa_kelas_id, 
        siswa.user_id AS siswa_user_id,
        kelas.id AS kelas_id,
        kelas.class_name
    FROM kunjungan_siswa 
    JOIN users ON kunjungan_siswa.user_id = users.id
    JOIN guru ON kunjungan_siswa.guru_id = guru.id
    JOIN siswa ON users.id = siswa.user_id
    JOIN kelas ON siswa.kelas_id = kelas.id";

// Tambahkan waktu maksimal untuk end_date jika ada
if (!is_null($end_date)) {
    $end_date .= ' 23:59:59';
}
// Menambahkan kondisi filter untuk tanggal jika ada
if ($start_date && $end_date) {
    $sql .= " AND kunjungan_siswa.date BETWEEN ? AND ?";
} elseif ($start_date) {
    $sql .= " AND kunjungan_siswa.date >= ?";
} elseif ($end_date) {
    $sql .= " AND kunjungan_siswa.date <= ?";
}
$sql .= " ORDER BY kunjungan_siswa.date ASC";

$stmt = $conn->prepare($sql);

// Bind parameter berdasarkan ada tidaknya filter tanggal
if ($start_date && $end_date) {
    $stmt->bind_param("ss", $start_date, $end_date);  // "iss" untuk integer, string, string
} elseif ($start_date) {
    $stmt->bind_param("s", $start_date);  // "is" untuk integer, string
} elseif ($end_date) {
    $stmt->bind_param("s", $end_date);  // "is" untuk integer, string
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Data tidak ditemukan!";
    exit;
}

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
    <title>Daftar Kunjungan Siswa</title>
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
                            Jl. Gereja No.20 Purwokerto, Sokanegara, Kec. Purwokerto Timur, Kab. Banyumas Prov. Jawa Tengah | Phone: (0281) 637842 | Email:smpn3pwt@gmail.com
                        </p>
                    </td>
                </tr>
            </table>
            <hr class="divider">
        </header>
        <main>
            <h3>DAFTAR KUNJUNGAN SISWA</h3>
                <table class="table" id="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Waktu Kunjungan</th>
                        <th scope="col">Nama Siswa</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Guru Yang Ditemui</th>
                        <th scope="col">Keperluan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $rowNumber = 1;
                    while ($row = $result->fetch_assoc()) {
                    
                    echo '
                        <tr>
                            <td class="text-right">' . $rowNumber . '</td>
                            <td>' . date('d-m-Y', strtotime($row["date"])) . ' (' . date('H:i', strtotime($row["date"])) . ')' . '</td>
                            <td>' . $row["siswa_name"] . '</td>
                            <td>' . $row["class_name"] . '</td>
                            <td>' . $row["guru_name"] . '</td>
                            <td>' . $row["keperluan"] . '</td>
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
        
        $content = $mpdf->Output("Daftar Kunjungan Siswa.pdf", "D");
    ?>
</body>
</html>