<?php 
    session_start();
    // Cek apakah user sudah login dan memiliki role 'guru'
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "guru") {
        // Redirect ke halaman login jika bukan guru
        header("Location:/BK/users/index.php");
        exit;
    }
    
    include '../../../function/connectDB.php';

    $fotoDefault = 'default.jpg';
    // Ambil nilai ENUM dari database
    $field = 'jenis';
    $table = 'publikasi';
    $sqlEnum = "SHOW COLUMNS FROM $table LIKE '$field'";
    $resultEnum = $conn->query($sqlEnum);
    $enumValues = [];
    if ($resultEnum && $rowEnum = $resultEnum->fetch_assoc()) {
        preg_match("/^enum\((.*)\)$/", $rowEnum['Type'], $matches);
        $enumValues = explode(",", str_replace("'", "", $matches[1]));
    }

    function upload() {
        $namaFile = $_FILES['foto']['name'];
        $ukuranFile = $_FILES['foto']['size'];
        $error = $_FILES['foto']['error'];
        $tmpName = $_FILES['foto']['tmp_name'];
    
        
        // Cek apakah tidak ada gambar yang di-upload
        if ($error === 4) {
            $_SESSION['error'] = "Upload gambar terlebih dahulu!";
            
            header("Location: " . $_SERVER['PHP_SELF']);
        exit;
        }
    
        // Cek apakah file yang di-upload adalah gambar
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
    
        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            $_SESSION['error'] = "Yang anda upload bukan foto!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    
        // Cek jika ukuran file terlalu besar
        if ($ukuranFile > 2097152) {
            $_SESSION['error'] = "Ukuran foto terlalu besar! Maksimal 2MB.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    
        // Lolos pengecekan, file siap di-upload
        // Generate nama file unik
        $foto = uniqid() . '.' . $ekstensiGambar;
    
        // Pindahkan file ke folder tujuan
        $uploadDir = '../../../assets/images/uploads/';
        if (!move_uploaded_file($tmpName, $uploadDir . $foto)) {
            echo "<script>
                alert('Gagal mengunggah file!');
                </script>";
            return false;
        }
    
        return $uploadDir . $foto; // Kembalikan path file yang di-upload
    }    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_SESSION['username'];
        $guru_id = $_SESSION['guru_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $jenis = $_POST['jenis'];
    
        $foto = upload(); // Memanggil fungsi upload()
    
    
        if (empty($title)) {
            $_SESSION['error'] = "Judul tidak boleh kosong!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } elseif (empty($jenis) || $jenis == 0) {
            $_SESSION['error'] = "Harap pilih jenis publikasi!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } elseif (empty($content)) {
            $_SESSION['error'] = "Isi tidak boleh kosong!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $sql = "INSERT INTO publikasi (guru_id, title, content, foto, jenis) VALUES (?, ?, ?, ?, ?)";
            $addInfo = $conn->prepare($sql);
            $addInfo->bind_param("issss", $guru_id, $title, $content, $foto, $jenis);
    
            if ($addInfo->execute() && $addInfo->affected_rows > 0) {
                echo "<script>
                        alert('publikasi berhasil dibuat!');
                        window.location.href = '/BK/users/user-guru/publikasi/index.php';
                    </script>";
                exit;
            } else {
                $_SESSION['error'] = "Gagal membuat publikasi!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        }
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../../assets/css/style_user.css">
    <title>Publikasi | Guru</title>
</head>
<body>
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
                            <li class="breadcrumb-item"><a href="index.php">Publikasi</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Publikasi</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Data Publikasi</h1>
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
                                    <label for="jenis" class="form-label">Jenis Publikasi</label>
                                    <select class="form-select" name="jenis" required>
                                        <option value="0">--Pilih Jenis Publikasi--</option>
                                        <?php foreach ($enumValues as $value): ?>
                                            <option value="<?= htmlspecialchars($value) ?>"><?= ucfirst($value) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul</label>
                                    <input type="text" class="form-control" name="title" id="title" required placeholder="Judul Publikasi">
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto</label> <small style="color: red;">*maks. ukuran 2 MB. Format : jpeg, jpg, png.</small>
                                    <input type="file" class="form-control" name="foto" id="foto">
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Isi Kontent</label>
                                    <textarea name="content" id="editor" class="form-control" rows="10" placeholder="Tulis isi konten disini..."></textarea>
                                </div>
                                
                                
                                <button class="btn btn-primary my-3" type="submit" name="submit" style="color: white;">Submit</button>
                            </form>
                        </div>
                    </div>
                    <footer class="pt-5 d-flex justify-content-between">
                        <span>Copyright © 2025 <a href="#">BKSPENTHREE.</a></span>
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
    <script src="https://cdn.tiny.cloud/1/vj6infomcca3x4pe8wkje1i3195ywbkcl8alu0cc0fibtmnt/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
    tinymce.init({
        selector: '#editor',
        plugins: 'lists',
        toolbar: 'bold italic underline | bullist numlist',
        height: 500
    });
    </script>
    </body>
</html>