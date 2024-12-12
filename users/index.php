<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/css/style_login.css">

</head>
<body class="d-grid align-content-center background-countainer">
    <div class="wrapper">
        <form class="border rounded mx-auto w-100 p-4 rounded-3" method="post">
            
            <?php 
            session_start();
            
            include '../function/connectDB.php';

            if (isset($_SESSION['status'])) {
                // Redirect sesuai role yang sudah login
                if ($_SESSION['role'] === "admin") {
                    header("location:/BK/users/user-admin/dashboard.php");
                    exit;
                } elseif ($_SESSION['role'] === "guru") {
                    header("location:/BK/users/user-guru/dashboard.php");
                    exit;
                } elseif ($_SESSION['role'] === "siswa") {
                    header("location:/BK/users/user-siswa/dashboard.php");
                    exit;
                }
            }
            
            if (isset($_POST['login'])) {
                $username = $_POST['username'];
                $pass = $_POST['password'];
            
                $sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $sql->bind_param("s", $username);
                $sql->execute();
                $result = $sql->get_result();
            
                if ($result->num_rows > 0) {
                    $data = $result->fetch_assoc();
                    if (password_verify($pass, $data['password'])) {
                        $_SESSION['role'] = $data['role'];
                        $_SESSION['user_id'] = $data['id']; // Menyimpan user_id
                        $_SESSION['username'] = $data['username'];
                        $_SESSION['status'] = "login";
                        
                    
                        
                        if ($data['role'] === "guru") {
                            $queryGuru = $conn->prepare("SELECT * FROM guru WHERE user_id = ?");
                            $queryGuru->bind_param("i", $data['id']);
                            $queryGuru->execute();
                            $resultGuru = $queryGuru->get_result();
                            
                            if($resultGuru->num_rows > 0) {
                                $guru = $resultGuru->fetch_assoc();
                                $_SESSION['guru_id'] = $guru['id'];
                                $_SESSION['name'] = $guru['name'];
                            }
                        } elseif ($data['role'] === "siswa") {
                            $querySiswa = $conn->prepare("SELECT * FROM siswa WHERE user_id = ?");
                            $querySiswa->bind_param("i", $data['id']);
                            $querySiswa->execute();
                            $resultSiswa = $querySiswa->get_result();
                            
                            if($resultSiswa->num_rows > 0) {
                                $siswa = $resultSiswa->fetch_assoc();
                                $_SESSION['siswa_id'] = $siswa['id'];
                                $_SESSION['name'] = $siswa['name'];
                            }
                        }

                        // Redirect sesuai role
                        if ($data['role'] === "admin") {
                            header('location:/BK/users/user-admin/dashboard.php');
                            exit;
                        } elseif ($data['role'] === "guru") {
                            header("location:/BK/users/user-guru/dashboard.php");
                            exit;
                        } elseif ($data['role'] === "siswa") {
                            header('location:/BK/users/user-siswa/dashboard.php');
                            exit;
                        }
                    } else {
                        $_SESSION['error'] = "Password salah!";
                    }
                } else {
                    $_SESSION['error'] = "Username tidak ditemukan!";
                }
            }
            ?>
            <div class="text-center">
                <img src="../assets/images/logoname.png" class="img-fluid profile-image-pic my-3" width="200px" alt="profile">
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bxs-user'></i></span>
                    <input type="text" class="form-control" name="username" id="username" required placeholder="Username">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bxs-lock-alt' ></i></span>
                    <input type="password" class="form-control" name="password" id="password" required placeholder="*********">
                </div>
            </div>

            <p style="color: red; font-size: 12px;"><?php if(isset($_SESSION['error'])){ echo($_SESSION['error']);} ?>
            </p>
            <button type="submit" name="login" class="btn btn-color px-5 mb-5 w-100">Login</button>
            <div id="emailHelp" class="form-text text-center mb-4 text-dark">
                Forgot
                    <a href="#" class="a fw-bold" style="text-decoration:none;">Password</a>?
                    <br><br>
                    <a href="/BK/index.php" class="a fw-bold" style="text-decoration:none;">Back to Home</a>
            </div>
        </form>
        <?php
            unset($_SESSION['error']);
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>