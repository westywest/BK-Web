<?php 
    session_start();
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "admin") {
        // Redirect ke halaman login jika bukan admin
        header("Location: /BK/users/index.php");
        exit;
    }
    include '../../../function/connectDB.php';

    // Query dengan pengurutan kelas sesuai pola
    $sql = "SELECT kelas.id AS kelas_id, kelas.class_name, kelas.guru_id, guru.id as guru_id, guru.nip, guru.name 
    FROM kelas 
    JOIN guru ON kelas.guru_id = guru.id
    ORDER BY 
        CASE
            WHEN class_name LIKE 'VII%' THEN 1
            WHEN class_name LIKE 'VIII%' THEN 2
            WHEN class_name LIKE 'IX%' THEN 3
            ELSE 4
        END,
        class_name ASC";

    $datas = $conn->prepare($sql);
    $datas->execute();
    $resultKelas = $datas->get_result();

    // Fungsi untuk memastikan format nama kelas
    function formatClassName($className) {
    if (preg_match('/^(VII|VIII|IX)\s([A-Z])$/', $className, $matches)) {
    return $matches[1] . " " . $matches[2];
    }
    return $className; // Kembalikan nama asli jika format tidak sesuai
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../../assets/css/style_user.css">
    <title>Kelola Kelas</title>
    <style>
        .buttons{
            width: 40px;                
            font-size: 18px;              
        }.btn{
            display: inline-flex;       
            align-items: center;      
            justify-content: center;       
            height: 40px;                  
            padding: 0;                    
            border-radius: 5px;            
        }
    </style>
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
                            <li class="breadcrumb-item"><a href="#">Kelas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Daftar Kelas</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Daftar Kelas</h1>
                    <p>Untuk menambah Kelas silahkan klik tombol<b> + Tambah Data</b> dibawah.</p>

                    <div class="card">
                        <div class="card-body">
                            <a class="btn btn-primary mb-4" href="create.php" style="color: white; width: 135px;"><i class="lni lni-plus"></i> Tambah Data</a>
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Kelas</th>
                                            <th scope="col">Guru Pengampu</th>
                                            <th scope="col">NIP</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $rowNumber = 1;  
                                            while ($row = $resultKelas->fetch_assoc()) {
                                                echo '
                                                    <tr>
                                                        <td>'.$rowNumber.'</td>
                                                        <td>'.$row['class_name'].'</td>
                                                        <td>'.$row['name'].'</td>
                                                        <td>'.$row['nip'].'</td>
                                                        <td>
                                                            <a class="btn btn-sm btn-warning buttons" href="edit.php?id='.$row['kelas_id'].'"><i class="lni lni-pencil-1"></i></a>
                                                            <a onclick="return confirm(`Apakah anda yakin?`)" class="btn btn-sm btn-danger buttons" href="delete.php?id='.$row['kelas_id'].'"><i class="lni lni-trash-3"></i></a>
                                                            
                                                        </td>
                                                    </tr>
                                                ';
                                                $rowNumber++;  
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
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
    <script>
        $(document).ready(function () {
            $('#table').DataTable();
        });
    </script>
    </body>
</html>