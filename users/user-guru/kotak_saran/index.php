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
    <title>Kotak Saran | Guru</title>
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
    
    $username = $_SESSION['username'];
    
    $user_id = $_SESSION['user_id'];

    if (!isset($_SESSION['guru_id'])) {
        echo "guru_id tidak ditemukan di session!";
        exit;
    }
    
    $guru_id = $_SESSION['guru_id'];
    
    $sql = "SELECT id, date, message, reply, guru_id, status FROM kotak WHERE guru_id = ? AND status = 'open' ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $guru_id);
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
                <li class="sidebar-item">
                    <a href="../daftar_konseling/index.php" class="sidebar-link">
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
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
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
                <img src="../../../assets/images/profile.jpg" alt="User Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
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
                            <li class="breadcrumb-item"><a href="#">Kotak Saran</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Kotak Saran</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Data Kotak Saran</h1>
                    <p>Ketika kotak saran berstatus <span class="badge bg-primary">Open</span>, silahkan membalas pesan tersebut. Jika sudah dibalas, status akan berubah <span class="badge bg-secondary">Closed</span> dan berpindah ke halaman Log Kotak Saran.</p>


                    <div class="card">
                        <div class="card-body">
                        <a class="btn btn-secondary mb-4" href="log.php" style="color: white; width: 50px;"><i class='bx bx-history'></i></a>
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tanggal/Waktu</th>
                                            <th scope="col">Message</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $rowNumber = 1;
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $badgeClass = $replyButton = $showButton = '';
                                            
                                            if ($row['status'] === "open") {
                                                $badgeClass = 'bg-primary';
                                                $replyButton = '<a class="btn btn-sm btn-warning" href="reply.php?id=' . htmlspecialchars($row['id']) . '">
                                                                    <i class="bx bx-reply"></i> Balas
                                                                </a>';
                                            } elseif ($row['status'] === "closed") {
                                                $badgeClass = 'bg-success';
                                                $showButton = '<a class="btn btn-sm btn-info" href="reply.php?id=' . htmlspecialchars($row['id']) . '">
                                                                    <i class="bx bx-show"></i> Tampilkan
                                                                </a>';
                                            } else {
                                                $badgeClass = 'bg-light';
                                            }

                                            echo '
                                                <tr>
                                                    <td>' . $rowNumber . '</td>
                                                    <td>' . date("d F Y H:i:s", strtotime($row["date"])) . '</td>
                                                    <td>' . htmlspecialchars($row["message"]) . '</td>
                                                    <td>
                                                        <span class="badge ' . $badgeClass . '">
                                                            ' . ucfirst($row['status']) . '
                                                        </span>
                                                    </td>
                                                    <td>
                                                        ' . $showButton . ' 
                                                        ' . $replyButton . ' 
                                                    </td>
                                                </tr>
                                            ';
                                            $rowNumber++;
                                        }
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