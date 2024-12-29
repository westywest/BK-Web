<?php 
ob_start();
session_start();
include '../../function/connectDB.php';
$guru_id = $_SESSION['guru_id'];
// Menangkap start_date dan end_date dari URL (jika ada)

$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;


// Membuat query dasar
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
        WHERE konseling.guru_id = ? AND konseling.status = 'completed'";

// Tambahkan filter berdasarkan tanggal
if (!is_null($start_date) && !is_null($end_date)) {
    $sql .= " AND konseling.tanggal_konseling BETWEEN ? AND ?";
} elseif (!is_null($start_date)) {
    $sql .= " AND konseling.tanggal_konseling >= ?";
} elseif (!is_null($end_date)) {
    $sql .= " AND konseling.tanggal_konseling <= ?";
}

// Siapkan query dengan prepared statement
$stmt = $conn->prepare($sql);

// Bind parameter
if (!is_null($start_date) && !is_null($end_date)) {
    $stmt->bind_param("iss", $guru_id, $start_date, $end_date);
} elseif (!is_null($start_date)) {
    $stmt->bind_param("is", $guru_id, $start_date);
} elseif (!is_null($end_date)) {
    $stmt->bind_param("is", $guru_id, $end_date);
} else {
    $stmt->bind_param("i", $guru_id);
}

// Eksekusi query dan ambil hasilnya
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Data tidak ditemukan!";
    exit;
}

$guru_name = '';
if ($row = $result->fetch_assoc()) {
    $guru_name = $row['guru_name']; // Simpan nama guru
    $result->data_seek(0); // Reset pointer result untuk iterasi tabel
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
    <title>Daftar Konseling Siswa</title>
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
            <h3>DAFTAR KONSELING SISWA</h3>
            <b>Guru BK  : <?php echo htmlspecialchars($guru_name) ?></b>
                <table class="table" id="table">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Keluhan</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tanggal Konseling</th>
                        <th scope="col">Tindak Lanjut</th>
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
                            <td>' . $row["siswa_name"] . '</td>
                            <td>' . $row["class_name"] . '</td>
                            <td>' . $row["keluhan"] . '</td>
                            <td>
                                <span class="badge ' . $badgeClass . '">' . ucfirst($row['status']) . '</span>
                            </td>
                            <td class="text-right">' . date('d-m-Y', strtotime($row["tanggal_konseling"])) . ' (' . date('H:i', strtotime($row["tanggal_konseling"])) . ')' . '</td>
                            <td>' . $row["tindak_lanjut"] . '</td>
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
        $mpdf->WriteHTML(utf8_encode($html));
        
        $content = $mpdf->Output("Daftar Konseling.pdf", "D");
    ?>
</body>
</html>