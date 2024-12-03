<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdn.lineicons.com/5.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../../assets/css/style_user.css">
    <title>Kelola Guru</title>
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
    $sql = "SELECT guru.id AS guru_id, guru.user_id, guru.nip, guru.name, guru.phone, users.id as user_id, users.username, users.role FROM guru 
    JOIN users on guru.user_id = users.id
    WHERE users.role = 'guru'
    ORDER BY id DESC";
    $datas = $conn->query($sql);
    ?>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-dashboard-square-1"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">SPENTHREE</a>
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
            </ul>
            <div class="user-profile-footer p-2 d-flex align-items-center">
                <img src="../../../assets/images/profile.jpg" alt="User Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <div class="user-info">
                    <h6 class="text-white mb-0">Maharani Dian Prawesty</h6>
                    <small>Guru</small>
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
                            <li class="breadcrumb-item"><a href="#">Guru</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Daftar Guru BK</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Daftar Guru BK</h1>
                    <p>Untuk menambah Guru silahkan klik tombol<b> + Tambah Data</b> dibawah.</p>

                    <div class="card">
                        <div class="card-body">
                            <a class="btn btn-primary mb-4" href="create.php" style="color: white"><i class="lni lni-plus"></i> Tambah Data</a>
                            <a href="../../cetak_author.php" class="btn btn-block btn-primary mb-4"><i class="lni lni-printer"></i></a>
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">NIP</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">No Telepon</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($datas as $key => $data){
                                                echo '
                                                    <tr>
                                                        <td>'.($key+1).'</td>
                                                        <td>'.$data['nip'].'</td>
                                                        <td>'.$data['username'].'</td>
                                                        <td>'.$data['name'].'</td>
                                                        <td>'.$data['phone'].'</td>
                                                        <td>
                                                            <a class="btn btn-sm btn-info buttons" href="show.php?id='.$data['id'].'" style="color: white;">Lihat</a>
                                                            <a class="btn btn-sm btn-success buttons" href="edit.php?id='.$data['id'].'" style="color: white;">Edit</a>
                                                            <a onclick="return confirm(`Apakah anda yakin?`)" class="btn btn-sm btn-danger buttons" href="delete.php?id='.$data['id'].'" style="color: white;">Hapus</a>
                                                            <a class="btn btn-sm btn-primary buttons" href="../../cetak_detailNews.php?id='.$data['id'].'"><i class="fa-solid fa-print"></i></a>
                                                        </td>
                                                    </tr>
                                                ';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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