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
    <title>Kotak Konseling | Siswa</title>
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

    // Cek apakah user sudah login dan memiliki role 'siswa'
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "siswa") {
        
        header("Location:/BK/users/index.php");
        exit;
    }
    
    include '../../../function/connectDB.php';
    $user_id = $_SESSION['user_id'];
    
    $sql = "SELECT kotak_konseling.id, kotak_konseling.date, kotak_konseling.message, kotak_konseling.guru_id, kotak_konseling.status, guru.id AS guru_id, guru.name AS guru_name, users.id AS user_id, anon_mapping.id AS anon_id, anon_mapping.konseling_id, anon_mapping.user_id FROM kotak_konseling
    JOIN guru ON kotak_konseling.guru_id = guru.id
    JOIN anon_mapping ON kotak_konseling.id = anon_mapping.konseling_id
    JOIN users ON anon_mapping.user_id = users.id
    WHERE users.id = ?
    ORDER BY id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    //Memeriksa apakah ada kotak konseling dengan status 'open'
    $sql_check_open = "SELECT COUNT(*) AS open_count FROM kotak_konseling 
                        JOIN anon_mapping ON kotak_konseling.id = anon_mapping.konseling_id
                        JOIN users ON anon_mapping.user_id = users.id
                        WHERE users.id = ? AND status = 'open'";
    $stmt_check_open = $conn->prepare($sql_check_open);
    $stmt_check_open->bind_param("i", $user_id);
    $stmt_check_open->execute();
    $result_check_open = $stmt_check_open->get_result();
    $row_check_open = $result_check_open->fetch_assoc();
    $has_open = $row_check_open['open_count'] > 0;

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
                <li class="sidebar-item ">
                    <a href="../profil/index.php" class="sidebar-link">
                        <i class='bx bxs-user-detail'></i>
                        <span>Profil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../pendaftaran_konseling/index.php" class="sidebar-link">
                        <i class='bx bxs-file-plus'></i>
                        <span>Pendaftaran Konseling</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../kunjungan/index.php" class="sidebar-link">
                        <i class='bx bx-list-plus'></i>
                        <span>Kunjungan</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
                        <i class='bx bxs-inbox'></i>
                        <span>Kotak Konseling</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../pelanggaran/index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="#">Kotak Konseling</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Log Kotak Konseling</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Log Kotak Konseling</h1>
                    <p>Jika kamu mempunyai masalah dan ingin menyampaikannya, silahkan klik tombol<b> + Kotak Konseling Baru</b> dibawah.</p>

                    <div class="card">
                        <div class="card-body">
                        <?php if (!$has_open): ?>
                            <a class="btn btn-primary mb-4" href="create.php" style="color: white; width: 190px;">
                                <i class="lni lni-plus"></i> Kotak Konseling Baru
                            </a>
                        <?php else: ?>
                            <div class="alert alert-warning alert-dismissible fade show">
                                <strong>Perhatian!</strong> Kamu masih memiliki kotak konseling yang berstatus "open". Harap selesaikan kotak konseling tersebut sebelum membuat yang baru.
                            </div>
                        <?php endif; ?>
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Waktu</th>
                                            <th scope="col">Guru</th>
                                            <th scope="col">Status</th>
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
                                                        <td>'.date("d F Y H:i:s", strtotime($row["date"])).'</td>
                                                        <td>'.$row['guru_name'].'</td>
                                                        <td>
                                                            <span class="badge ' . ($row['status'] === "open" ? "bg-success" : "bg-secondary") . '">
                                                                ' . ucfirst($row['status']) . '
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm btn-primary buttons" href="show.php?konseling_id='.$row['konseling_id'].'"><i class="bx bx-show" ></i></a>
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