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
        $nip = (trim($_POST['nip']));
        $name = htmlspecialchars(trim($_POST['name']));
        $phone = trim($_POST['phone']);
        $username = htmlspecialchars(trim($_POST['username']));
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    
        // Cek apakah username sudah ada di database
        $query = "SELECT COUNT(*) FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->free_result();
    
        if ($count > 0) {
            // Jika username sudah ada, tampilkan pesan error
            $_SESSION['error'] = "Username sudah terdaftar, silakan pilih yang lain!";
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh halaman agar pesan error muncul
            exit;
        } else {
            // Jika username belum ada, lanjutkan untuk memasukkan data
            $sql = "INSERT INTO users (id, username, password, role) VALUES (null, ?, ?, 'guru')";
            $datas = $conn->prepare($sql);
            $datas->bind_param("ss", $username, $pass);
            $datas->execute();
    
            if ($datas->affected_rows > 0) {
                $userId = $conn->insert_id;
    
                $sql = "INSERT INTO guru (id, user_id, nip, name, phone) VALUES (null, ?, ?, ?, ?)";
                $datas = $conn->prepare($sql);
                $datas->bind_param("isss", $userId, $nip, $name, $phone);
                
    
                if ($datas->execute() && $datas->affected_rows > 0) {
                    echo "<script>
                            alert('Data berhasil ditambahkan!');
                            window.location.href = '/BK/users/user-admin/guru/index.php';
                          </script>";
                    exit;
                    
                } else {
                    $_SESSION['error'] = "Gagal menyimpan data guru!";
                }
            } else {
                $_SESSION['error'] = "Gagal menyimpan data pengguna!";
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
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="index.php">Daftar Guru</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Menambahkan Data</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Menambahkan Data</h1>
                    <p>Anda sedang menambahkan data guru baru.</p>

                    <div class="card">
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                                <!-- Menampilkan pesan error jika ada -->
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-warning alert-dismissible fade show">
                                        <strong>WARNING!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP</label>
                                    <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukkan NIP" required>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="08xxxxxxxxxx" required>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
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