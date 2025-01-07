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
    <title>Kotak Saran | Siswa</title>
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
    
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $guru_id = intval($_POST['guru_id']);
        $message = htmlspecialchars(trim($_POST['message']));

        if (empty($guru_id) || $guru_id == 0) {
            $_SESSION['error'] = "Harap pilih guru!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } elseif (empty($message)) {
            $_SESSION['error'] = "Harap isi pesan kamu!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else  {
            $sql = "INSERT INTO kotak (id, message, guru_id, status) VALUES (null, ?, ?, 'open')";
            $addKotak = $conn->prepare($sql);
            $addKotak->bind_param("si", $message, $guru_id);
            $addKotak->execute();

            if ($addKotak->affected_rows > 0) {
                $kotak_id = $conn->insert_id;

                $sql = "INSERT INTO anon_mapping (id, kotak_id, user_id) VALUES (null, ?, ?)";
                $addKotak = $conn->prepare($sql);
                $addKotak->bind_param("ii", $kotak_id, $user_id);
                
                if ($addKotak->execute() && $addKotak->affected_rows > 0) {
                    echo "<script>
                            alert('Kotak Saran berhasil dibuat!');
                            window.location.href = '/BK/users/user-siswa/kotak_saran/index.php';
                          </script>";
                    exit;
                    
                } else {
                    $_SESSION['error'] = "Gagal membuat kotak saran!";
                }
            } else {
                $_SESSION['error'] = "Gagal membuat kotak saran!";
            }
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
                        <span>Kunjungan Siswa</span>
                    </a>
                </li>
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
                        <i class='bx bxs-inbox'></i>
                        <span>Kotak Saran</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../catatan_kasus/index.php" class="sidebar-link">
                        <i class='bx bx-error'></i>
                        <span>Catatan Kasus</span>
                    </a>
                </li>
            </ul>
            <div class="user-profile-footer p-2 d-flex align-items-center">
                <img src="../../../assets/images/profile.jpg" alt="User Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
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
                            <li class="breadcrumb-item"><a href="index.php">Log Kotak Saran</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kotak Saran Baru</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Kotak Saran Baru</h1>
                    <p>Sampaikan masalahmu pada form ini dan pilih guru yang ingin kamu sampaikan!</p>

                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <!-- Menampilkan pesan error jika ada -->
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-warning alert-dismissible fade show">
                                        <strong>WARNING!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label for="guru_id" class="form-label">Guru</label>
                                    <select class="form-select" aria-label="Default select example" name="guru_id" required>
                                        <option value="0">--Pilih Guru BK--</option>
                                        <?php
                                            $query = mysqli_query($conn, "SELECT * FROM guru") or die (mysqli_error($conn));
                                            while($data = mysqli_fetch_array($query)){
                                                echo "<option value=$data[id]>$data[nip] - $data[name]</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Pesan</label>
                                    <input type="text" class="form-control" name="message" id="message" required placeholder="Isi pesan kamu disini">
                                </div>

                                <button class="btn btn-primary my-3" type="submit" name="submit" style="color: white;">Kirim</button>
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
    </body>
</html>