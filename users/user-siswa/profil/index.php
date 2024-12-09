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
    <title>Profil | Siswa</title>
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
    
    $username = $_SESSION['username'];
    // Query untuk mengambil data guru berdasarkan username yang login
    $sql = "SELECT siswa.id AS siswa_id, siswa.nis, siswa.name, siswa.jk, siswa.tmp_lahir, siswa.tgl_lahir, siswa.phone, siswa.kelas_id, users.username, users.password, kelas.id AS kelas_id, kelas.class_name, guru.id AS guru_id, guru.nip, guru.name AS guru_name
            FROM siswa
            JOIN users ON siswa.user_id = users.id
            JOIN kelas ON siswa.kelas_id = kelas.id
            JOIN guru ON kelas.guru_id = guru.id
            WHERE users.username = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        $nis = $user['nis'];
        $jk = $user['jk'];
        $tmp_lahir = $user['tmp_lahir'];
        $tgl_lahir = date("d F Y", strtotime($user["tgl_lahir"]));
        $phone = $user['phone'];
        $guru_name = $user['guru_name'];
        $nip = $user['nip'];
        $class_name = $user['class_name'];
        $hashed_password = $user['password'];
        
    }

    //UBAH PASSWORD
    if (isset($_POST['submit'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
    
        
        // Validasi password lama
        if (password_verify($current_password, $hashed_password)) {
            // Enkripsi password baru
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
    
            // Update password di database
            $update_sql = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $update_sql->bind_param("ss", $hashed_new_password, $username);
    
            
            if ($new_password !== $confirm_password) {
                echo "<script>
                        alert('Konfirmasi password tidak sesuai!');
                        window.location.href = '/BK/users/user-siswa/profil/index.php';
                    </script>";
                exit;
            }
            if (strlen($new_password) < 8) {
                echo "<script>
                        alert('Password harus memiliki minimal 8 karakter!');
                        window.location.href = '/BK/users/user-siswa/profil/index.php';
                      </script>";
                exit;
            }
            if ($update_sql->execute()) {
                echo "<script>
                        alert('Password berhasil diupdate!');
                        window.location.href = '/BK/users/user-siswa/profil/index.php';
                      </script>";
                exit;
            } else {
                echo "<script>
                        alert('Terjadi kesalahan saat memperbarui password!');
                        window.location.href = '/BK/users/user-siswa/profil/index.php';
                      </script>";
                exit;
            }
        } else {
            // Password lama tidak sesuai
            echo "<script>
                    alert('Password lama tidak sesuai!');
                    window.location.href = '/BK/users/user-siswa/profil/index.php';
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
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
                        <i class='bx bx-user' ></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../kunjungan/index.php" class="sidebar-link">
                        <i class='bx bx-list-plus'></i>
                        <span>Kunjungan</span>
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
                            <li class="breadcrumb-item"><a href="#">Profil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Overview</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Profil <?php echo($_SESSION['name'])?></h1>
                    <p>Anda dapat mengganti password pada halaman ini!</p>

                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <td class="data-label"><b>NIS</b></td>
                                    <td><?php echo htmlspecialchars($nis); ?></td>
                                </tr>
                                <tr>
                                    <td class="data-label"><b>Nama</b></td>
                                    <td><?php echo htmlspecialchars($_SESSION['name']); ?></td>
                                </tr>
                                <tr>
                                    <td class="data-label"><b>Jenis Kelamin</b></td>
                                    <td><?php echo htmlspecialchars($jk); ?></td>
                                </tr>
                                <tr>
                                    <td class="data-label"><b>Tempat, Tanggal Lahir</b></td>
                                    <td><?php echo htmlspecialchars($tmp_lahir);?>, <?php echo htmlspecialchars($tgl_lahir); ?></td>
                                </tr>
                                <tr>
                                    <td class="data-label"><b>No. Telepon</b></td>
                                    <td><?php echo htmlspecialchars($phone); ?></td>
                                </tr>
                                <tr>
                                    <td class="data-label"><b>Kelas</b></td>
                                    <td><?php echo htmlspecialchars($class_name);?></td>
                                </tr>
                                <tr>
                                    <td class="data-label"><b>Guru Pengampu</b></td>
                                    <td><?php echo htmlspecialchars($guru_name);?> (NIP. <?php echo htmlspecialchars($nip); ?>)</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <br>
                    <b>Ubah Password</b>
                    <div class="card">
                            <div class="card-body">
                                <form action="" method="post">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Password Lama</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">Password Baru</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary">Update Password</button>
                                </form>
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
    <script src="../../../assets/script/script_admin.js"></script>
    </body>
</html>