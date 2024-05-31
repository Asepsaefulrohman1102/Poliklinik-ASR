<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['username'])) {
    // Jika sesi sudah ada, langsung redirect ke dashboard
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM akun WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && $result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $stored_password = $row['password']; // Password yang disimpan di database
        if (password_verify($password, $stored_password)) { // Memeriksa password dengan password_verify
            if ($row['role'] === 'Admin') {
                // Hanya jika pengguna adalah admin, atur session
                $_SESSION['username'] = $row['username'];
                $_SESSION['loggedin'] = true; // Set status login ke true
                $_SESSION['role'] = $row['role']; // Store the role in the session

                // Alihkan ke dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                // Tampilkan SweetAlert menggunakan JavaScript
                echo "<script>
                      window.onload = function() {
                          Swal.fire({
                              icon: 'error',
                              title: 'Oops...',
                              text: 'Maaf, Anda bukan admin.'
                          });
                      };
                    </script>";
            }
        } else {
            $error_message = "Username atau password Anda salah. Silakan coba lagi!";
        }
    } else {
        $error_message = "Username atau password Anda salah. Silakan coba lagi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pages / Login - Permata Poli Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/assets_login/img/favicon.png" rel="icon">
  <link href="../assets/assets_login/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/assets_login/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/assets_login/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/assets_login/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/assets_login/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/assets_login/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/assets_login/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/assets_login/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


  <!-- Template Main CSS File -->
  <link href="../assets/assets_login/css/style.css" rel="stylesheet">

</head>

<body>

<main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="../../images/logo-1.png" alt="">
                  <span class="d-none d-lg-block">Permata Poli</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">
                <div class="card-body">
                    <div class="pt-4 pb-2">
                      <h5 class="card-title text-center pb-0 fs-4">Login Admin</h5>
                    </div>
                    <?php if (isset($error_message)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>


                  <form class="row g-3 needs-validation" method="post" action="" >
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <div class="invalid-feedback">
                                Please fill in your username
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">
                                Please fill in your password
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">Remember me</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100" type="submit" name="submit">Login</button>
                        </div>
                        <div class="col-12">
                            <p class="small mb-0">Tidak Punya Akun? <a href="register.php">Daftar</a></p>
                        </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/assets_login/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/assets_login/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/assets_login/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/assets_login/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/assets_login/vendor/quill/quill.min.js"></script>
  <script src="../assets/assets_login/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/assets_login/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/assets_login/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/assets_login/js/main.js"></script>

  <script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
  </script>

</body>

</html>
