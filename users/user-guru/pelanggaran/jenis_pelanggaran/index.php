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
    <link rel="stylesheet" href="../../../../assets/css/style_user.css">
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
    
    include '../../../../function/connectDB.php'; 

    $sql = "SELECT * FROM jenis_pelanggaran";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $jenis = htmlspecialchars(trim($_POST['jenis']));
        $sql = "SELECT COUNT(*) FROM jenis_pelanggaran WHERE jenis = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $jenis);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            // Jika username sudah ada, tampilkan pesan error
            $_SESSION['error'] = "Jenis Pelanggaran sudah ada!";
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh halaman agar pesan error muncul
            exit;
        } else {
            if (empty($jenis)) {
                $_SESSION['error'] = "Harap isi jenis pelanggaran!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }else{
                $sql = "INSERT INTO jenis_pelanggaran (id, jenis) VALUES (null, ?)";
                $addJenis = $conn->prepare($sql);
                $addJenis->bind_param("s", $jenis);
    
                if ($addJenis->execute() && $addJenis->affected_rows > 0) {
                    echo "<script>
                            alert('Jenis kunjungan baru berhasil ditambahkan!');
                            window.location.href = '/BK/users/user-guru/pelanggaran/jenis_pelanggaran/index.php';
                          </script>";
                    exit;
                } else {
                    $_SESSION['error'] = "Gagal menambahkan jenis kunjungan baru!";
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
                    <a href="../../dashboard.php">SPENTHREE</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="../../dashboard.php" class="sidebar-link">
                        <i class='bx bx-home' ></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../../profil/index.php" class="sidebar-link">
                        <i class='bx bxs-user-detail'></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../../informasi/index.php" class="sidebar-link">
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
                    <a href="../../kunjungan/index.php" class="sidebar-link">
                        <i class='bx bx-list-ul' ></i>
                        <span>Kunjungan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../../kotak_konseling/index.php" class="sidebar-link">
                        <i class='bx bxs-inbox'></i>
                        <span>Kotak Konseling</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="../index.php" class="sidebar-link">
                        <i class='bx bx-error'></i>
                        <span>Pelanggaran Siswa</span>
                    </a>
                </li>
            </ul>
            <div class="user-profile-footer p-2 d-flex align-items-center">
                <img src="../../../../assets/images/profile.jpg" alt="User Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <div class="user-info">
                    <h6 class="text-white mb-0"><?php echo ($_SESSION['name']) ?></h6>
                    <small><?php echo($_SESSION['role']) ?></small>
                </div>
            </div>
            <div class="sidebar-footer">
                <a href="../../../../function/logout.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="../index.php">Data Pelanggaran</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Jenis Pelanggaran</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Jenis Pelanggaran</h1>
                    <p>Anda Bisa menambahkan jenis pelanggaran baru.</p>

                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-warning alert-dismissible fade show">
                                        <strong>WARNING!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label for="jenis" class="form-label">Jenis Pelanggaran</label>
                                    <input type="text" class="form-control" id="jenis" name="jenis" placeholder="Masukkan jenis pelanggaran baru" required>
                                </div>
                                <div>
                                    <button class="btn btn-primary" type="submit" name="submit" style="color: white;">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div><br>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Jenis Pelanggaran</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $rowNumber = 1;  
                                            while ($row = $result->fetch_assoc()) {
                                                echo '
                                                    <tr>
                                                        <td>'.$rowNumber.'</td>
                                                        <td>'.$row['jenis'].'</td>
                                                        <td>
                                                            <a onclick="return confirm(`Apakah anda yakin?`)" class="btn btn-sm btn-danger buttons" href="delete.php?id='.$row['id'].'"><i class="lni lni-trash-3"></i></a>
                                                        </td>
                                                    </tr>
                                                ';$rowNumber++;
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
    <script src="../../../../assets/script/script_admin.js"></script>
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