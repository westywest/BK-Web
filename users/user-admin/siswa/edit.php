<?php 
    session_start();
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "admin") {
        // Redirect ke halaman login jika bukan admin
        header("Location: /BK/users/index.php");
        exit;
    }
    
    include '../../../function/connectDB.php';

    $id = $_GET['id'];
    $sql = "SELECT siswa.id AS siswa_id, siswa.user_id, siswa.nis, siswa.name, siswa.jk, siswa.kelas_id, siswa.phone, users.id AS user_id, kelas.id AS kelas_id, kelas.class_name
    FROM siswa 
    JOIN users on siswa.user_id = users.id 
    JOIN kelas on siswa.kelas_id = kelas.id
    WHERE siswa.id = ?";
    
    $datas = $conn->prepare($sql);
    $datas->bind_param("i",$id);
    $datas->execute();
    $resultSiswa = $datas->get_result();

    if ($data = $resultSiswa->fetch_assoc()) {
        $nis = $data['nis'];
        $name = $data['name'];
        $jk = $data['jk'];
        $kelas_id = $data['kelas_id'];
        $class_name = $data['class_name'];
        $phone = $data['phone'];
    }

    if(isset($_POST['submit'])) {
        $name = ($_POST['name']);
        $jk = $_POST['jk'];
        $kelas_id = $_POST['kelas_id'];
        $phone = $_POST['phone'];
        
        $sql = "UPDATE siswa SET name=?, jk=?, kelas_id=?, phone=?  WHERE id=?";
        $updStmt = $conn->prepare($sql);
        $updStmt->bind_param("ssisi", $name, $jk, $kelas_id, $phone, $id);
        $updStmt->execute();

        if ($updStmt->affected_rows > 0) {
            // Hapus pesan error jika ada
            unset($_SESSION['error']);
            echo "<script>
                    alert('Data berhasil diupdate!');
                    window.location.href = '/BK/users/user-admin/siswa/index.php';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Gagal Mengupdate Data!');
                    window.location.href = '/BK/users/user-admin/siswa/index.php';
                  </script>";
            exit;
        }
    }

    if (isset($_POST['reset_pass'])) {
        $new_password = $nis; // Reset password menjadi NIS
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash password
    
        // Update password di tabel `users`
        $updatePasswordSQL = "UPDATE users SET password=? WHERE id=?";
        $updatePasswordStmt = $conn->prepare($updatePasswordSQL);
        $updatePasswordStmt->bind_param("si", $hashed_password, $data['user_id']);
        $updatePasswordStmt->execute();
    
        if ($updatePasswordStmt->affected_rows > 0) {
            echo "<script>
                    alert('Password berhasil di-reset ke NIS!');
                    window.location.href = '/BK/users/user-admin/siswa/index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal mereset password!');
                    window.location.href = '/BK/users/user-admin/siswa/index.php';
                  </script>";
        }
        exit;
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
    <link rel="stylesheet" href="../../../assets/css/style_user.css">
    <title>Tambah Data</title>
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
                        <i class="lni lni-home-2"></i>
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
                            <li class="breadcrumb-item"><a href="index.php">Daftar Siswa</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Menambahkan Data</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Menambahkan Data</h1>
                    <p>Anda sedang menambahkan data siswa baru.</p>

                    <div class="card">
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])."?id=".$id ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $data['siswa_id'];?>">

                                <div class="mb-3">
                                    <label for="nis" class="form-label">NIS</label>
                                    <input type="text" class="form-control" id="nis" name="nis" placeholder="Masukkan NIS" required value="<?php echo htmlspecialchars($nis) ?>" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama" required value="<?php echo htmlspecialchars($name) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="jk" class="form-label">Jenis Kelamin</label>
                                    <select name="jk" id="jk" class="form-control" required>
                                        <option value="Laki-Laki" <?= $jk == 'Laki-Laki' ? 'selected' : null ?>>Laki-Laki</option>
                                        <option value="Perempuan" <?= $jk == 'Perempuan' ? 'selected' : null ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="08xxxxxxxxxx" required value="<?php echo htmlspecialchars($phone) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="kelas_id" class="form-label">Kelas</label>
                                    <select class="form-select" aria-label="Default select example" name="kelas_id">
                                        <?php
                                            echo "<option value=$kelas_id>$class_name</option>";
                                            $query = mysqli_query($conn, "SELECT * FROM kelas") or die (mysqli_error($conn));
                                            while($data = mysqli_fetch_array($query)){
                                                echo "<option value=$data[id]> $data[class_name]</option>";
                                            }
                                       ?>
                                    </select>     
                                </div>
                                <button class="btn btn-primary my-3" type="submit" name="submit" style="color: white;">Save</button>
                                <button class="btn btn-danger my-3" onclick="return confirm('Apakah anda yakin? Password akan direset menjadi NIS')" type="submit" name="reset_pass" style="color: white;">Reset Password</button>
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
    </body>
</html>