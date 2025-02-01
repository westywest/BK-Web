<?php 
    session_start();
    // Cek apakah user sudah login dan memiliki role 'guru'
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "guru") {
        // Redirect ke halaman login jika bukan guru
        header("Location:/BK/users/index.php");
        exit;
    }
    
    include '../../../function/connectDB.php';
    
    $username = $_SESSION['username'];
    $fotoDefault = 'default.jpg';
    $user_id = $_SESSION['user_id'];

    if (!isset($_SESSION['guru_id'])) {
        echo "guru_id tidak ditemukan di session!";
        exit;
    }
    
    $guru_id = $_SESSION['guru_id'];

    $sql = "SELECT konseling.id, 
               konseling.siswa_id, 
               konseling.guru_id,
               konseling.keluhan,
               konseling.tanggal_konseling, 
               konseling.tindak_lanjut, 
               konseling.status,
               siswa.id AS siswa_id,
               siswa.name,
               siswa.kelas_id,
               kelas.id AS kelas_id,
               kelas.class_name 
        FROM konseling
        JOIN siswa ON konseling.siswa_id = siswa.id
        JOIN kelas ON siswa.kelas_id = kelas.id
        WHERE konseling.guru_id = ? AND (konseling.status = 'pending' OR konseling.status = 'confirmed')
        ORDER BY 
            CASE 
                WHEN konseling.status = 'confirmed' THEN 1
                WHEN konseling.status = 'pending' THEN 2
            END, konseling.id ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $result = $stmt->get_result();

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
    <title>Kotak Konseling | Guru</title>
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
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="#">Daftar Konseling</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Siswa Konseling</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Data Siswa Konseling</h1>
                    <p>Ketika status <span class="badge bg-warning">Pending</span>, silahkan atur jadwal untuk pertemuan konseling. Setelah itu status akan berubah menjadi <span class="badge bg-success">Confirmed</span> untuk mengkonfirmasi ke siswa jadwal konselingnya. Setelah melakukan konseling, guru mengisi field Tindak lanjut dari hasil konseling. Setelah itu status akan berubah menjadi <span class="badge bg-primary">Completed</span> dan berpindah ke halaman Log Konseling.</p>
                    <div class="card">
                        <div class="card-body">
                            <a class="btn btn-secondary mb-4" href="log.php" style="color: white; width: 50px;">
                                <i class='bx bx-history'></i>
                            </a>
                                <div class="table-responsive">
                                    <table class="table" id="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Nama</th>
                                                <th scope="col">Kelas</th>
                                                <th scope="col">Keluhan</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Tanggal Konseling</th>
                                                <th scope="col">Tindak Lanjut</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $rowNumber = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                $badgeClass = '';
                                                $dateField = ''; // Input tanggal
                                                $tindakLanjutField = ''; // Input tindak lanjut
                                                $dateValue = ''; // Tanggal read-only jika confirmed
                                                $submitButton = ''; // Tombol submit

                                                // Status Pending
                                                if ($row['status'] === "pending") {
                                                    $badgeClass = 'bg-warning'; // Warna kuning untuk pending
                                                    $dateField = '<input type="datetime-local" name="date" class="form-control" required>';
                                                    $submitButton = '<button type="submit" name="submit_pending" value="' . $row['id'] . '" class="btn btn-sm btn-primary">Konfirmasi</button>';
                                                }
                                                // Status Confirmed
                                                elseif ($row['status'] === "confirmed") {
                                                    $badgeClass = 'bg-success'; // Warna hijau untuk confirmed
                                                    $dateValue = $row['tanggal_konseling']; // Asumsi ada kolom tanggal konseling
                                                    $dateField = '<input type="text" class="form-control" value="' . $dateValue . '" readonly>';
                                                    $tindakLanjutField = '<input type="text" name="tindak_lanjut" class="form-control" placeholder="Isi Tindak Lanjut" required>';
                                                    $submitButton = '<button type="submit" name="submit_confirmed" value="' . $row['id'] . '" class="btn btn-sm btn-primary">Submit</button>';
                                                }

                                                echo '
                                                <tr>
                                                    <td>' . $rowNumber . '</td>
                                                    <td>' . htmlspecialchars($row["name"]) . '</td>
                                                    <td>' . htmlspecialchars($row["class_name"]) . '</td>
                                                    <td>' . htmlspecialchars($row["keluhan"]) . '</td>
                                                    <td>
                                                        <span class="badge ' . $badgeClass . '">' . ucfirst($row['status']) . '</span>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="update_konseling.php">
                                                            ' . $dateField . '
                                                    </td>
                                                    <td>
                                                            ' . $tindakLanjutField . '
                                                    </td>
                                                    <td>
                                                            ' . $submitButton . '
                                                        </form>
                                                    </td>
                                                </tr>';
                                                $rowNumber++;
                                            }
                                            ?>
                                        </tbody>

                                    </table>
                                </div>
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