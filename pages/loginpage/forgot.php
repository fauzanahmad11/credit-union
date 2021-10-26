<?php
require_once '../../assets/library/function.php';
session_start();
if(isset($_SESSION['login'])){
    header("Location: ../../index.php");
    exit;
}

$library = new Library();

if(isset($_POST['resetPass'])){
    $noKtp = htmlspecialchars($_POST['noKtp']);
    $password = htmlspecialchars(strtolower($_POST['password']));
    $konfPassword = htmlspecialchars(strtolower($_POST['konfPassword']));

    $library->resetPass($noKtp, $password, $konfPassword);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password : Koperasi</title>
    <link rel="stylesheet" href="../../assets/css/styleLogin.css">
    <link rel="icon" href="../../assets/img/icon/favicon2.png" type="image/png" sizes="16x16">
</head>

<body>
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
            <form method="post">
                <div class="head">
                    <h1 class="head-signup">Reset Password</h1>
                </div>
                <div class="body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="noKtp" placeholder="NO KTP" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="NEW PASSWORD" required>
                    </div>
                    <div class=" form-group">
                        <input type="password" class="form-control" name="konfPassword" placeholder="KONFIRMASI NEW PASSWORD" required>
                    </div>
                </div>
                <div class="footer">
                    <div class="btn-action">
                        <button type="submit" name="resetPass" class="btn">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            RESET
                        </button>
                    </div>
                    <div class="box-title">
                        <span class="title">Remember my pass</span>
                    </div>
                    <a href="login.php" class="link-singup">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>