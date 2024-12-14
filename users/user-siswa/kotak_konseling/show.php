<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.lineicons.com/5.0/lineicons.css" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../../assets/css/style_user.css">
    <title>Kotak Konseling | Siswa</title>
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
    $konseling_id = $_GET['konseling_id'];
    $sql = "SELECT kotak_konseling.id AS konseling_id, kotak_konseling.date, kotak_konseling.message, kotak_konseling.reply, kotak_konseling.guru_id, kotak_konseling.status, guru.id AS guru_id, guru.name AS guru_name, users.id AS user_id, anon_mapping.id AS anon_id, anon_mapping.konseling_id, anon_mapping.user_id FROM kotak_konseling
    JOIN guru ON kotak_konseling.guru_id = guru.id
    JOIN anon_mapping ON kotak_konseling.id = anon_mapping.konseling_id
    JOIN users ON anon_mapping.user_id = users.id
    WHERE konseling_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $konseling_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($data = $result->fetch_assoc()) {
        $message = $data['message'];
        $reply = $data['reply'];
        $guru_name = $data['guru_name'];
    }
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
                <li class="sidebar-item">
                    <a href="../pendaftaran_konseling/index.php" class="sidebar-link">
                        <i class='bx bxs-file-plus'></i>
                        <span>Pendaftaran Konseling</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../kunjungan/index.php" class="sidebar-link">
                        <i class='bx bx-list-plus'></i>
                        <span>Kunjungan</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
                        <i class='bx bxs-inbox'></i>
                        <span>Kotak Konseling</span>
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
                            <li class="breadcrumb-item"><a href="index.php">Log Kotak Konseling</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Konseling</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Detail Konseling</h1>
                    <p>Harap</p>

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label><br>
                                <div class="message-box" style="border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9; border-radius: 5px; min-height: 100px;">
                                    <?php echo htmlspecialchars($message); ?>                                </div>
                                </div>
                            <div class="mb-3">
                                <label for="reply" class="form-label">Balasan (Guru : <?php echo htmlspecialchars($guru_name); ?>)</label><br>
                                <div class="message-box" style="border: 1px solid #ccc; padding: 10px; background-color: #e0f7fa; border-radius: 5px; min-height: 100px;">
                                    <?php echo isset($reply) && !empty($reply) ? htmlspecialchars($reply) : 'Belum ada balasan.'; ?>
                                </div>
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
    </body>
</html>