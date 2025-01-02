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

$username = $_SESSION['username'];
    
$user_id = $_SESSION['user_id'];
$guru_id = $_SESSION['guru_id'];
$sql = "SELECT kotak.id, kotak.date, kotak.message, kotak.reply, kotak.status, kotak.guru_id, guru.id AS guru_id, guru.name AS guru_name FROM kotak 
        JOIN guru ON kotak.guru_id = guru.id
        WHERE kotak.guru_id = ? AND kotak.status = 'closed' ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $guru_id);
$stmt->execute();
$result = $stmt->get_result();

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
    <title>Kotak Saran</title>
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
            <h3>KOTAK Saran</h3>
            <b>Kepada : <?php echo htmlspecialchars($guru_name); ?></b>
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tanggal/Waktu</th>
                            <th scope="col">Message</th>
                            <th scope="col">Reply</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $rowNumber = 1;
                    while ($row = $result->fetch_assoc()) {
                    $badgeClass = '';

                    if ($row['status'] === "completed") {
                    $badgeClass = 'bg-success';
                    }
                    echo '
                        <tr>
                            <td class="text-right">' . $rowNumber . '</td>
                            <td>' . date("d F Y H:i:s", strtotime($row["date"])) . '</td>
                            <td>' . htmlspecialchars($row["message"]) . '</td>
                            <td>' . htmlspecialchars($row["reply"]) . '</td>
                            <td>
                                <span class="badge bg-primary">'.ucfirst($row['status']).'</span>
                            </td>
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
        
        $content = $mpdf->Output("Kotak Saran.pdf", "D");
    ?>
</body>
</html>