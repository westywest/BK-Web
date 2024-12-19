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
    <title>Pendaftaran Konseling | Siswa</title>
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

    // Cek apakah user sudah login dan memiliki role 'siswa'
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "siswa") {
        
        header("Location:/BK/users/index.php");
        exit;
    }
    
    include '../../../function/connectDB.php';
    $user_id = $_SESSION['user_id'];
    $siswa_id = $_SESSION['siswa_id'];

    $sql = "SELECT konseling.id AS konseling_id, 
            konseling.siswa_id, 
            konseling.guru_id, 
            konseling.keluhan, 
            konseling.status, 
            konseling.tanggal_konseling,
            konseling.tindak_lanjut, 
            guru.id AS guru_id,
            guru.name AS guru_name,
            guru.phone,
            siswa.id AS siswa_id 
        FROM konseling
        JOIN guru ON konseling.guru_id = guru.id
        JOIN siswa ON konseling.siswa_id  = siswa.id
        WHERE siswa.id = ?
        ORDER BY konseling_id DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $siswa_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $sql_check_status = "SELECT COUNT(*) AS pendingORconfirmed 
                     FROM konseling 
                     JOIN siswa ON konseling.siswa_id = siswa.id 
                     WHERE siswa.id = ? AND (status = 'pending' OR status = 'confirmed')";

        $check_status = $conn->prepare($sql_check_status);
        $check_status->bind_param("i", $siswa_id);
        $check_status->execute();
        $result_Check = $check_status->get_result();
        $row_check_status = $result_Check->fetch_assoc();

        $has_open = $row_check_status['pendingORconfirmed'] > 0;

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
                <li class="sidebar-item ">
                    <a href="../profil/index.php" class="sidebar-link">
                        <i class='bx bxs-user-detail'></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
                        <i class='bx bxs-file-plus'></i>
                        <span>Pendaftaran Konseling</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../kunjungan/index.php" class="sidebar-link">
                        <i class='bx bx-list-plus'></i>
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
                    <a href="../catatan_kasus/index.php" class="sidebar-link">
                        <i class='bx bx-error'></i>
                        <span>Catatan Kasus</span>
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
                            <li class="breadcrumb-item"><a href="#">Pendaftaran Konseling</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Log Pendaftaran Konseling</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Log Pendaftaran Konseling</h1>
                    <p>Jika ingin melakukan konseling, lakukan pendaftaran disini.</p>
                    <p>Ketika status <span class="badge bg-warning">Pending</span>, silahkan menunggu guru mengkonfirmasi jadwal konseling. Setelah jadwal terkonfirmasi, status akan berubah menjadi <span class="badge bg-success">Confirmed</span> jadwal konseling sudah ditentukan. Setelah melakukan konseling, status akan berubah menjadi <span class="badge bg-primary">Completed</span>.</p>

                    <div class="card">
                        <div class="card-body">
                        <?php if (!$has_open): ?>
                            <a class="btn btn-primary mb-4" href="create.php" style="color: white; width: 160px;">
                                <i class="lni lni-plus"></i> Daftar Konseling
                            </a>
                        <?php else: ?>
                            <div class="alert alert-warning alert-dismissible fade show">
                                <strong>Perhatian!</strong> Kamu masih memiliki konseling yang berstatus "pending/confirmed". Harap selesaikan konseling tersebut sebelum membuat yang baru.
                            </div>
                        <?php endif; ?>
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Guru</th>
                                            <th scope="col">No. Telepon</th>
                                            <th scope="col">Keluhan</th>
                                            <th scope="col">Tanggal Konseling</th>
                                            <th scope="col">Tindak Lanjut</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $rowNumber = 1;  
                                            while ($row = $result->fetch_assoc()) {
                                                $badgeClass = '';
                                                if ($row['status'] === 'pending') {
                                                    $badgeClass = 'bg-warning';
                                                    $tglKonseling = '';
                                                    $tindakLanjut = '';
                                                } elseif ($row['status'] === 'confirmed') {
                                                    $badgeClass = 'bg-success';
                                                    $tglKonseling = htmlspecialchars($row['tanggal_konseling']);
                                                    $tindakLanjut = '';
                                                } elseif ($row['status'] === 'completed') {
                                                    $badgeClass = 'bg-primary';
                                                    $tglKonseling = htmlspecialchars($row['tanggal_konseling']);
                                                    $tindakLanjut = htmlspecialchars($row['tindak_lanjut']);
                                                } else {
                                                    $badgeClass = 'bg-secondary';
                                                    $tglKonseling = '';
                                                    $tindakLanjut = '';
                                                }

                                                echo '
                                                    <tr>
                                                        <td>' . $rowNumber . '</td>
                                                        <td>' . htmlspecialchars($row['guru_name']) . '</td>
                                                        <td>' . htmlspecialchars($row['phone']) . '</td>
                                                        <td>' . htmlspecialchars($row['keluhan']) . '</td>
                                                        <td>
                                                            ' . $tglKonseling . '
                                                        </td>
                                                        <td>
                                                            ' . $tindakLanjut . '
                                                        </td>
                                                        <td>
                                                            <span class="badge ' . $badgeClass . '">' . ucfirst($row['status']) . '</span>
                                                        </td>
                                                    </tr>
                                                ';
                                                $rowNumber++;
                                            }
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <footer class="pt-5 d-flex justify-content-between">
                        <span>Copyright © 2024 <a href="#">BKSPENTHREE.</a></span>
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
</body>