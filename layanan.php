<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />
    <title>BIMBINGAN KONSELING</title>
    <link rel="stylesheet" href="assets/css/style_index.css" />
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
      <div class="container">
        <a class="navbar-brand me-auto" href="#"
          ><img src="assets/images/logoname.png"
        /></a>

        <div
          class="offcanvas offcanvas-end"
          tabindex="-1"
          id="offcanvasNavbar"
          aria-labelledby="offcanvasNavbarLabel"
        >
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
              <img src="assets/images/logoname.png" />
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="offcanvas"
              aria-label="Close"
            ></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
              <li class="nav-item">
                <a
                  class="nav-link mx-lg-2"
                  href="index.php"
                  >Home</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link mx-lg-2" href="Informasi.php">Informasi</a>
              </li>
              <li class="nav-item">
                <a class="nav-link mx-lg-2 active" aria-current="page" href="#">Layanan</a>
              </li>
              <li class="nav-item">
                <a class="nav-link mx-lg-2" href="#">Event</a>
              </li>
              <li class="nav-item">
                <a class="nav-link mx-lg-2" href="#">About Us</a>
              </li>
            </ul>
          </div>
        </div>
        <a href="users/index.php" class="login-button"
          ><i class="fa-solid fa-user"></i> Login</a
        >
        <button
          class="navbar-toggler pe-0"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasNavbar"
          aria-controls="offcanvasNavbar"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </nav>
    <!-- End Navbar -->

    <section class="hero-section-service">
      <div
        class="container d-flex align-items-center justify-content-center fs-1 text-white flex-column"
      >
        <h1>LAYANAN BK</h1>
      </div>
    </section>

    <!-- Service -->
     <section id="service" class="service section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <img src="" class="card-img" alt="">
                    </div>
                </div>
            </div>
        </div>
     </section>
    <!-- End Service -->

    
    <!-- Footer -->
    <footer class="bg-dark p-2 text-center">
      <div class="container">
        <p class="text-white footer-p">
          Jl. Gereja No.20 Purwokerto, Sokanegara, Kec. Purwokerto Timur, Kab.
          Banyumas Prov. Jawa Tengah | Phone: (0281) 637842 | Email:
          smpn3pwt@ymail.com
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
