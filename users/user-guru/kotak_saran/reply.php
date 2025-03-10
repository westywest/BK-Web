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
    $user_id = $_SESSION['user_id'];
    $guru_id = $_SESSION['guru_id'];
    $fotoDefault = 'default.jpg';
    $kotak_id = $_GET['id'];
    $sql = "SELECT id, date, message, reply, status FROM kotak WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kotak_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reply = $_POST['reply'];

        if (empty($reply)) {
            $_SESSION['error'] = "Balasan tidak boleh kosong!";
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $kotak_id);
            exit;
        }

        $sql = "UPDATE kotak SET reply = ?, status = 'closed' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $reply, $kotak_id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Balasan berhasil dikirim, saran ditutup!');
                    window.location.href = '/BK/users/user-guru/kotak_saran/index.php';
                  </script>";
                    exit;
        } else {
            $_SESSION['error'] = "Gagal mengirim balasan!";
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $kotak_id);
            exit;
        }
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
    <title>Kotak Saran | Guru</title>
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
                    <a href="../../dashboard.php" class="sidebar-link">
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
                <li class="sidebar-item active">
                    <a href="index.php" class="sidebar-link">
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
                            <li class="breadcrumb-item"><a href="/BK/users/user-guru/kotak_saran/index.php">Data Kotak Saran</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reply Balasan</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Reply Balasan</h1>
                    <p>Note : Jika sudah dibalas auto closed</p>

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
                                    <label for="message" class="form-label">Pesan</label><br>
                                    <!-- Mengganti textarea dengan div untuk pesan -->
                                    <div class="message-box" style="border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9; border-radius: 5px; min-height: 100px;">
                                        <?php echo htmlspecialchars($data['message']); ?>
                                    </div>
                                </div>
                                <!-- Formulir untuk balasan -->
                                <div class="mb-3">
                                    <label for="reply" class="form-label">Kirim Balasan</label>
                                    <!-- Jika sudah ada balasan, tambahkan readonly pada textarea -->
                                    <textarea name="reply" id="reply" class="form-control" rows="4" placeholder="Tulis balasan disini..." <?php echo isset($data['reply']) && !empty($data['reply']) ? 'readonly' : ''; ?>><?php echo isset($data['reply']) ? htmlspecialchars($data['reply']) : ''; ?></textarea>
                                </div>

                                <!-- Jika reply sudah ada, sembunyikan tombol kirim -->
                                <?php if (empty($data['reply'])): ?>
                                    <button class="btn btn-primary my-3" type="submit" name="submit" style="color: white;">Kirim</button>
                                <?php endif; ?>
                            </form>
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