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
    <title>Informasi | Guru</title>
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

    // Ambil nilai ENUM dari database
    $field = 'jenis';
    $table = 'informasi';
    $sqlEnum = "SHOW COLUMNS FROM $table LIKE '$field'";
    $resultEnum = $conn->query($sqlEnum);
    $enumValues = [];
    if ($resultEnum && $rowEnum = $resultEnum->fetch_assoc()) {
        preg_match("/^enum\((.*)\)$/", $rowEnum['Type'], $matches);
        $enumValues = explode(",", str_replace("'", "", $matches[1]));
    }

    // Upload foto
    if (!empty($_FILES['foto']['name'])) { 
        $namaFile = $_FILES['foto']['name'];
        $ukuranFile = $_FILES['foto']['size'];
        $error = $_FILES['foto']['error'];
        $tmpName = $_FILES['foto']['tmp_name'];
    
        if ($error === 4) {
            // Tidak ada file di-upload
            $foto = "default.png";
        } else {
            $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
            $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    
            if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
                $_SESSION['error'] = "Yang anda upload bukan foto!";
            } elseif ($ukuranFile > 2048000) {
                $_SESSION['error'] = "Ukuran foto terlalu besar!";
            } else {
                $uploadDir = "../../../assets/images/uploads/";
                $fileName = time() . "_" . preg_replace('/[^A-Za-z0-9.\-_]/', '_', $namaFile);
                $targetFilePath = $uploadDir . $fileName;
    
                if (move_uploaded_file($tmpName, $targetFilePath)) {
                    $foto = $fileName; 
                } else {
                    $_SESSION['error'] = "Gagal mengunggah foto. Menggunakan foto default.";
                    $foto = "default.png";
                }
            }
        }
    } else {
        $foto = "default.png";
    }
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_SESSION['username'];
        $guru_id = $_SESSION['guru_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $jenis = $_POST['jenis'];

        if (empty($title)) {
            $_SESSION['error'] = "Judul tidak boleh kosong!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } elseif(empty($jenis) || $jenis == 0) {
            $_SESSION['error'] = "Harap pilih jenis informasi!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } elseif (empty($content)) {
            $_SESSION['error'] = "Isi informasi tidak boleh kosong!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $sql = "INSERT INTO informasi (guru_id, title, content, foto, jenis) VALUES (?, ?, ?, ?, ?)";
            $addInfo = $conn->prepare($sql);
            $addInfo->bind_param("issss", $guru_id, $title, $content, $foto, $jenis);

            if ($addInfo->execute() && $addInfo->affected_rows > 0) {
                echo "<script>
                        alert('Informasi berhasil dibuat!');
                        window.location.href = '/BK/users/user-guru/informasi/index.php';
                    </script>";
                exit;
            } else {
                $_SESSION['error'] = "Gagal membuat Informasi!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        }
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
                        <span>Informasi</span>
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
                        <span>Kunjungan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../kotak_konseling/index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="index.php">Informasi</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Informasi</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Data Informasi</h1>
                    <p>Pengumuman berupa artikel, berita, acara yang akan datang.</p>
                    <p>Kegiatan berupa acara yang telah dilaksanakan.</p>
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <!-- Menampilkan pesan error jika ada -->
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-warning alert-dismissible fade show">
                                        <strong>WARNING!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label for="jenis" class="form-label">Jenis Informasi</label>
                                    <select class="form-select" name="jenis" required>
                                        <option value="0">--Pilih Jenis Informasi--</option>
                                        <?php foreach ($enumValues as $value): ?>
                                            <option value="<?= htmlspecialchars($value) ?>"><?= ucfirst($value) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul</label>
                                    <input type="text" class="form-control" name="title" id="title" required placeholder="Judul Informasi">
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input type="file" class="form-control" name="foto" id="foto">
                                    <small class="form-text text-muted">Opsional. Jika tidak diunggah, akan menggunakan foto default.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Isi Kontent</label>
                                    <textarea name="content" id="content" class="form-control" rows="10" placeholder="Tulis isi konten disini..."></textarea>
                                </div>
                                
                                
                                <button class="btn btn-primary my-3" type="submit" name="submit" style="color: white;">Submit</button>
                            </form>
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