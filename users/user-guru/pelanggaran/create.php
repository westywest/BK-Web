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
                        <i class='bx bx-user' ></i>
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
                            <li class="breadcrumb-item"><a href="index.php">Data Pelanggaran</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Buat Pelanggaran</li>
                        </ol>
                    </nav>
                    <h1 class="h2">Buat Pelanggaran</h1>
                    <p>Membuat catatan pelanggaran bagi siswa yang melakukan pelanggaran.</p>

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
                                    <label for="kelas_id" class="form-label">Kelas</label>
                                    <select class="form-select" aria-label="Default select example" name="kelas_id" required>
                                        <option value="0">--Pilih Kelas--</option>
                                        <?php
                                            $query = mysqli_query($conn, "SELECT * FROM kelas") or die (mysqli_error($conn));
                                            while($data = mysqli_fetch_array($query)){
                                                echo "<option value=$data[id]>$data[class_name]</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="siswa_id" class="form-label">Siswa</label>
                                    <select class="form-select" aria-label="Default select example" name="siswa_id" required>
                                        <option value="">--Pilih Siswa--</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_id" class="form-label">Jenis Pelanggaran</label>
                                    <select class="form-select" aria-label="Default select example" name="jenis_id" required>
                                        <option value="0">--Pilih Jenis Pelanggaran--</option>
                                        <?php
                                            $query = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran") or die (mysqli_error($conn));
                                            while($data = mysqli_fetch_array($query)){
                                                echo "<option value=$data[id]>$data[jenis]</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="note" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="note" name="note" placeholder="Masukkan Keterangan" required>
                                </div>
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
        $(document).ready(function() {
            // Ketika kelas dipilih, load siswa sesuai kelas
            $('#kelas_id').change(function() {
                var kelas_id = $(this).val(); // Ambil kelas_id yang dipilih
                
                if (kelas_id != "") {
                    // Kirim permintaan AJAX untuk mengambil siswa berdasarkan kelas_id
                    $.ajax({
                        url: 'get_siswa_by_kelas.php', // File PHP untuk mengambil siswa
                        method: 'GET',
                        data: { kelas_id: kelas_id },
                        success: function(response) {
                            console.log(response);  // Memastikan response yang diterima
                            var siswaData = JSON.parse(response);
                            var siswaSelect = $('#siswa_id');
                            
                            // Kosongkan dropdown siswa sebelumnya
                            siswaSelect.empty();
                            siswaSelect.append('<option value="">Pilih Siswa</option>');
                            
                            // Masukkan data siswa yang baru
                            $.each(siswaData, function(index, siswa) {
                                siswaSelect.append('<option value="' + siswa.id + '">' + siswa.name + '</option>');
                            });
                        }
                    });
                } else {
                    // Jika kelas tidak dipilih, kosongkan dropdown siswa
                    $('#siswa_id').empty();
                    $('#siswa_id').append('<option value="">Pilih Siswa</option>');
                }
            });
        });

    </script>
    </body>
</html>