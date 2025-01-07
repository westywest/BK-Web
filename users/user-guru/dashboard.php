    <?php
    session_start();
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "guru") {
        // Redirect ke halaman login jika bukan guru
        header("Location:/BK/users/index.php");
        exit;
    }

    include '../../function/connectDB.php';

    $data = [];
    try {
        // Query untuk mendapatkan jumlah masing-masing jenis kasus
        $sql = "SELECT jenis_layanan.jenis AS jenis, 
                    COUNT(kasus.id) AS total
                FROM kasus
                JOIN jenis_layanan ON kasus.jenis_id = jenis_layanan.id
                GROUP BY jenis_layanan.jenis";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $data[] = [$row['jenis'], (int)$row['total']];
        }
        $stmt->close();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }

    $kunjunganPerBulan = [];
try {
    // Query untuk menghitung jumlah kunjungan siswa per bulan
    $sql = "SELECT DATE_FORMAT(date, '%M %Y') AS bulan, COUNT(id) AS total
            FROM kunjungan_siswa
            WHERE date IS NOT NULL
            GROUP BY DATE_FORMAT(date, '%M %Y')
            ORDER BY DATE_FORMAT(date, '%Y-%m')";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        if (!empty($row['bulan']) && $row['total'] !== null) {
            $kunjunganPerBulan[] = [$row['bulan'], (int)$row['total']];
        }
    }
    $stmt->close();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.lineicons.com/5.0/lineicons.css" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../assets/css/style_user.css">
    <title>Dashboard | Guru</title>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Jenis', 'Total'],
                <?php
                foreach ($data as $d) {
                    echo "['" . addslashes($d[0]) . "', " . $d[1] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Distribusi Catatan Kasus Siswa Berdasarkan Jenisnya',
                pieHole: 0.4,
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
    </script>

    <script type="text/javascript">
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawBarChart);

        function drawBarChart() {
            var data = google.visualization.arrayToDataTable([
                ['Bulan', 'Jumlah Kunjungan'],
                <?php
                foreach ($kunjunganPerBulan as $row) {
                    echo "['" . addslashes($row[0]) . "', " . $row[1] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Jumlah Kunjungan Siswa per Bulan',
                hAxis: {
                    title: 'Bulan',
                    slantedText: true, // Menyesuaikan label yang panjang
                    slantedTextAngle: 45
                },
                vAxis: {
                    title: 'Jumlah Kunjungan',
                },
                chartArea: {width: '50%'},
                colors: ['#76A7FA']
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('bar_chart'));
            chart.draw(data, options);
        }

    </script>

</head>
<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex sidebar-header">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-dashboard-square-1"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="dashboard.php">SPENTHREE</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item active">
                    <a href="dashboard.php" class="sidebar-link">
                        <i class='bx bx-home' ></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="profil/index.php" class="sidebar-link">
                        <i class='bx bxs-user-detail'></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="publikasi/index.php" class="sidebar-link">
                        <i class='bx bx-news'></i>
                        <span>Publikasi</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="daftar_siswa/index.php" class="sidebar-link">
                        <i class='bx bx-group' ></i>
                        <span>Daftar Siswa</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="daftar_konseling/index.php" class="sidebar-link">
                        <i class='bx bx-list-check' ></i>
                        <span>Daftar Konseling</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="kunjungan/index.php" class="sidebar-link">
                        <i class='bx bx-list-ul' ></i>
                        <span>Kunjungan Siswa</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="kotak_saran/index.php" class="sidebar-link">
                        <i class='bx bxs-inbox'></i>
                        <span>Kotak Saran</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="kasus/index.php" class="sidebar-link">
                        <i class='bx bx-error'></i>
                        <span>Catatan Kasus</span>
                    </a>
                </li>
            </ul>
            <div class="user-profile-footer p-2 d-flex align-items-center">
                <img src="../../assets/images/profile.jpg" alt="User Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <div class="user-info">
                    <h6 class="text-white mb-0"><?php echo htmlspecialchars($_SESSION['name']) ?></h6>
                    <small><?php echo ucfirst($_SESSION['role']) ?></small>
                </div>
            </div>
            <div class="sidebar-footer">
                <a href="../../function/logout.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <div class="main p-3">
            <main>
                <div class="container-fluid">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Overview</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Selamat Datang <?php echo($_SESSION['name'])?>!</h1>
                    <p>Ini adalah halaman awal setelah anda berhasil login.</p>
                    <table style="width: 100%; border-collapse: collapse; text-align: center;">
                        <tr>
                            <td style="border: none;">
                                <div id="donutchart" style="width: 700px; height: 400px;"></div>
                            </td>
                            <td style="border: none;">
                                <div id="bar_chart" style="width: 700px; height: 400px;"></div>
                            </td>
                        </tr>
                    </table>

                    <footer class="pt-5 d-flex justify-content-between">
                        <span>Copyright © 2024 <a href="#">BKSPENTHREE</a></span>
                        <ul class="nav m-0">
                            <li class="nav-item">
                                <a class="nav-link text-secondary"href="#">Hubungi Kami</a>
                            </li>
                        </ul>
                    </footer>
                </div>
            </main>
        </div>
    </div>    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/script/script_admin.js"></script>
    </body>
</html>