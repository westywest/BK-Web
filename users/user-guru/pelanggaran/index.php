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
    <title>Pelanggaran Siswa | Guru</title>
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
    <?php
    session_start();
    // Cek apakah user sudah login dan memiliki role 'guru'
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "guru") {
        // Redirect ke halaman login jika bukan guru
        header("Location:/BK/users/index.php");
        exit;
    }
    
    include '../../../function/connectDB.php'; 
    $sql = "SELECT pelanggaran.id AS pelanggaran_id,
            pelanggaran.siswa_id, 
            pelanggaran.jenis_id, 
            pelanggaran.date, 
            pelanggaran.note, 
            siswa.id AS siswa_id, 
            siswa.name AS siswa_name, 
            siswa.kelas_id,
            kelas.id AS kelas_id,
            kelas.class_name,
            jenis_pelanggaran.id AS jenis_id, 
            jenis_pelanggaran.jenis 
        FROM pelanggaran
        JOIN siswa ON pelanggaran.siswa_id = siswa.id
        JOIN kelas ON siswa.kelas_id = kelas.id
        JOIN jenis_pelanggaran ON pelanggaran.jenis_id = jenis_pelanggaran.id";

    $pelanggaran = $conn->prepare($sql);
    $pelanggaran->execute();
    $result = $pelanggaran->get_result();
    
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
                <li class="sidebar-item">
                    <a href="../profil/index.php" class="sidebar-link">
                        <i class='bx bxs-user-detail'></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../informasi/index.php" class="sidebar-link">
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
                    <a href="../kunjungan/index.php" class="sidebar-link">
                        <i class='bx bx-list-ul' ></i>
                        <span>Kunjungan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../kotak_konseling/index.php" class="sidebar-link">
                        <i class='bx bxs-inbox'></i>
                        <span>Kotak Konseling</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
                        <i class='bx bx-error'></i>
                        <span>Pelanggaran Siswa</span>
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
                            <li class="breadcrumb-item"><a href="#">Pelanggaran Siswa</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Pelanggaran</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Data Pelanggaran</h1>
                    <p>Berisi data siswa yang melakukan pelanggaran, silahkan klik tombol<b> + Pelanggaran Baru</b>. Jika ingin menambahkan jenis pelanggaran baru, silahkan klik tombol<b> + Jenis Pelanggaran</b> dibawah.</p>

                    <div class="card">
                        <div class="card-body">
                            <a class="btn btn-primary mb-4" href="create.php" style="color: white; width: 165px;"><i class="lni lni-plus"></i> Pelanggaran Baru</a>
                            <a class="btn btn-primary mb-4" href="jenis_pelanggaran/index.php" style="color: white; width: 170px;"><i class="lni lni-plus"></i> Jenis Pelanggaran</a>
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tanggal/Waktu</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Kelas</th>
                                            <th scope="col">Jenis Pelanggaran</th>
                                            <th scope="col">Keterangan</th>
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
                                                        <td>'.date("d F Y", strtotime($row["date"])).'</td>
                                                        <td>'.$row['siswa_name'].'</td>
                                                        <td>'.$row['class_name'].'</td>
                                                        <td>'.$row['jenis'].'</td>
                                                        <td>'.$row['note'].'</td>
                                                        <td>
                                                            <a class="btn btn-sm btn-warning buttons" href="edit.php?id='.$row['pelanggaran_id'].'"><i class="lni lni-pencil-1"></i></a>
                                                            <a onclick="return confirm(`Apakah anda yakin?`)" class="btn btn-sm btn-danger buttons" href="delete.php?id='.$row['pelanggaran_id'].'"><i class="lni lni-trash-3"></i></a>
                                                            <a class="btn btn-sm btn-primary buttons" href="../../cetak_detailNews.php?id=' . $row['pelanggaran_id'] . '"><i class="lni lni-printer"></i></a>
                                                        </td>
                                                    </tr>
                                                '; $rowNumber++;
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
</body>