<?php
session_start();

$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <title>Permata Poli</title>

  <link rel="stylesheet" href="assets/css/maicons.css">

  <link rel="stylesheet" href="assets/css/bootstrap.css">

  <link rel="stylesheet" href="assets/vendor/animate/animate.css">

  <link rel="stylesheet" href="assets/css/theme.css">
  <link rel="shortcut icon" href="images/logo-1.png" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


</head>
<body>

  <!-- Back to top button -->
  <div class="back-to-top"></div>

  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky" data-offset="500">
      <div class="container">
        <a href="#" class="navbar-brand">
          <img src="images/logo-1.png" alt="Logo" style="width: 40px; height: 40px;">
          Permata<span class="text-primary">Poli</span></a>

        <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse collapse" id="navbarContent">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#about">Tentang Kami</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#poliklinik">Poliklinik</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#kontak">Kontak</a>
            </li>
            <?php if(isset($_SESSION['role'])): ?>
              <?php if($_SESSION['role'] === 'Admin'): ?>
                  <li class="nav-item">
                      <a class="btn btn-primary ml-lg-2" href="admin/dashboard.php">Admin</a>
                  </li>
              <?php elseif($_SESSION['role'] === 'Dokter'): ?>
                  <li class="nav-item">
                      <a class="btn btn-primary ml-lg-2" href="dokter/dashboard.php">Dokter</a>
                  </li>
              <?php else: ?>
                  <li class="nav-item">
                      <a class="btn btn-primary ml-lg-2" href="pasien/index.php">Buat Janji</a>
                  </li>
              <?php endif; ?>
          <?php else: ?>
              <li class="nav-item">
                  <a class="btn btn-primary ml-lg-2" href="pasien/index.php">Buat Janji</a>
              </li>
          <?php endif; ?>

          </ul>
        </div>

      </div>
    </nav>

    <div class="container">
      <div class="page-banner home-banner">
          <div class="row align-items-center flex-wrap-reverse h-100">
              <div class="col-md-6 py-5 wow fadeInLeft">
                  <h1 class="mb-4">Segera buat janji dengan dokter terbaik</h1>
                  <p class="text-lg text-grey mb-5">Kami menyediakan layanan kesehatan yang terbaik untuk anda</p>
                  <a href="#" class="btn btn-primary btn-split" data-toggle="modal" data-target="#videoModal" data-video="https://www.youtube.com/embed/9iJgfgO_cTw?autoplay=1">Tonton Video <div class="fab"><span class="mai-play"></span></div></a>
              </div>
              <div class="col-md-6 py-5 wow zoomIn">
                  <div class="img-fluid text-center">
                      <img src="images/logo-1.png" alt="">
                  </div>
              </div>
          </div>
          <a href="#akses" class="btn-scroll" data-role="smoothscroll"><span class="mai-arrow-down"></span></a>
      </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="videoModal" tablogin="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="videoModalLabel">Tonton Video</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" id="video" src="" allowfullscreen></iframe>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <script>
      $(document).ready(function() {
          $('#videoModal').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget); // Button that triggered the modal
              var videoUrl = button.data('video'); // Extract info from data-* attributes
              var modal = $(this);
              modal.find('.modal-body iframe').attr('src', videoUrl);
          });
          $('#videoModal').on('hide.bs.modal', function (event) {
              $(this).find('.modal-body iframe').attr('src', '');
          });
      });
  </script>
  </header>

  <?php if (!$is_logged_in): ?>
    <div class="page-section" id="akses">
        <div class="container">
            <div class="row">
                <!-- <div class="col-lg-4">
                    <div class="card-service wow fadeInUp">
                        <div class="header">
                            <img src="assets/img/dokter.png" style="width: 150px;" alt="">
                        </div>
                        <div class="body">
                            <h5 class="text-secondary">Admin</h5>
                            <p>Apabila anda adalah seorang admin, silahkan Login terlebih dahulu untuk mengelola data pasien</p>
                            <a href="admin/login.php" class="btn btn-primary">Login</a>
                        </div>
                    </div>
                </div> -->
                <div class="col-lg-6">
                    <div class="card-service wow fadeInUp">
                        <div class="header">
                            <img src="assets/img/dokter.svg" style="width: 150px;" alt="">
                        </div>
                        <div class="body">
                            <h5 class="text-secondary">Dokter</h5>
                            <p>Apabila anda adalah seorang dokter, silahkan Login terlebih dahulu untuk melayani pasien</p>
                            <a href="dokter/login.php" class="btn btn-primary">Login</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-service wow fadeInUp">
                        <div class="header">
                        <img src="assets/img/pasien.svg" style="width: 150px;" alt="">
                        </div>
                        <div class="body">
                            <h5 class="text-secondary">Pasien</h5>
                            <p>Apabila anda adalah seorang pasien, silahkan Login terlebih dahulu untuk membuat janji</p>
                            <a href="pasien/login.php" class="btn btn-primary">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .container -->
    </div> <!-- .page-section -->
    <?php endif; ?>

  <div class="page-section" id="about">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 py-3 wow fadeInUp">
          <span class="subhead">Tentang Kami</span>
          <h2 class="title-section">Permata Poli</h2>
          <div class="divider"></div>

          <p>Poli Permata adalah sebuah aplikasi yang menyediakan layanan kesehatan yang terbaik untuk anda. Kami menyediakan layanan kesehatan yang terbaik untuk anda</p>
          <p>Buat janji dengan dokter terbaik kami sekarang juga</p>
          <a href="pasien/login.php" class="btn btn-primary mt-3">Buat Janji</a>
        </div>
        <div class="col-lg-6 py-3 wow fadeInRight">
          <div class="img-fluid py-3 text-center">
            <img src="assets/img/Loby3-1800x600.jpg" alt="">
          </div>
        </div>
      </div>
    </div> <!-- .container -->
  </div> <!-- .page-section -->

  <div class="page-section bg-light" id="poliklinik">
    <div class="container">
      <div class="text-center wow fadeInUp">
        <div class="subhead">Poliklinik</div>
        <h2 class="title-section">Layanan Kami</h2>
        <div class="divider mx-auto"></div>
      </div>

      <div class="row">
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-hospital"></span> <!-- Ikon untuk Poli Umum -->
            </div>
            <h5>Poli Umum</h5>
            <p>Poli umum adalah poliklinik yang melayani pasien umum</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-tooth"></span> <!-- Ikon untuk Poli Gigi -->
            </div>
            <h5>Poli Gigi</h5>
            <p>Poli gigi adalah poliklinik yang melayani pasien gigi</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-baby"></span> <!-- Ikon untuk Poli Kandungan -->
            </div>
            <h5>Poli Kandungan</h5>
            <p>Poli kandungan adalah poliklinik yang melayani pasien kandungan</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-child"></span> <!-- Ikon untuk Poli Anak -->
            </div>
            <h5>Poli Anak</h5>
            <p>Poli anak adalah poliklinik yang melayani pasien anak</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-eye"></span> <!-- Ikon untuk Poli Mata -->
            </div>
            <h5>Poli Mata</h5>
            <p>Poli mata adalah poliklinik yang melayani pasien mata</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="mai-ear"></span> <!-- Ikon untuk Poli THT -->
            </div>
            <h5>Poli THT</h5>
            <p>Poli THT adalah poliklinik yang melayani pasien THT</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-heartbeat"></span> <!-- Ikon untuk Poli Jantung -->
            </div>
            <h5>Poli Jantung</h5>
            <p>Poli jantung adalah poliklinik yang melayani pasien jantung</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-lungs"></span> <!-- Ikon untuk Poli Paru -->
            </div>
            <h5>Poli Paru</h5>
            <p>Poli paru adalah poliklinik yang melayani pasien paru</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-brain"></span> <!-- Ikon untuk Poli Saraf -->
            </div>
            <h5>Poli Saraf</h5>
            <p>Poli saraf adalah poliklinik yang melayani pasien saraf</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-allergies"></span> <!-- Ikon untuk Poli Kulit -->
            </div>
            <h5>Poli Kulit</h5>
            <p>Poli kulit adalah poliklinik yang melayani pasien kulit</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-user-md"></span> <!-- Ikon untuk Poli Bedah -->
            </div>
            <h5>Poli Bedah</h5>
            <p>Poli bedah adalah poliklinik yang melayani pasien bedah</p>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-3 py-3 wow zoomIn">
          <div class="features">
            <div class="header mb-3">
              <span class="fas fa-stethoscope"></span> <!-- Ikon untuk Poli Penyakit Dalam -->
            </div>
            <h5>Poli Penyakit Dalam</h5>
            <p>Poli penyakit dalam adalah poliklinik yang melayani pasien penyakit dalam</p>
          </div>
        </div>
      </div>
      
      

    </div> <!-- .container -->
  </div> <!-- .page-section -->



  <footer class="page-footer bg-image" style="background-image: url(assets/img/world_pattern.svg);" id="kontak">
    <div class="container">
      <div class="row mb-5">
        <div class="col-lg-5 py-5">
          <h3>Permata Poli</h3>
          <p>Permata Poli adalah sebuah aplikasi yang menyediakan layanan kesehatan yang terbaik untuk anda. Kami menyediakan layanan kesehatan yang terbaik untuk anda</p>

          <div class="social-media-button">
            <a href="#"><span class="mai-logo-facebook-f"></span></a>
            <a href="#"><span class="mai-logo-twitter"></span></a>
            <a href="#"><span class="mai-logo-google-plus-g"></span></a>
            <a href="#"><span class="mai-logo-instagram"></span></a>
            <a href="#"><span class="mai-logo-youtube"></span></a>
          </div>
        </div>
        <div class="col-lg-5 py-5">
          <h5>Kontak</h5>
          <p>jl. Tuparev No.117, Pilangsari, Kec. Kedawung, Kabupaten Cirebon, Jawa Barat 45153</p>
          <a href="#" class="footer-link"> (0231) 8338877</a>
        </div>

      <p class="text-center" id="copyright">Copyright &copy; 2024. This Website Developed by 
        <a href="https://www.instagram.com/asepsr2502/">Asep Saeful Rohman</a>
      </p>
    </div>
  </footer>

<script src="assets/js/jquery-3.5.1.min.js"></script>

<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="assets/js/google-maps.js"></script>

<script src="assets/vendor/wow/wow.min.js"></script>

<script src="assets/js/theme.js"></script>
  
</body>
</html>