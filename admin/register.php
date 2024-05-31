<?php
// Mulai sesi
session_start();

include 'koneksi.php';

if (isset($_SESSION['username'])) {
    header("location:dashboard.php");
    exit();
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Gunakan password_hash untuk menghasilkan hash bcrypt
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($password == $confirm_password) {
        $query = "SELECT * FROM akun WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        if (!$result->num_rows > 0) {
            // Simpan password yang di-hash ke database
            $query = "INSERT INTO akun (username, password, role) VALUES ('$username', '$hashed_password', 'Admin')";
            $result = mysqli_query($conn, $query);
            if ($result) {
                echo "<script>alert('Register berhasil. Silahkan login.'); window.location='login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Register gagal. Silahkan coba lagi.');</script>";
            }
        } else {
            echo "<script>alert('Username sudah terdaftar. Silahkan coba lagi.');</script>";
        }
    } else {
        echo "<script>alert('Password tidak sama. Silahkan coba lagi.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Register - Permata Poli</title>
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
                                    <h5 class="card-title text-center pb-0 fs-4">Register Account</h5>
                                </div>

                                <form class="row g-3 needs-validation" action="" method="POST">
                                    <div class="form-group">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                        <div class="invalid-feedback">
                                            Please enter a username
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <div class="invalid-feedback">
                                            Please enter a password
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        <div class="invalid-feedback">
                                            Please confirm your password
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit" name="submit">Register</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">Sudah punya akun? <a href="login.php">Login</a></p>
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