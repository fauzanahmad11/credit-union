<?php
require_once "../../assets/library/function.php";
require_once "../../assets/library/function_control.php";
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../loginpage/login.php");
}


$library = new Library();
$control = new Control();
$idpetugas = $_SESSION['key'];

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$idpetugas'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];
$nickName = explode(" ",$username);

if(isset($_POST['submit'])){
    $nama = htmlspecialchars(strtolower($_POST['namaAnggota']));
    $alamat = htmlspecialchars(strtolower($_POST['alamat']));
    $noTelepon = htmlspecialchars($_POST['noTelepon']);
    $pekerjaan = htmlspecialchars(strtolower($_POST['pekerjaan']));
    $gender = htmlspecialchars(strtolower($_POST['gender']));

    $execute = $library->insertAnggota($nama, $alamat, $noTelepon, $pekerjaan, $gender);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Anggota</title>
    <link rel="icon" href="../../assets/img/icon/favicon2.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/formStyle.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/popup-style.css">
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
    <div class="wrap-all">
        <!-- Start Navbar -->
        <div class="sidebar">
            <nav class="navbar">
                <div class="navbar-content head">
                    <div class="logo-brand">
                        <img src="../../assets/img/icon/logo.svg" alt="">
                    </div>
                    <div class="title">
                        <h2>Design</h2>
                    </div>
                    <div class="btn-toggle-navbar">
                        <a href="#">
                            <i class="fas fa-bars fa-2x"></i>
                        </a>
                    </div>
                </div>
                <div class="navbar-content body">
                    <h5 class="head">Main</h5>
                    <ul class="navbar-nav">
                        <li class="navbar-items">
                            <a href="../../index.php" class="navbar-link">
                                <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                                <span class="title">Dashboard</span>
                            </a>
                        </li>
                        <li class="navbar-items">
                            <a href="#" class="navbar-link head-list">
                                <span class="icon"><i class="fas fa-user-tie"></i></span>
                                <span class="title">Petugas</span>
                            </a>
                            <ul class="navbar-list">
                                <li class="list-item">
                                    <a href="../petugas/addNew_petugas.php" class="list-link"><span>Tambah
                                            Petugas</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../petugas/data_petugas.php" class="list-link"><span>Data Petugas</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="navbar-items">
                            <a href="#" class="navbar-link head-list">
                                <span class="icon"><i class="fas fa-user"></i></span>
                                <span class="title">Anggota</span>
                            </a>
                            <ul class="navbar-list">
                                <li class="list-item">
                                    <a href="add_anggota.php" class="list-link active"><span>Tambah Anggota</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="data_anggota.php" class="list-link"><span>Data Anggota</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="navbar-items">
                            <a href="#" class="navbar-link head-list">
                                <span class="icon"><i class="fas fa-balance-scale"></i></span>
                                <span class="title">transkasi</span>
                            </a>
                            <ul class="navbar-list">
                                <li class="list-item">
                                    <a href="../transaksi/add_transaksi_masuk.php" class="list-link"><span>Transaksi Masuk</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../transaksi/add_transaksi_keluar.php" class="list-link"><span>Transaksi Keluar</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../transaksi/add_transaksi_simapan.php" class="list-link"><span>Transaksi Simapan</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../transaksi/add_transaksi_sianggota.php" class="list-link"><span>Transaksi Sianggota</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../transaksi/add_transaksi_pinjaman.php" class="list-link"><span>Transaksi Pinjaman</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="navbar-items">
                            <a href="#" class="navbar-link head-list">
                                <span class="icon"><i class="fas fa-database"></i></span>
                                <span class="title">data</span>
                            </a>
                            <ul class="navbar-list">
                                <li class="list-item">
                                    <a href="../data/simpanan_pokok.php" class="list-link"><span>Simpanan Pokok</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../data/simpanan_wajib.php" class="list-link"><span>Simpanan Wajib</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../data/simpanan_sukarela.php" class="list-link"><span>Simpanan Sukarela</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../data/simpanan_anggota.php" class="list-link"><span>Simpanan Anggota</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../data/simpanan_masadepan.php" class="list-link"><span>Simpanan Masa Depan</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../data/pinjaman.php" class="list-link"><span>Pinjaman</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../data/angsuran.php" class="list-link"><span>Angsuran</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <h5 class="head" style="margin-top: 0px;">Help</h5>
                    <ul class="navbar-nav">
                        <li class="navbar-items">
                            <a href="../settings/add_settings.php" class="navbar-link">
                                <span class="icon"><i class="fas fa-cog"></i></span>
                                <span class="title">settings</span>
                            </a>
                        </li>
                        <li class="navbar-items">
                            <a href="../riwayat/add_riwayat.php" class="navbar-link">
                                <span class="icon"><i class="fas fa-history"></i></span>
                                <span class="title">riwayat</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="navbar-content footer">
                    <small>alright reserved by <span>ZanWorld</span> &copy; 2020 </small>
                </div>
            </nav>
        </div>
        <!-- #END Navbar -->
        <!-- Start Section Content -->
        <section class="content">
            <!-- Start Head Content -->
            <header class="content-header">
                <div class="content-title">
                    <h2>Add Anggota</h3>
                </div>
                <div class="user-dropdown">
                    <a href="#" class="dropdown-toggle">
                        <h4 class="user-name"><?=$nickName[0]?></h4>
                    </a>
                    <div class="dropdown-menu">
                        <ul class="dropdown-item">
                            <li class="list-menu">
                                <a href="../petugas/profile_petugas.php?data=<?=$control->hashMethod('encrypt',$idpetugas)?>" class="link-menu">
                                    <span class="icon"><i class="fas fa-user-alt"></i></span>
                                    <span class="title">Profil</span>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="../petugas/setting_petugas.php?data=<?=$control->hashMethod('encrypt',$idpetugas)?>" class="link-menu">
                                    <span class="icon"><i class="fas fa-user-cog"></i></span>
                                    <span class="title">Pengaturan Akun</span>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="../loginpage/logout.php" class="link-menu">
                                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                                    <span class="title">Keluar</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            <!-- #END Head Content -->
            <!-- Start Body Content -->
            <div class="content-body">
                <div class="container">
                    <section class="single-form">
                        <div class="form">
                            <form method="post">
                                <div class="form-head">
                                    <h1>Anggota</h1>
                                    <a href="data_anggota.php" class="link" title="data pinjaman">See all</a>
                                </div>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label for="namaAnggota" class="form-label">Nama Anggota</label>
                                        <input type="text" name="namaAnggota" class="form-control"
                                            placeholder="Example : Fauzan Ahmad" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Jenis Kelamin</label>
                                        <div class="radio-control">
                                            <div class="box">
                                                <label for="gender" class="btn-radio">Laki-Laki
                                                    <input type="radio" name="gender" value="laki-laki" required>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="box">
                                                <label for="gender" class="btn-radio">Perempuan
                                                    <input type="radio" name="gender" value="perempuan" required>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea cols="30" rows="10" class="form-control" name="alamat" placeholder="Example : Jalan satu nomor dua rt/rw 1/2" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                        <input type="text" name="pekerjaan" class="form-control"
                                            placeholder="Example : Wirausaha" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="noTelepon" class="form-label">No Telepon</label>
                                        <input type="number" name="noTelepon" class="form-control"
                                            placeholder="Example : 08229229XXXX" required>
                                    </div>
                                    <div class="btn-group">
                                        <button type="submit" name="submit"
                                            class="btn btn-md btn-submit">Simpan</button>
                                        <button type="reset" name="reset"
                                            class="btn btn-md btn-reset">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
            <!-- #END Body Content -->
        </section>
        <!-- #END Section Content -->
        <!-- Start Section PopUp -->
        <?php 
        $data = isset($_GET["data"]) ? explode(" ",$_GET["data"]) : "";
        if(isset($_GET["data"]) && $data[0] === "success"){ 
        ?>
        <section class="popup">
            <div class="popup-success">
                <div class="head">
                    <img src="../../assets/img/icon/icon-regist-succes.svg" alt="icon sukses">
                </div>
                <div class="body">
                    <h1 class="title">Registrasi Berhasil !</h1>
                    <h5 class="small-title">Apakah anda ingin melanjutkan pada transaksi ?</h5>
                </div>
                <div class="foot">
                    <a href="../transaksi/add_transaksi_masuk.php?direct=transaksi&noKta=<?=$control->hashMethod("decrypt",$data[1])?>" class="btn btn-y-popup">Ya</a>
                    <a href="add_anggota.php" class="btn btn-n-popup">Tidak</a>
                </div>
            </div>
        </section>
        <?php } ?>
        <!-- #END Section PopUp -->

    </div>
    <script src="../../assets/js/all.min.js"></script>
    <script src="../../assets/js/layout.js"></script>
    <script>
        setInterval(() => {
            document.querySelector(".popup").style.opacity = "1";
        }, 1000);
    </script>
</body>

</html>