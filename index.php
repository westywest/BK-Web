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
                <a class="nav-link mx-lg-2 active" aria-current="page" href="#"
                  >Home</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link mx-lg-2" href="informasi.php">Informasi</a>
              </li>
              <li class="nav-item">
                <a class="nav-link mx-lg-2" href="layanan.php">Layanan</a>
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
        <a class="login-button" href="users/index.php"><i class="fa-solid fa-user"></i> Login</a>
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

    <section class="hero-section">
      <div
        class="container d-flex align-items-center justify-content-center fs-1 text-white flex-column"
      >
        <h1>SELAMAT DATANG</h1>
        <h2>Bimbingan Konseling SMP Negeri 3 Purwokerto</h2>
      </div>
    </section>

    <!-- Quote section -->
    <section id="quote" class="quote section-padding">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section header text-center pb-5">
              <h2>Kutipan</h2>
              <p>- oleh tokoh</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About section -->
    <section id="about" class="about section-padding">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-12 col-12">
            <div class="about-img">
              <img src="assets/images/guru-bk.jpg" alt="" class="img-fluid" />
            </div>
          </div>
          <div class="col-lg-8 col-md-12 col-12 ps-lg-5 mt-md-5">
            <div class="about-text">
              <h2>Seputar Bimbingan Konseling</h2>
              <hr />
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                enim ad minim veniam, quis nostrud exercitation ullamco laboris
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                sunt in culpa qui officia deserunt mollit anim id est laborum.
              </p>
              <a href="#" class="btn btn-learn">Baca Selengkapnya</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest Event -->
    <section id="event" class="event section-padding">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="section-header text-center pb-5">
              <h2>LATEST EVENT</h2>
              <hr style="margin: auto" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-12 col-lg-4">
            <div class="card text-center bg-white pb-2">
              <div class="card-body text-dark">
                <div class="img-area mb-4">
                  <img src="assets/images/img1.jpg" alt="" class="img-fluid" />
                </div>
                <h3 class="card-tittle">Judul</h3>
                <p class="lead">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
                <button class="btn btn-learn">Baca Selengkapnya</button>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-12 col-lg-4">
            <div class="card text-center bg-white pb-2">
              <div class="card-body text-dark">
                <div class="img-area mb-4">
                  <img src="assets/images/img1.jpg" alt="" class="img-fluid" />
                </div>
                <h3 class="card-tittle">Judul</h3>
                <p class="lead">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                  
                </p>
                <button class="btn btn-learn">Baca Selengkapnya</button>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-12 col-lg-4">
            <div class="card text-center bg-white pb-2">
              <div class="card-body text-dark">
                <div class="img-area mb-4">
                  <img src="assets/images/img1.jpg" alt="" class="img-fluid" />
                </div>
                <h3 class="card-tittle">Judul</h3>
                <p class="lead">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                  
                </p>
                <button class="btn btn-learn">Baca Selengkapnya</button>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-12 col-lg-4">
            <div class="card text-center bg-white pb-2">
              <div class="card-body text-dark">
                <div class="img-area mb-4">
                  <img src="assets/images/img1.jpg" alt="" class="img-fluid" />
                </div>
                <h3 class="card-tittle">Judul</h3>
                <p class="lead">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                  do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                  
                </p>
                <button class="btn btn-learn">Baca Selengkapnya</button>
              </div>
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
              <h2>Guru Profesional</h2>
              <hr style="margin: auto" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-4 col-lg-4">
            <div class="card text-center">
              <div class="card-body">
                <img
                  src="assets/images/profile.jpg"
                  alt=""
                  class="img-fluid rounded-circle"
                />
                <h3 class="card-tittle py-2">Novita</h3>
                <p class="card-text">
                  Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                  Veniam quam rem officiis esse magni dolore sint aperiam maxime
                  temporibus sunt reiciendis incidunt saepe nemo odio cumque
                  omnis perferendis, suscipit ipsum
                </p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <div class="card text-center">
              <div class="card-body">
                <img
                  src="assets/images/profile.jpg"
                  alt=""
                  class="img-fluid rounded-circle"
                />
                <h3 class="card-tittle py-2">Novita</h3>
                <p class="card-text">
                  Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                  Veniam quam rem officiis esse magni dolore sint aperiam maxime
                  temporibus sunt reiciendis incidunt saepe nemo odio cumque
                  omnis perferendis, suscipit ipsum
                </p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <div class="card text-center">
              <div class="card-body">
                <img
                  src="assets/images/profile.jpg"
                  alt=""
                  class="img-fluid rounded-circle"
                />
                <h3 class="card-tittle py-2">Novita</h3>
                <p class="card-text">
                  Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                  Veniam quam rem officiis esse magni dolore sint aperiam maxime
                  temporibus sunt reiciendis incidunt saepe nemo odio cumque
                  omnis perferendis, suscipit ipsum
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


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
