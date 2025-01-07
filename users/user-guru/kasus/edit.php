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
    <title>Catatan Kasus Siswa | Guru</title>
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
    $fotoDefault = 'default.jpg';
    $id = $_GET['id'];
    $sql = "SELECT kasus.id AS kasus_id,
            kasus.siswa_id, 
            kasus.jenis_id, 
            kasus.date, 
            kasus.note, 
            siswa.id AS siswa_id, 
            siswa.name AS siswa_name, 
            siswa.kelas_id,
            kelas.id AS kelas_id,
            kelas.class_name,
            Jenis_layanan.id AS jenis_id, 
            Jenis_layanan.jenis 
        FROM kasus
        JOIN siswa ON kasus.siswa_id = siswa.id
        JOIN kelas ON siswa.kelas_id = kelas.id
        JOIN Jenis_layanan ON kasus.jenis_id = Jenis_layanan.id
        WHERE kasus.id = ?";

    $kasus = $conn->prepare($sql);
    $kasus->bind_param("i", $id);
    $kasus->execute();
    $result = $kasus->get_result();

    if ($data = $result->fetch_assoc()){
        $siswa_name = $data['siswa_name'];
        $class_name = $data['class_name'];
        $jenis_id = $data['jenis_id'];
        $jenis = $data['jenis'];
        $note = $data['note'];
    }

    if(isset($_POST['submit'])) {
        $jenis_id = $_POST['jenis_id'];
        $note = $_POST['note'];

        $sql = "UPDATE kasus SET jenis_id=?, note=? WHERE id=?";
        $updStmt = $conn->prepare($sql);
        $updStmt->bind_param("isi", $jenis_id, $note, $id);
        $updStmt->execute();

        if ($updStmt->affected_rows > 0) {
            // Hapus pesan error jika ada
            unset($_SESSION['error']);
            echo "<script>
                    alert('Data berhasil diupdate!');
                    window.location.href = '/BK/users/user-guru/kasus/index.php';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Gagal Mengupdate Data!');
                    window.location.href = '/BK/users/user-guru/kasus/index.php';
                  </script>";
            exit;
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
                <li class="sidebar-item">
                    <a href="../kotak_saran/index.php" class="sidebar-link">
                        <i class='bx bxs-inbox'></i>
                        <span>Kotak Saran</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="index.php">Catatan Kasus</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Kasus</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Edit Kasus</h1>
                    <p>Anda sedang mengedit data </p>

                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $data['kasus_id'];?>">
                                <!-- Menampilkan pesan error jika ada -->
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-warning alert-dismissible fade show">
                                        <strong>WARNING!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="name" disabled value="<?php echo htmlspecialchars($siswa_name) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="class_name" class="form-label">Kelas</label>
                                    <input type="text" class="form-control" id="class_name" name="class_name" placeholder="class_name" disabled value="<?php echo htmlspecialchars($class_name) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_id" class="form-label">Jenis Layanan</label>
                                    <select class="form-select" aria-label="Default select example" name="jenis_id">
                                        <?php
                                            echo "<option value=$jenis_id>$jenis</option>";
                                            $query = mysqli_query($conn, "SELECT * FROM jenis_layanan") or die (mysqli_error($conn));
                                            while($data = mysqli_fetch_array($query)){
                                                echo "<option value=$data[id]> $data[jenis]</option>";
                                            }
                                       ?>
                                    </select>     
                                </div>
                                <div class="mb-3">
                                    <label for="note" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="note" name="note" placeholder="Keterangan" required value="<?php echo htmlspecialchars($note) ?>">
                                </div>
                                <button class="btn btn-primary" type="submit" name="submit" style="color: white;">Save</button>
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    </body>
</html>