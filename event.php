<?php 
include 'function/connectDB.php';
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
        WHERE publikasi.jenis = 'kegiatan'
        ORDER BY publikasi.date DESC, publikasi.id DESC";

$dataInfo = $conn->prepare($sql);
$dataInfo->execute();
$resultInfo = $dataInfo->get_result();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <title>BIMBINGAN KONSELING</title>
    <link rel="stylesheet" href="assets/css/style_event.css" />
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
                  <a class="nav-link mx-lg-2 " href="informasi.php">Informasi</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link mx-lg-2" href="service.php">Layanan</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link mx-lg-2 active" href="event.php">Event</a>
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

      <section class="hero-section">
        <div class="container d-flex align-items-center justify-content-center fs-1 text-white flex-column">
          <h1>EVENTS</h1>
        </div>
      </section>

      <section id="info" class="info section-padding">
        <div class="container">
          <h2>Kegiatan Terbaru</h2>
          <hr/>
          <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php 
              while ($row = $resultInfo->fetch_assoc()) { 
                $foto = $row['foto'] ? $row['foto'] : 'default.png';
            ?>
            <div class="col">
              <a href="detail_info.php?id=<?= htmlspecialchars($row['publikasi_id']);?>" class="card-link">
                <div class="card" style="width: 20rem; height:18rem">
                <img src="<?= htmlspecialchars($foto === 'default.png' ? 'assets/images/default.png' : 'assets/images/uploads/' . $foto); ?>" class="card-img-top n-img" alt="">
                  <div class="card-body">
                    <p><small class="text-muted"><?php echo htmlspecialchars(date("d F Y", strtotime($row["date"])));?></small> • <small style="color: #0e2238;"><?php echo htmlspecialchars(ucfirst($row['jenis'])) ?></small></p>
                    <b class="card-tittle" style="text-transform: uppercase;"><?php echo htmlspecialchars($row['title']); ?></b>
                  </div>
                </div>
              </a>
            </div>
            <?php }; ?>
          </div>
        </div>
      </section>
    </div>
    
    
    <!-- Footer -->
    <footer class="bg-dark p-2 text-center" style="margin-top: 40px;">
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
