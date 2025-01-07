<?php 
include 'function/connectDB.php';

$sql = "SELECT nip, name, foto FROM guru";

$fotoDefault = 'default.jpg';

$datas = $conn->prepare($sql);
$datas->execute();
$resulGuru = $datas->get_result();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <title>BIMBINGAN KONSELING</title>
    <link rel="stylesheet" href="assets/css/style_aboutUs.css" />
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
                  <a class="nav-link mx-lg-2" href="event.php">Event</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link mx-lg-2 active" href="aboutUs.php">About Us</a>
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
          <h1>ABOUT US</h1>
        </div>
      </section>

      <section id="visi" class="visi section-padding">
          <div class="container" style="margin-top: 40px;">
              <div class="row">
                  <div class="col-md-12">
                      <div class="section header text-center pb-5">
                          <h2>VISI</h2>
                          <hr style="margin: auto"/>
                          <p>Menjadi lembaga yang memberikan layanan Bimbingan Konseling yang profesional, terpercaya, dan bermanfaat bagi siswa dalam mencapai kesejahteraan emosional, sosial, dan akademik.</p>
                      </div>
                  </div>
              </div>
          </div>
      </section>

      <section id="misi" class="misi section-padding">
          <div class="container">
              <div class="row">
                  <div class="col-md-12">
                      <div class="section header text-center pb-5">
                          <h2>MISI</h2>
                          <hr style="margin: auto"/>
                          <ul>
                              <li>Menyediakan layanan bimbingan dan konseling yang responsif terhadap kebutuhan siswa.</li>
                              <li>Membantu siswa dalam mengidentifikasi dan mengatasi masalah yang mereka hadapi, baik di sekolah maupun dalam kehidupan pribadi mereka.</li>
                              <li>Mendorong siswa untuk berkembang secara holistik, dengan memfasilitasi pemahaman diri, pilihan karir, dan hubungan interpersonal yang sehat.</li>
                              <li>Memberikan dukungan yang kuat kepada orang tua dan guru dalam mendukung perkembangan siswa.</li>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
      </section>

      <!-- Teacher Section-->
      <section id="teacher" class="teacher section-padding">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="section-header text-center pb-5">
                <h2>Guru BK</h2>
                <hr style="margin: auto" />
              </div>
            </div>
          </div>
          <div class="row">
          <?php
          while ($row = $resulGuru->fetch_assoc()) {?>
              <div class="col-12 col-md-4 col-lg-4">
                  <div class="card text-center">
                      <div class="card-body">
                      <img src="assets/images/teacher/<?php echo !empty($row['foto']) ? htmlspecialchars($row['foto']) : $fotoDefault; ?>" alt="" class="img-fluid rounded-circle"/>
                          <h3 class="card-tittle py-2"><?php echo htmlspecialchars($row['name']) ?></h3>
                          <p class="card-text">NIP. <?php echo htmlspecialchars($row['nip']) ?></p>
                      </div>
                  </div>
              </div>
          <?php  }; ?>
          </div>
        </div>
      </section>
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
          Copyright © 2024 Website BK SMP NEGERI 3 PURWOKERTO
        </p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/fontawesome.min.css"></script>
  </body>
</html>
