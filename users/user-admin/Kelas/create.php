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
    <title>Kelola Kelas</title>
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
        $class_name = htmlspecialchars(trim($_POST['class_name']));
        $guru_id = intval($_POST['guru_id']);

        // Cek apakah username sudah ada di database
        $query = "SELECT COUNT(*) FROM kelas WHERE class_name = ?";
        $checkUSN = $conn->prepare($query);
        $checkUSN->bind_param("s", $class_name);
        $checkUSN->execute();
        $checkUSN->bind_result($count);
        $checkUSN->fetch();
        $checkUSN->close();
        
        // Validasi input
        if ($count > 0) {
            // Jika username sudah ada, tampilkan pesan error
            $_SESSION['error'] = "Kelas sudah ada!";
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh halaman agar pesan error muncul
            exit;
        } else {
            if (empty($class_name)) {
                $_SESSION['error'] = "Semua field wajib diisi!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } elseif (empty($guru_id) || $guru_id == 0) {
                $_SESSION['error'] = "Harap pilih guru pengampu yang valid!";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $sql = "INSERT INTO kelas (id, class_name, guru_id) VALUES (null, ?, ?)";
                $insKelas = $conn->prepare($sql);
                $insKelas->bind_param("si", $class_name, $guru_id);
        
                if ($insKelas->execute() && $insKelas->affected_rows > 0) {
                    echo "<script>
                            alert('Data berhasil ditambahkan!');
                            window.location.href = '/BK/users/user-admin/kelas/index.php';
                          </script>";
                    exit;
                } else {
                    $_SESSION['error'] = "Gagal menyimpan data!";
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
                <li class="sidebar-item">
                    <a href="../siswa/index.php" class="sidebar-link">
                        <i class="lni lni-user-multiple-4"></i>
                        <span>Siswa</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="../Kelas/index.php" class="sidebar-link">
                        <i class='bx bx-spreadsheet' ></i>
                        <span>Kelas</span>
                    </a>
                </li>
            </ul>
            <div class="user-profile-footer p-2 d-flex align-items-center">
                <img src="../../../assets/images/profile.jpg" alt="User Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <div class="user-info">
                    <h6 class="text-white mb-0"><?php echo ($_SESSION['username']) ?></h6>
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
                            <li class="breadcrumb-item"><a href="index.php">Daftar Kelas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Kelas</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Tambah Kelas</h1>

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
                                    <label for="class_name" class="form-label">Nama Kelas</label>
                                    <input type="text" class="form-control" id="class_name" name="class_name" placeholder="Masukkan Kelas" required>
                                </div>
                                <div class="mb-3">
                                    <label for="guru_id" class="form-label">Guru Pengampu</label>
                                    <select class="form-select" aria-label="Default select example" name="guru_id" required>
                                        <option value="0">--Pilih Guru Pengampu--</option>
                                        <?php
                                            $query = mysqli_query($conn, "SELECT * FROM guru") or die (mysqli_error($conn));
                                            while($data = mysqli_fetch_array($query)){
                                                echo "<option value=$data[id]>$data[nip] - $data[name]</option>";
                                            }
                                        ?>
                                    </select>
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