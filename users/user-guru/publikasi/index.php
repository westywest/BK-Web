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
    <title>Publikasi | Guru</title>
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

    // Ambil kolom jenis dari tabel publikasi
    $sqlJenis = "DESCRIBE publikasi jenis";
    $result = $conn->query($sqlJenis);
    $row = $result->fetch_assoc();

    // Mengambil nilai ENUM yang tersedia
    $enumValues = str_replace("'", "", substr($row['Type'], 5, -1));
    $enumValues = explode(",", $enumValues);

    $selectedJenis = $_GET['jenis'] ?? 'all';

    //Menampilkan data dari publikasi
    $sql = "SELECT publikasi.id AS publikasi_id, 
            publikasi.guru_id, 
            publikasi.date, 
            publikasi.title, 
            publikasi.content, 
            publikasi.foto, 
            publikasi.jenis, 
            guru.id AS guru_id,
            guru.name AS guru_name
            FROM publikasi 
        JOIN guru ON publikasi.guru_id = guru.id
        ORDER BY publikasi.date DESC, publikasi.id DESC";
    
    $dataInfo = $conn->prepare($sql);
    $dataInfo->execute();
    $resultInfo = $dataInfo->get_result();

    //mengelompokan publikasi berdasarkan jenisnya
    $info_jenis = [];
    while ($row = $resultInfo->fetch_assoc()) {
        $jenis = $row['jenis'];
        if(!isset($info_jenis[$jenis])) {
            $info_jenis[$jenis] = [];
        }
        $info_jenis[$jenis][] = $row;
    }
    $selectedJenis = $_GET['jenis'] ?? 'all';
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
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
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
                    <a href="../kotak_siswa/index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="index.php">Publikasi</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Publikasi</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Data Publikasi</h1>
                    <p>Informasi berupa artikel, berita, acara yang akan datang.</p>
                    <p>Kegiatan berupa acara yang telah dilaksanakan.</p>
                    <div class="card">
                        <div class="card-body">
                        <a class="btn btn-primary mb-4" href="create.php" style="color: white; width: 155px;"><i class="lni lni-plus"></i> Publikasi Baru</a>
                            <form method="GET">
                                <div class="d-flex align-items-center gap-2">
                                    <p class="mb-0">Anda sedang menampilkan data publikasi:</p>
                                    <select name="jenis" id="jenisDropdown" class="form-select w-auto" onchange="this.form.submit()">
                                        <option value="all" <?php echo $selectedJenis === 'all' ? 'selected' : ''; ?>>Semua Data</option>
                                        <?php foreach ($enumValues as $jenis) { ?>
                                            <option value="<?php echo $jenis; ?>" <?php echo $selectedJenis === $jenis ? 'selected' : ''; ?>>
                                                <?php echo ucfirst($jenis); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tanggal Publikasi</th>
                                            <th scope="col">Penulis</th>
                                            <th scope="col">Judul</th>
                                            <th scope="col">Jenis Publikasi</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rowNumber = 1;

                                        // Tampilkan data siswa berdasarkan filter kelas
                                        foreach ($info_jenis as $jenis => $info) {
                                            if ($selectedJenis !== 'all' && $jenis !== $selectedJenis) {
                                                continue; // Skip kelas yang tidak sesuai filter
                                            }

                                            foreach ($info as $row) { ?>
                                                <tr>
                                                    <td><?php echo $rowNumber++; ?></td>
                                                    <td><?php echo htmlspecialchars(date("d F Y H:i:s", strtotime($row["date"])));?></td>
                                                    <td><?php echo htmlspecialchars($row['guru_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                                    <td><?php echo htmlspecialchars(ucfirst($row['jenis'])); ?></td>
                                                    <td>
                                                        <a class="btn btn-sm btn-info buttons" href="show.php?id=<?= htmlspecialchars($row['publikasi_id']); ?>"><i class="bx bx-show"></i></a>
                                                        <a class="btn btn-sm btn-warning buttons" href="edit.php?id=<?= htmlspecialchars($row['publikasi_id']); ?>"><i class="lni lni-pencil-1"></i></a>
                                                        <a onclick="return confirm('Apakah anda yakin?')" class="btn btn-sm btn-danger buttons" href="delete.php?id=<?= htmlspecialchars($row['publikasi_id']); ?>"><i class="lni lni-trash-3"></i></a>
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