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
    <title>Publikasi | Guru</title>
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
    $publikasi_id = $_GET['id'];
    $sql = "SELECT publikasi.id AS publikasi_id, 
            publikasi.guru_id, publikasi.date, 
            publikasi.title, publikasi.jenis, 
            publikasi.content, 
            publikasi.foto,
            guru.id AS guru_id,
            guru.name AS guru_name
            FROM publikasi 
        JOIN guru ON publikasi.guru_id = guru.id
        WHERE publikasi.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $publikasi_id);
    $stmt->execute();
    $result = $stmt->get_result();
    

    if ($data = $result->fetch_assoc()) {
        $foto = $data['foto'];
        $title = $data['title'];
        $date = $data['date'];
        $jenis = $data['jenis'];
        $guru_name = $data['guru_name'];
        $content = $data['content'];
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
                <li class="sidebar-item">
                    <a href="../profil/index.php" class="sidebar-link">
                        <i class='bx bxs-user-detail'></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="index.php">Data Publikasi</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Publikasi</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Detail Publikasi <b><?php echo htmlspecialchars($title) ?></b></h1>
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <img class="img-info" 
                                    src="<?= htmlspecialchars(($foto === 'default.png') 
                                        ? "../../../assets/images/$foto" 
                                        : "../../../assets/images/uploads/$foto"); ?>" 
                                    alt="Gambar Publikasi">
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Tanggal Publikasi</label>
                                <input type="text" class="form-control" name = "date" id="date" placeholder="date" required value="<?php echo htmlspecialchars($date) ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" class="form-control" name = "title" id="title" placeholder="title" required value="<?php echo htmlspecialchars($title) ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="author" class="form-label">Penulis</label>
                                <input type="text" class="form-control" name = "author" id="author" placeholder="author" required value="<?php echo htmlspecialchars($guru_name) ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="jenis" class="form-label">Jenis Publikasi</label>
                                <input type="text" class="form-control" name="jenis" id="jenis" placeholder="jenis" value="<?php echo htmlspecialchars($jenis) ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Isi Kontent</label>
                                <textarea name="content" id="content" class="form-control" rows="10" placeholder="Tulis isi konten disini..." disabled><?php echo htmlspecialchars($content) ?></textarea>
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
</body>