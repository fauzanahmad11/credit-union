<?php
require_once "../../assets/library/function.php";
session_start();
if(isset($_SESSION['login'])){
    header("Location: ../../index.php");
}

$library = new Library();

if(isset($_POST['login'])){
    $noKtp = htmlspecialchars($_POST['noKtp']);
    $username = htmlspecialchars(strtolower($_POST['username']));
    $password = htmlspecialchars(strtolower($_POST['password']));
    $konfPassword = htmlspecialchars(strtolower($_POST['konfPassword']));
    
    if (strlen($noKtp) >= 16) {
        $library->insertAkunPetugas($noKtp, $username, $password, $konfPassword);
    } else {
        $library->insertAkunAnggota($noKtp, $username, $password, $konfPassword);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Singup : Koperasi</title>
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
                    <h1 class="head-signup">Singup</h1>
                </div>
                <div class="body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="noKtp" placeholder="NO KTP atau NO KTA" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" placeholder="USERNAME" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="PASSWORD" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="konfPassword" placeholder="KONFIRMASI PASSWORD" required>
                    </div>
                </div>
                <div class="footer">
                    <div class="btn-action">
                        <button type="submit" name="login" class="btn">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            Singup
                        </button>
                    </div>
                    <div class="box-title">
                        <span class="title">I have an account</span>
                    </div>
                    <a href="login.php" class="link-singup">
                        Login now
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>