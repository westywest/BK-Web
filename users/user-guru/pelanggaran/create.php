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
    <title>Pelanggaran Siswa | Guru</title>
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
    $selectedKelas = isset($_POST['kelas_id']) ? $_POST['kelas_id'] : null;

    $siswaQuery = null;
    if ($selectedKelas) {
        $siswaQuery = mysqli_query($conn, "SELECT * FROM siswa WHERE kelas_id = '$selectedKelas' ORDER BY name ASC");
        if (!$siswaQuery) {
            die("Error: " . mysqli_error($conn)); // Debug jika ada error
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
                <li class="sidebar-item">
                    <a href="../informasi/index.php" class="sidebar-link">
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
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="index.php">Data Pelanggaran</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Buat Pelanggaran</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Buat Pelanggaran</h1>
                    <p>Membuat catatan pelanggaran bagi siswa yang melakukan pelanggaran. Silahkan pilih kelas terlebih dahulu sebelum memilih siswa.</p>

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
                                    <label for="kelas_id" class="form-label">Kelas</label>
                                    <select name="kelas_id" id="kelas_id" class="form-select" onchange="this.form.submit()">
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php
                                        $kelasQuery = mysqli_query($conn, "SELECT * FROM kelas");
                                        while ($kelas = mysqli_fetch_assoc($kelasQuery)) {
                                            echo "<option value='{$kelas['id']}'" . 
                                                ($selectedKelas == $kelas['id'] ? ' selected' : '') . 
                                                ">{$kelas['class_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </form>
                            <?php if ($siswaQuery && mysqli_num_rows($siswaQuery) > 0) { ?>
                                <form action="submit_pelanggaran.php" method="post">
                                    <div class="mb-3">
                                        <label for="siswa_id" class="form-label">Nama Siswa</label>
                                        <select name="siswa_id" id="siswa_id" class="form-select" required>
                                            <option value="">-- Pilih Siswa --</option>
                                            <?php while ($siswa = mysqli_fetch_assoc($siswaQuery)) { ?>
                                                <option value="<?= $siswa['id'] ?>"><?= $siswa['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } else { ?>
                                    <span style='color: red;'>Tidak ada siswa untuk kelas ini.</span>
                                <?php } ?>
                                    <div class="mb-3">
                                        <label for="jenis_id" class="form-label">Jenis Pelanggaran</label>
                                        <select name="jenis_id" id="jenis_id" class="form-select" required>
                                            <option value="">-- Pilih Jenis Pelanggaran --</option>
                                            <?php
                                            $jenisQuery = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran");
                                            while ($jenis = mysqli_fetch_assoc($jenisQuery)) {
                                                echo "<option value='{$jenis['id']}'>{$jenis['jenis']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="note" class="form-label">Keterangan</label>
                                        <textarea name="note" id="note" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <button class="btn btn-primary" type="submit" name="submit" style="color: white;">Save</button>
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    </body>
</html>