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
    $id = $_GET['id'];
    $sql = "SELECT kelas.id AS kelas_id, kelas.class_name, kelas.guru_id, guru.id as guru_id, guru.name FROM kelas 
    JOIN guru on kelas.guru_id = guru.id
    WHERE kelas.id = ?";

    $datas = $conn->prepare($sql);
    $datas->bind_param("i",$id);
    $datas->execute();
    $resultKelas = $datas->get_result();

    if ($data = $resultKelas->fetch_assoc()){
        $class_name = $data['class_name'];
        $guru_id = $data['guru_id'];
        $guru_name = $data['name'];
    }

    if(isset($_POST['submit'])){
        $class_name = $_POST['class_name'];
        $guru_id = $_POST['guru_id'];
        
        $sql = "UPDATE kelas SET class_name=?, guru_id=? WHERE id=?";
        $updStmt = $conn->prepare($sql);
        $updStmt->bind_param("sii", $class_name, $guru_id, $id);
        $updStmt->execute();

        if ($updStmt->affected_rows > 0) {
            // Hapus pesan error jika ada
            unset($_SESSION['error']);
            echo "<script>
                    alert('Data berhasil diupdate!');
                    window.location.href = '/BK/users/user-admin/kelas/index.php';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Gagal Mengupdate Data!');
                    window.location.href = '/BK/users/user-admin/kelas/index.php';
                  </script>";
            exit;
        }
    }

    ?>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
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
                            <li class="breadcrumb-item"><a href="index.php">Daftar Kelas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Edit Data</h1>
                    <p>Anda sedang mengedit data kelas <b><?php echo $class_name ?></b></p>

                    <div class="card">
                        <div class="card-body">
                            <form action="<?php echo $_SERVER['PHP_SELF']."?id=".$id ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $data['kelas_id'];?>">
                                <div class="mb-3">
                                    <label for="class_name" class="form-label">Nama Kelas</label>
                                    <input type="text" class="form-control" id="class_name" name="class_name" placeholder="class_name" required value="<?php echo htmlspecialchars($class_name) ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="guru_id" class="form-label">Guru Pengampu</label>
                                    <select class="form-select" aria-label="Default select example" name="guru_id">
                                        <?php
                                            echo "<option value=$guru_id>$guru_name</option>";
                                            $query = mysqli_query($conn, "SELECT * FROM guru") or die (mysqli_error($conn));
                                            while($data = mysqli_fetch_array($query)){
                                                echo "<option value=$data[id]> $data[name]</option>";
                                            }
                                        ?>
                                    </select>                           
                                </div>
                                
                                <p style="color:red; font-size: 12px;"><?php if(isset($_SESSION['error'])){ echo($_SESSION['error']);} ?></p>
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