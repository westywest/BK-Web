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
    <title>Profil | Guru</title>
    <style>
    .table {
        border-spacing: 0;
        border-collapse: collapse;
        width: 100%;
    }
    .table td {
        padding: 5px;
        vertical-align: top;
    }
    .table .data-label {
        width: 30%; /* Lebar kolom label */
        text-align: left;
        font-weight: bold;
    }
    </style>
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
    
    // Ambil username dari session
    $username = $_SESSION['username'];
    $fotoDefault = 'default.jpg';

    $sql = "SELECT guru.id AS guru_id, guru.nip, guru.name, guru.user_id, guru.phone, guru.foto, 
            users.id AS user_id, users.username, users.password
            FROM guru
            JOIN users ON guru.user_id = users.id
            WHERE users.username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data_guru = $result->fetch_assoc();
        $_SESSION = array_merge($_SESSION, $data_guru);
    } else {
        $_SESSION['error'] = "Data guru tidak ditemukan.";
        header('Location: /BK/users/index.php');
        exit;
    }
    
    // Fungsi untuk upload file
    function upload()
    {
        $file = $_FILES['foto'];
        $namaFile = $file['name'];
        $ukuranFile = $file['size'];
        $error = $file['error'];
        $tmpName = $file['tmp_name'];
    
        if ($error === 4) {
            $_SESSION['error'] = "Upload gambar terlebih dahulu!";
            
            header("Location: " . $_SERVER['PHP_SELF']);
        exit;
        }
    
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    
        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            $_SESSION['error'] = "File bukan gambar yang valid!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    
        if ($ukuranFile > 2097152) {
            $_SESSION['error'] = "Ukuran file maksimal 2MB.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    
        $fileNameNew = uniqid() . '.' . $ekstensiGambar;
        $uploadDir = '../../../assets/images/teacher/';
        if (!move_uploaded_file($tmpName, $uploadDir . $fileNameNew)) {
            $_SESSION['error'] = "Gagal mengunggah file.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    
        return $fileNameNew;
    }
    
    // Jika form di-submit
    if (isset($_POST['submit_foto'])) {
        $fotoLama = $_POST['fotoLama'];
        $fotoBaru = upload();
        $foto = $fotoBaru ? $fotoBaru : $fotoLama;
    
        if ($fotoBaru && $fotoLama !== $fotoDefault && file_exists("../../../assets/images/teacher/$fotoLama")) {
            unlink("../../../assets/images/teacher/$fotoLama");
        }
    
        $sql = "UPDATE guru SET foto = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $foto, $_SESSION['guru_id']);
        if ($stmt->execute()) {
            $_SESSION['foto'] = $foto;
            echo "<script>
                        alert('Foto berhasil diupdate!');
                        window.location.href = '/BK/users/user-guru/profil/index.php';
                      </script>";
                exit;
        } else {
            $_SESSION['error'] = "Gagal memperbarui data foto.";
        }
    }


    // UBAH PASSWORD
    if (isset($_POST['submit_pass'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Validasi password baru dan konfirmasi password
        if ($new_password !== $confirm_password) {
            echo "<script>
                    alert('Konfirmasi password tidak sesuai!');
                    window.location.href = '/BK/users/user-guru/profil/index.php';
                </script>";
            exit;
        }

        // Validasi panjang password baru
        if (strlen($new_password) < 8) {
            echo "<script>
                    alert('Password baru harus minimal 8 karakter!');
                    window.location.href = '/BK/users/user-guru/profil/index.php';
                </script>";
            exit;
        }

        // Validasi password lama
        if (password_verify($current_password, $_SESSION["password"])) {
            // Enkripsi password baru
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password di database
            $update_sql = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $update_sql->bind_param("ss", $hashed_new_password, $username);

            if ($update_sql->execute()) {
                echo "<script>
                        alert('Password berhasil diupdate!');
                        window.location.href = '/BK/users/user-guru/profil/index.php';
                    </script>";
                exit;
            } else {
                echo "<script>
                        alert('Terjadi kesalahan saat memperbarui password!');
                        window.location.href = '/BK/users/user-guru/profil/index.php';
                    </script>";
                exit;
            }
        } else {
            // Password lama tidak sesuai
            echo "<script>
                    alert('Password lama tidak sesuai!');
                    window.location.href = '/BK/users/user-guru/profil/index.php';
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
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="#">Profil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profil User</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Profil <?php echo($_SESSION['name'])?></h1>
                    <p>Anda dapat mengganti password pada halaman ini!</p>

                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="fotoLama" value="<?php echo htmlspecialchars($_SESSION['foto']); ?>">
                                <table class="table">
                                    <tr>
                                        <td class="data-label">NIP</td>
                                        <td><?php echo htmlspecialchars($_SESSION['nip']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="data-label">Nama</td>
                                        <td><?php echo htmlspecialchars($_SESSION['name']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="data-label">Username</td>
                                        <td><?php echo htmlspecialchars($_SESSION['username']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="data-label">No. Telepon</td>
                                        <td><?php echo htmlspecialchars($_SESSION['phone']); ?></td>
                                    </tr>
                                    <tr>
                                        <?php if (isset($_SESSION['error'])): ?>
                                            <div class="alert alert-warning alert-dismissible fade show">
                                                <strong>WARNING!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <td colspan="2">
                                            <input type="file" class="form-control" name="foto" id="foto">
                                            <small style="color: red;">*maks. ukuran 2 MB. Format : jpeg, jpg, png.</small><br>
                                            <img src="../../../assets/images/teacher/<?php echo htmlspecialchars($_SESSION['foto'] ?: $fotoDefault); ?>" alt="Foto Guru" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                                        </td>
                                    </tr>
                                </table>

                                <button type="submit" name="submit_foto" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
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
                                        <small style="color: red;"> *password minimal 8 karakter</small>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                    <button type="submit" name="submit_pass" class="btn btn-primary">Update Password</button>
                                </form>
                            </div>
                        </div>

                    <footer class="pt-5 d-flex justify-content-between">
                        <span>Copyright © 2025 <a href="#">BKSPENTHREE</a></span>
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