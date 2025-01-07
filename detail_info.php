<?php 
include 'function/connectDB.php';
$publikasi_id = $_GET['id'];
$sql = "SELECT publikasi.id AS publikasi_id, 
        publikasi.guru_id, publikasi.date, 
        publikasi.title, publikasi.jenis, 
        publikasi.content, 
        publikasi.foto,
        guru.id AS guru_id,
        guru.name AS guru_name
        FROM publikasi 
    JOIN guru ON publikasi.guru_id = guru.id
    WHERE publikasi.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $publikasi_id);
$stmt->execute();
$result = $stmt->get_result();


if ($data = $result->fetch_assoc()) {
    $foto = $data['foto'];
    $title = $data['title'];
    $date = $data['date'];
    $jenis = $data['jenis'];
    $guru_name = $data['guru_name'];
    $content = $data['content'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <title>BIMBINGAN KONSELING</title>
    <link rel="stylesheet" href="assets/css/style_detail.css" />
  </head>
  <body>
    <div class="wrapper">
          <!-- Navbar -->
      <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
          <a class="navbar-brand me-auto" href="index.php"
            ><img src="assets/images/logonamenobg2.png"
          /></a>

          <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
                <img src="assets/images/logonamenobg2.png" />
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                <li class="nav-item">
                  <a class="nav-link mx-lg-2" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link mx-lg-2" href="informasi.php">Informasi</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link mx-lg-2" href="service.php">Layanan</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link mx-lg-2" href="event.php">Event</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link mx-lg-2" href="aboutUs.php">About Us</a>
                </li>
              </ul>
            </div>
          </div>
          <a class="login-button" href="users/index.php"><i class="fa-solid fa-user"></i> Login</a>
          <button class="navbar-toggler pe-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </nav>
      <!-- End Navbar -->

      <div class="container" style="margin-top: 120px;">
          <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="/BK/index.php" style="text-decoration:none; color: #1f3072;">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo $title ?></li>
              </ol>
          </nav>
          <div class="pb-5">
              <div class="detail p-4 rounded-4">
                  <h2 class="titleContent"><?php echo htmlspecialchars($title) ?></h2><br>
                  <div class="author">
                    <p class="card-text text-center" style="color: #1f3072;; font-weight: bold;"><small style="color: black";><?php echo htmlspecialchars($guru_name) ?> -</small> <?php echo htmlspecialchars(ucfirst($jenis)) ?></p>
                  </div>
                  <div class="text-center">
                    <small class="text-muted"><?php echo htmlspecialchars(date( "l, d M Y | H:i", strtotime($date)));?></small>
                  </div>
                  <div class="center" style="text-align: center;"><img src="assets/images/uploads/<?php echo htmlspecialchars($foto);?>" class="det-img" alt=""></div>
                  <br>
                  <p class="content"><?php echo ($content) ?></p>
              </div>
          </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark p-2 text-center" style="margin-top: 20px;">
      <div class="container">
        <p class="text-white footer-p">
          Jl. Gereja No.20 Purwokerto, Sokanegara, Kec. Purwokerto Timur, Kab.
          Banyumas Prov. Jawa Tengah | Phone: (0281) 637842 | Email:
          smpn3pwt@gmail.com
        </p>
        <p class="text-white footer-p">
          Copyright © 2025 Website BK SMP NEGERI 3 PURWOKERTO
        </p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/fontawesome.min.css"></script>
  </body>
</html>
