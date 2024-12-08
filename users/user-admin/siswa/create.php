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
    <title>Tambah Data</title>
</head>
<body>
    <?php 
    session_start();
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "admin") {
        // Redirect ke halaman login jika bukan admin
        header("Location: /BK/users/index.php");
        exit;
    }
    
    include '../../../function/connectDB.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nis = intval(trim($_POST['nis']));
        $name = htmlspecialchars(trim($_POST['name']));
        $jk = $_POST['jk'];
        $tmp_lahir = trim($_POST['tmp_lahir']);
        $tgl_lahir = trim($_POST['tgl_lahir']);
        $phone = trim($_POST['phone']);
        $kelas_id = intval($_POST['kelas_id']);
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    
        // Cek apakah NIS sudah ada di database
        $query = "SELECT COUNT(*) FROM siswa WHERE nis = ?";
        $checkNIS = $conn->prepare($query);
        $checkNIS->bind_param("i", $nis);
        $checkNIS->execute();
        $checkNIS->bind_result($count);
        $checkNIS->fetch();
        $checkNIS->close();

        // Cek apakah NIS sudah digunakan sebagai username di tabel users
        $query = "SELECT COUNT(*) FROM users WHERE username = ?";
        $checkUSN = $conn->prepare($query);
        $checkUSN->bind_param("s", $nis);
        $checkUSN->execute();
        $checkUSN->bind_result($countUsers);
        $checkUSN->fetch();
        $checkUSN->close();

        if ($count > 0) {
            $_SESSION['error'] = "NIS sudah terdaftar!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            if (empty($nis) || empty($name) || empty($tmp_lahir) || empty($tgl_lahir) || empty($phone)){
                $_SESSION['error'] = "Semua field wajib diisi!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } elseif (empty($jk) || $jk == 0) {
                $_SESSION['error'] = "Harap pilih jenis kelamin!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } elseif (empty($kelas_id) || $kelas_id == 0) {
                $_SESSION['error'] = "Harap pilih kelas!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } elseif (empty($tgl_lahir)) {
                $_SESSION['error'] = "Tanggal Lahir harus diisi!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                // Tambahkan data ke tabel users
                $sql = "INSERT INTO users (id, username, password, role) VALUES (null, ?, ?, 'siswa')";
                $datas = $conn->prepare($sql);
                $datas->bind_param("ss", $nis, $pass);
                $datas->execute();
            
                if ($datas->affected_rows > 0) {
                    $userId = $conn->insert_id;
            
                    // Pastikan userId valid
                    if (!$userId) {
                        $_SESSION['error'] = "Gagal membuat akun pengguna!";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    }
            
                    // Tambahkan data ke tabel siswa
                    $sql = "INSERT INTO siswa (id, user_id, nis, name, jk, tmp_lahir, tgl_lahir, phone, kelas_id) 
                            VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $datas = $conn->prepare($sql);
                    $datas->bind_param("iissssss", $userId, $nis, $name, $jk, $tmp_lahir, $tgl_lahir, $phone, $kelas_id);
            
                    if ($datas->execute() && $datas->affected_rows > 0) {
                        echo "<script>
                                alert('Data berhasil ditambahkan!');
                                window.location.href = '/BK/users/user-admin/siswa/index.php';
                            </script>";
                        exit;
                    } else {
                        $_SESSION['error'] = "Gagal menyimpan data siswa!";
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    }
                } else {
                    $_SESSION['error'] = "Gagal menyimpan data pengguna!";
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                }
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
                    <a href="../dashboard.php">BK SPENTHREE</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="../dashboard.php" class="sidebar-link">
                        <i class="lni lni-home-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../guru/index.php" class="sidebar-link">
                        <i class="lni lni-user-4"></i>
                        <span>Guru</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="../siswa/index.php" class="sidebar-link">
                        <i class="lni lni-user-multiple-4"></i>
                        <span>Siswa</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../Kelas/index.php" class="sidebar-link">
                        <i class='bx bx-spreadsheet' ></i>
                        <span>Kelas</span>
                    </a>
                </li>
            </ul>
            <div class="user-profile-footer p-2 d-flex align-items-center">
                <img src="../../../assets/images/profile.jpg" alt="User Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <div class="user-info">
                    <h6 class="text-white mb-0">Administrator</h6>
                    <small><?php echo($_SESSION['username']) ?></small>
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
                            <li class="breadcrumb-item"><a href="index.php">Daftar Siswa</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Menambahkan Data</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Menambahkan Data</h1>
                    <p>Anda sedang menambahkan data siswa baru.</p>

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
                                    <label for="nis" class="form-label">NIS</label>
                                    <input type="text" class="form-control" id="nis" name="nis" placeholder="Masukkan NIS" required>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jk" class="form-label">Jenis Kelamin</label>
                                    <select name="jk" id="jk" class="form-control" required>
                                        <option value="">--Pilih Jenis Kelamin--</option>
                                        <option value="Laki-Laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tmp_lahir" class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control" id="tmp_lahir" name="tmp_lahir" placeholder="Masukkan Tempat Lahir" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                    <input 
                                            type="date" 
                                            class="form-control" 
                                            id="tgl_lahir" 
                                            name="tgl_lahir" 
                                            required
                                            min="1900-01-01" 
                                            max="2024-12-31">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="08xxxxxxxxxx" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kelas_id" class="form-label">Kelas</label>
                                    <select class="form-select" aria-label="Default select example" name="kelas_id" required>
                                        <option value="0">--Pilih Kelas--</option>
                                        <?php
                                            $query = mysqli_query($conn, "SELECT * FROM kelas") or die (mysqli_error($conn));
                                            while($data = mysqli_fetch_array($query)){
                                                echo "<option value=$data[id]>$data[class_name]</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="pass" class="form-label">Password</label>
                                    <input type="text" class="form-control" id="pass" name="pass" placeholder="**********" required>
                                </div>
                                <button class="btn btn-primary my-3" type="submit" name="submit" style="color: white;">Save</button>
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