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
    <title>Kotak Konseling | Guru</title>
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

    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
    
    $username = $_SESSION['username'];
    
    $user_id = $_SESSION['user_id'];

    if (!isset($_SESSION['guru_id'])) {
        echo "guru_id tidak ditemukan di session!";
        exit;
    }
    
    $guru_id = $_SESSION['guru_id'];

    $sql = "SELECT konseling.id, 
               konseling.siswa_id, 
               konseling.guru_id,
               konseling.keluhan,
               konseling.tanggal_konseling, 
               konseling.tindak_lanjut, 
               konseling.status,
               siswa.id AS siswa_id,
               siswa.name,
               siswa.kelas_id,
               kelas.id AS kelas_id,
               kelas.class_name 
        FROM konseling
        JOIN siswa ON konseling.siswa_id = siswa.id
        JOIN kelas ON siswa.kelas_id = kelas.id
        WHERE konseling.guru_id = ? AND konseling.status = 'completed'";


    // Tambahkan waktu maksimal untuk end_date jika ada
    if (!is_null($end_date)) {
        $end_date .= ' 23:59:59';
    }
    // Menambahkan kondisi filter untuk tanggal jika ada
    if ($start_date && $end_date) {
        $sql .= " AND konseling.tanggal_konseling BETWEEN ? AND ?";
    } elseif ($start_date) {
        $sql .= " AND konseling.tanggal_konseling >= ?";
    } elseif ($end_date) {
        $sql .= " AND konseling.tanggal_konseling <= ?";
    }
    $sql .= " ORDER BY konseling.tanggal_konseling DESC";
    $stmt = $conn->prepare($sql);

    // Bind parameter berdasarkan ada tidaknya filter tanggal
    if ($start_date && $end_date) {
        $stmt->bind_param("iss", $guru_id, $start_date, $end_date);  // "iss" untuk integer, string, string
    } elseif ($start_date) {
        $stmt->bind_param("is", $guru_id, $start_date);  // "is" untuk integer, string
    } elseif ($end_date) {
        $stmt->bind_param("is", $guru_id, $end_date);  // "is" untuk integer, string
    } else {
        $stmt->bind_param("i", $guru_id);  // Hanya ID guru
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
                    <a href="../dashboard.php">SPENTHREE</a>
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
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
                        <i class='bx bx-list-check' ></i>
                        <span>Daftar Konseling</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../kunjungan/index.php" class="sidebar-link">
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
                    <a href="../pelanggaran/index.php" class="sidebar-link">
                        <i class='bx bx-error'></i>
                        <span>Pelanggaran Siswa</span>
                    </a>
                </li>
            </ul>
            <div class="user-profile-footer p-2 d-flex align-items-center">
                <img src="../../../assets/images/profile.jpg" alt="User Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <div class="user-info">
                    <h6 class="text-white mb-0"><?php echo ($_SESSION['name']) ?></h6>
                    <small><?php echo($_SESSION['role']) ?></small>
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
                            <li class="breadcrumb-item"><a href="index.php">Data Siswa Konseling</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Log Konseling</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Log Konseling</h1>
                    <p>Konseling yang sudah selesai atau berstatus <b>Completed</b> akan dipindahkan disini.</p>

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
                            <a href="log.php" class="btn btn-secondary mb-3" style="color: white; width: 180px;">Tampilkan Semua Data</a>
                        <?php else: ?>
                            <p class="alert alert-secondary">
                                Menampilkan semua data konseling.
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($result->num_rows > 0): ?>
                            <form method="POST" action="../../cetak/cetakAll_konseling.php">
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
                                        <th scope="col">Nama</th>
                                        <th scope="col">Kelas</th>
                                        <th scope="col">Keluhan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Tanggal Konseling</th>
                                        <th scope="col">Tindak Lanjut</th>
                                        <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rowNumber = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $badgeClass = '';

                                            // Status COMPLETED
                                            if ($row['status'] === "completed") {
                                                $badgeClass = 'bg-primary';
                                            }
                                            echo '
                                                <tr>
                                                    <td>' . $rowNumber . '</td>
                                                    <td>' . $row["name"] . '</td>
                                                    <td>' . $row["class_name"] . '</td>
                                                    <td>' . $row["keluhan"] . '</td>
                                                    <td>
                                                        <span class="badge ' . $badgeClass . '">' . ucfirst($row['status']) . '</span>
                                                    </td>
                                                    <td>' . date('d-m-Y', strtotime($row["tanggal_konseling"])) . ' (' . date('H:i', strtotime($row["tanggal_konseling"])) . ')' . '</td>
                                                    <td>' . $row["tindak_lanjut"] . '</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-secondary buttons" href="../../cetak/cetakST_konseling.php?id='.$row['id'].'">
                                                            <i class="bx bx-printer"></i>
                                                        </a>
                                                    </td>
                                                </tr>';
                                                $rowNumber++;
                                        }
                                            
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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