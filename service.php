<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    <title>BIMBINGAN KONSELING</title>
    <link rel="stylesheet" href="assets/css/style_service.css" />
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
                  <a class="nav-link mx-lg-2 active" href="service.php">Layanan</a>
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

      <section class="hero-section">
        <div class="container d-flex align-items-center justify-content-center fs-1 text-white flex-column">
          <h1>LAYANAN KAMI</h1>
        </div>
      </section>

      <!-- About section -->
      <section id="about" class="about section-padding">
        <div class="container">
          <div class="row">
            <div class="col-lg-4 col-md-12 col-12">
              <div class="about-img">
                <img src="assets/images/guru-bk.jpg" alt="" class="img-fluid"/>
              </div>
            </div>
            <div class="col-lg-8 col-md-12 col-12 ps-lg-5 mt-md-5">
              <div class="about-text">
                <h2>Seputar Bimbingan Konseling</h2>
                <hr/>
                <b>Apa itu Bimbingan Konseling?</b>
                <p>Bimbingan Konseling (BK) adalah layanan yang disediakan untuk membantu siswa dalam menghadapi berbagai tantangan, baik akademik, pribadi, sosial, maupun karir. Melalui BK, siswa dapat mendapatkan dukungan profesional untuk mengembangkan potensi mereka secara maksimal.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="service" class="service section-padding">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="section-header text-center pb-5">
                <h2>Layanan Bimbingan Konseling</h2>
                <hr/>
                
                <table class="service-table">
                  <tr>
                    <td class="service-item">
                      <i class="fas fa-user-circle fa-3x"></i>
                      <b>Konseling Pribadi</b>
                      <p>Membantu siswa dalam menyelesaikan masalah pribadi seperti stres, kecemasan, atau konflik dengan orang lain.</p>
                    </td>
                    <td class="service-item">
                      <i class="fas fa-book fa-3x"></i>
                      <b>Bimbingan Belajar</b>
                      <ul>
                        <li>Membantu dalam mengelola waktu belajar.</li>
                        <li>Memberikan panduan dalam memilih jurusan atau strategi belajar yang efektif.</li>
                      </ul>
                    </td>
                    <td class="service-item">
                      <i class="fas fa-briefcase fa-3x"></i>
                      <b>Konseling Karir</b>
                      <p>Memberikan informasi dan panduan untuk membantu siswa memahami minat dan bakat mereka, serta memilih jalur karir yang sesuai.</p>
                    </td>
                    <td class="service-item">
                      <i class="fas fa-users fa-3x"></i>
                      <b>Bimbingan Sosial</b>
                      <ul>
                        <li>Mendukung siswa dalam memperbaiki hubungan dengan teman atau keluarga.</li>
                        <li>Memberikan saran untuk meningkatkan kemampuan komunikasi dan kerja sama.</li>
                      </ul>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
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
          Copyright © 2025 Website BK SMP NEGERI 3 PURWOKERTO
        </p>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/fontawesome.min.css"></script>
  </body>
</html>
