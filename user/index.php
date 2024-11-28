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
            <h2 class="fw-bold text-center">LOGIN FORM</h2>
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

            <p style="color: red; font-size: 12px;">
            </p>
            <button type="submit" name="submit" class="btn btn-color px-5 mb-5 w-100">Login</button>
            <div id="emailHelp" class="form-text text-center mb-4 text-dark">
                Forgot
                    <a href="#" class="a fw-bold" style="text-decoration:none;">Password</a>?
                    <br><br>
                    <a href="/BK/index.php" class="a fw-bold" style="text-decoration:none;">Back to Home</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>