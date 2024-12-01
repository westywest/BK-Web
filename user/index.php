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

            if(isset($_POST['login'])){
                $user = $_POST['username'];
                $pass = $_POST['password'];

                $query = mysqli_query($conn, "SELECT * FROM user WHERE username='$user' AND password='$pass'");
                $data = mysqli_fetch_array($query);
                $cekdata = mysqli_num_rows($query);

                if($cekdata > 0) {
                    if($data['role']=="admin") {
                        $_SESSION['role']=$data['role'];
                        $_SESSION['username']=$data['username'];
                        header('location:/BK/user/user-admin/dashboard.php');
                    }elseif($data['role']=="guru") {
                        $_SESSION['role']=$data['role'];
                        $_SESSION['username']=$data['username'];
                        header('location:/BK/user/user-guru/dashboard.php');
                    }elseif($data['role']=="siswa") {
                        $_SESSION['role']=$data['role'];
                        $_SESSION['username']=$data['username'];
                        header('location:/BK/user/user-siswa/dashboard.php');
                    }
                }else{
                    $_SESSION['error'] = "Gagal login, silahkan cek kembali username dan password anda!";
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