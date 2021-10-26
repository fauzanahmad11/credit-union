<?php
session_start();
require_once '../../assets/library/function.php';

if (STATUS_DEV !== 'production') {
    header('Location:../underconstruction/');
    die;
}

if (isset($_SESSION['login'])) {
    header("Location: ../../index.php");
}

if (isset($_SESSION['user']) && $_SESSION['user'] == 'anggota') {
    header("Location: ../../client/index.php");
}

if (isset($_SESSION['user']) && $_SESSION['user'] == 'petugas') {
    header("Location: ../../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../assets/css/styleLogin.css">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="icon" href="../../assets/img/icon/favicon2.png" type="image/png" sizes="16x16">
</head>

<body>
    <!-- START Preloader -->
    <section class="preloader">
        <div class="loader">
            <div class="text">
                <h6>Please wait...</h6>
            </div>
            <span></span>
            <span></span>
        </div>
    </section>
    <!-- #END Preloader -->
    <div class="error-box">
        <ul class="form-error">
        </ul>
    </div>
    <div class="container">
        <div class="firts-layer">
            <div class="secound-layer">
                <div class="third-layer">
                    <div class="fourd-layer">
                        <img src="../../assets/img/bg/logo.svg" alt="" class="logo-brand">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-login">
            <div class="head">
                <img src="../../assets/img/icon/iconuser.svg" alt="" class="head-icon">
            </div>
            <div class="body">
                <div class="form-group"></div>
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="USERNAME" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="PASSWORD" autocomplete="off">
                </div>
                <div class="form-group">
                    <div class="radio-control">
                        <div class="fitur">
                            <div class="forgot-pass">
                                <a href="forgot.php" class="link-forgot">
                                    Forgot password ?
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="btn-action">
                    <button type="submit" name="login" class="btn">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        Login
                    </button>
                </div>
                <div class="box-title">
                    <span class="title">Don't have an account yet ?</span>
                </div>
                <a href="singup.php" class="link-singup">
                    Create an account
                </a>
            </div>
        </div>
    </div>
    <script src="../../assets/js/login-control.js"></script>
    <script src="../../assets/js/layout.js"></script>
</body>

</html>