<?php 
    session_start();

    // Cek apakah user sudah login dan memiliki role 'guru'
    if (!isset($_SESSION['status']) || $_SESSION['role'] !== "guru") {
        // Redirect ke halaman login jika bukan guru
        header("Location:/BK/users/index.php");
        exit;
    }
    
    include '../../../function/connectDB.php';
    $fotoDefault = 'default.jpg';
    $sql = "SELECT siswa.id AS siswa_id, siswa.user_id, siswa.nis, siswa.name AS nama_siswa, siswa.jk, siswa.phone, siswa.kelas_id, kelas.id AS kelas_id, kelas.class_name, kelas.guru_id, guru.id AS guru_id, guru.name AS nama_guru
    FROM siswa JOIN kelas ON siswa.kelas_id = kelas.id
    JOIN guru ON kelas.guru_id = guru.id
    ORDER BY 
        CAST(SUBSTRING_INDEX(kelas.class_name, ' ', 1) AS UNSIGNED) ASC,  -- Urutkan berdasarkan angka kelas
        SUBSTRING(kelas.class_name, LENGTH(SUBSTRING_INDEX(kelas.class_name, ' ', -1)) + 2) ASC,  -- Urutkan berdasarkan huruf kelas
        siswa.nis ASC";
    $datas = $conn->prepare($sql);
    $datas->execute();
    $resulSiswa = $datas->get_result();

    // Mengelompokkan siswa berdasarkan kelas
    $siswa_per_kelas = [];
    while ($row = $resulSiswa->fetch_assoc()) {
        $kelas = $row['class_name'];
        if (!isset($siswa_per_kelas[$kelas])) {
            $siswa_per_kelas[$kelas] = [];
        }
        $siswa_per_kelas[$kelas][] = $row;
    }

    // Filter kelas berdasarkan dropdown
    $selectedClass = $_GET['kelas'] ?? 'all';

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
    <title>Profil | Guru</title>
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
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="index.php">Siswa</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Daftar Siswa</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Daftar Siswa</h1>

                    <div class="card">
                        <div class="card-body">
                            <form method="GET">
                                <div class="d-flex align-items-center gap-2">
                                    <p class="mb-0">Anda sedang menampilkan data kelas:</p>
                                    <select name="kelas" id="kelasDropdown" class="form-select w-auto" onchange="this.form.submit()">
                                        <option value="all" <?php echo $selectedClass === 'all' ? 'selected' : ''; ?>>Semua Kelas</option>
                                        <?php foreach (array_keys($siswa_per_kelas) as $kelas) { ?>
                                            <option value="<?php echo $kelas; ?>" <?php echo $selectedClass === $kelas ? 'selected' : ''; ?>>
                                                <?php echo $kelas; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                            <form method="POST" action="../../cetak/cetakAll_students.php">
                                <input type="hidden" name="kelas" value="<?php echo htmlspecialchars($selectedClass); ?>">
                                <button type="submit" class="btn btn-success mt-3" style="color: white; width: 100px; margin-bottom: 10px;">Cetak PDF</button>
                            </form>

                            <!-- Tabel Siswa -->
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">NIS</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Kelas</th>
                                            <th scope="col">L/P</th>
                                            <th scope="col">No Telepon</th>
                                            <th scope="col">Guru Pengampu</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rowNumber = 1;

                                        // Tampilkan data siswa berdasarkan filter kelas
                                        foreach ($siswa_per_kelas as $kelas => $siswa) {
                                            if ($selectedClass !== 'all' && $kelas !== $selectedClass) {
                                                continue; // Skip kelas yang tidak sesuai filter
                                            }

                                            foreach ($siswa as $row) { ?>
                                                <tr>
                                                    <td><?php echo $rowNumber++; ?></td>
                                                    <td><?php echo htmlspecialchars($row['nis']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                                                    <td><?php echo htmlspecialchars($kelas); ?></td>
                                                    <td><?php echo htmlspecialchars($row['jk']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['nama_guru']); ?></td>
                                                    <td>
                                                        <a class="btn btn-sm btn-primary buttons" href="../../cetak/cetak_student.php?id=<?php echo $row['siswa_id']; ?>">
                                                            <i class="lni lni-printer"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php }
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