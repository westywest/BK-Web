<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.lineicons.com/5.0/lineicons.css" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../../assets/css/style_user.css">
    <title>Profil | Guru</title>
    <style>
        .buttons{
            width: 40px;                
            font-size: 18px;              
        }.btn{
            display: inline-flex;       
            align-items: center;      
            justify-content: center;       
            height: 40px;                  
            padding: 0;                    
            border-radius: 5px;            
        }
    </style>
</head>
<body>
    <?php
    session_start();

    // Cek apakah user sudah login dan memiliki role 'guru'
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "guru") {
        // Redirect ke halaman login jika bukan guru
        header("Location:/BK/users/index.php");
        exit;
    }
    
    include '../../../function/connectDB.php';
    
    // Ambil username dari session
    $username = $_SESSION['username'];
    $fotoDefault = 'default.jpg';
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

    
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
    $sql .= " ORDER BY kunjungan_siswa.date DESC";

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
    
    ?>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex sidebar-header">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-dashboard-square-1"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="../dashboard.php">BK SPENTHREE</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="../dashboard.php" class="sidebar-link">
                        <i class='bx bx-home' ></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../profil/index.php" class="sidebar-link">
                        <i class='bx bxs-user-detail'></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../publikasi/index.php" class="sidebar-link">
                        <i class='bx bx-news'></i>
                        <span>Publikasi</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../daftar_siswa/index.php" class="sidebar-link">
                        <i class='bx bx-group' ></i>
                        <span>Daftar Siswa</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../daftar_konseling/index.php" class="sidebar-link">
                        <i class='bx bx-list-check' ></i>
                        <span>Daftar Konseling</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
                        <i class='bx bx-list-ul' ></i>
                        <span>Kunjungan Siswa</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../kotak_saran/index.php" class="sidebar-link">
                        <i class='bx bxs-inbox'></i>
                        <span>Kotak Saran</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../kasus/index.php" class="sidebar-link">
                        <i class='bx bx-error'></i>
                        <span>Catatan Kasus</span>
                    </a>
                </li>
            </ul>
            <div class="user-profile-footer p-2 d-flex align-items-center">
                <img src="../../../assets/images/teacher/<?php echo htmlspecialchars($_SESSION['foto'] ?: $fotoDefault); ?>" alt="User Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                <div class="user-info">
                    <h6 class="text-white mb-0"><?php echo ($_SESSION['name']) ?></h6>
                    <small><?php echo ucfirst($_SESSION['role']) ?></small>
                </div>
            </div>
            <div class="sidebar-footer">
                <a href="../../../function/logout.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="#">Kunjungan Siswa</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Log Kunjungan Siswa</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Log Kunjungan Siswa</h1>
                    <p>Log Kunjungan Siswa</p>

                    <form method="GET" action="" class="d-flex" style="margin-bottom: 10px;">
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" style="width: 200px; height:40px;">
                            </div>
                            <div class="col-auto">
                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" style="width: 200px; height:40px;">
                            </div>
                            <div class="col-auto">
                                <label class="form-label d-block" style="visibility: hidden;">&nbsp;</label> <!-- Spacer -->
                                <button type="submit" class="btn btn-primary buttons"><i class='bx bx-filter-alt'></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="card">
                        <div class="card-body">
                            <?php if ($start_date || $end_date): ?>
                                <p class="alert alert-info">
                                    Menampilkan data konseling 
                                    <?php if ($start_date && $end_date): ?>
                                        dari <strong><?= date('d-m-Y', strtotime($start_date)) ?></strong> sampai <strong><?= date('d-m-Y', strtotime($end_date)) ?></strong>.
                                    <?php elseif ($start_date): ?>
                                        mulai dari <strong><?= date('d-m-Y', strtotime($start_date)) ?></strong>.
                                    <?php elseif ($end_date): ?>
                                        sampai <strong><?= date('d-m-Y', strtotime($end_date)) ?></strong>.
                                    <?php endif; ?>
                                </p>
                                <a href="index.php" class="btn btn-secondary mb-3" style="color: white; width: 180px;">Tampilkan Semua Data</a>
                            <?php else: ?>
                                <p class="alert alert-secondary">
                                    Menampilkan semua data konseling.
                                </p>
                            <?php endif; ?>
                            
                            <?php if ($result->num_rows > 0): ?>
                                <form method="POST" action="../../cetak/cetakAll_kunjungan.php">
                                    <?php if (!empty($start_date)): ?>
                                        <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                                    <?php endif; ?>
                                    <?php if (!empty($end_date)): ?>
                                        <input type="hidden" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-success mt-3" style="color: white; width: 100px; margin-bottom: 10px;">Cetak PDF</button>
                                </form>
                            <?php endif; ?>
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Waktu Kunjungan</th>
                                            <th scope="col">Nama Siswa</th>
                                            <th scope="col">Kelas</th>
                                            <th scope="col">Guru yang ditemui</th>
                                            <th scope="col">Keperluan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $rowNumber = 1;  
                                            while ($row = $result->fetch_assoc()) {
                                                echo '
                                                    <tr>
                                                        <td>'.$rowNumber.'</td>
                                                        <td>'.date("d F Y H:i:s", strtotime($row["date"])).'</td>
                                                        <td>'.$row['siswa_name'].'</td>
                                                        <td>'.$row['class_name'].'</td>
                                                        <td>'.$row['guru_name'].'</td>
                                                        <td>'.$row['keperluan'].'</td>
                                                    </tr>
                                                '; $rowNumber++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <footer class="pt-5 d-flex justify-content-between">
                        <span>Copyright © 2025 <a href="#">BKSPENTHREE</a></span>
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
    <script src="../../../assets/script/script_admin.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#table').DataTable();
        });
    </script>
    </body>
</html>