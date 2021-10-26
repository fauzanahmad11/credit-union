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

$masterBunga = $library->conn->query("SELECT*FROM masterbunga WHERE namabunga='bunga sianggota'");

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$idpetugas'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];
$nickName = explode(" ",$username);

if(isset($_POST['submit'])){
    $nokta = htmlspecialchars($_POST['nokta']);

    $jangkaWaktu = null;
    if(isset($_POST['jangkaMinggu']) && $_POST['jangkaMinggu'] != 0){
        $jangkaWaktu = htmlspecialchars($_POST['jangkaMinggu'])." week";
    }

    if(isset($_POST['jangkaBulan']) && $_POST['jangkaBulan'] != 0){
        $jangkaWaktu = htmlspecialchars($_POST['jangkaBulan'])." month";
    }

    if(isset($_POST['jangkaTahun']) && $_POST['jangkaTahun'] != 0){
        $jangkaWaktu = htmlspecialchars($_POST['jangkaTahun'])." year";
    }

    $jumlah = htmlspecialchars($control->unRupiah($_POST['jumlah']));
    $tglMulai = htmlspecialchars($_POST['tglMulai']);
    $keterangan = htmlspecialchars($_POST['keterangan']);
    
    $execute = $library->insertTransaksiPinjaman($idpetugas, $nokta, $jangkaWaktu, $jumlah, $tglMulai, $keterangan);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Transaction || Pinjaman</title>
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
                                    <a href="../anggota/add_anggota.php" class="list-link"><span>Tambah Anggota</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../anggota/data_anggota.php" class="list-link"><span>Data Anggota</span></a>
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
                                    <a href="../transaksi/add_transaksi_pinjaman.php" class="list-link active"><span>Transaksi Pinjaman</span></a>
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
                    <h2>Add Transaction</h3>
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
                                    <h1>Pinjaman</h1>
                                    <a href="../data/pinjaman.php" class="link" title="data pinjaman">See all</a>
                                </div>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label for="nokta" class="form-label">NO KTA</label>
                                        <input type="number" name="nokta" class="form-control"
                                            placeholder="Example : 555546222578" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="jumlah" class="form-label">Jumlah Dana</label>
                                        <input type="text" name="jumlah" class="form-control rupiah"
                                            placeholder="Example : Rp. 500.000" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="jangkaMinggu" class="form-label">Jangka Waktu</label>
                                        <div class="t-group">
                                            <div class="group-item">
                                                <input type="number" name="jangkaMinggu" min="0" max="12" class="form-control disable-typing"
                                                    placeholder="1-10" required>
                                                <span>Minggu</span>
                                            </div>
                                            <div class="group-item">
                                                <input type="number" name="jangkaBulan" min="0" max="24" class="form-control disable-typing"
                                                    placeholder="1-12" required>
                                                <span>Bulan</span>
                                            </div>
                                            <div class="group-item">
                                                <input type="number" name="jangkaTahun" min="0" max="2" class="form-control disable-typing"
                                                    placeholder="1-2" required>
                                                <span>Tahun</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tglMulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" name="tglMulai" class="form-control"required>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea cols="30" rows="10" class="form-control" name="keterangan" placeholder="Example : Jalan satu nomor dua rt/rw 1/2" required></textarea>
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
        <?php if((isset($_GET['status']) && $_GET['status']=='1') && isset($_GET['aksi']) && $_GET['aksi']=='print'): ?>
        <section class="popup">
            <div class="popup-success">
                <div class="head">
                    <img src="../../assets/img/icon/icon-transaction-success.svg" alt="icon sukses">
                </div>
                <div class="body">
                    <h1 class="title">Transaksi Berhasil !</h1>
                    <h5 class="small-title">Tekan "Cetak" untuk print struk</h5>
                </div>
                <div class="foot">
                    <a href="../cetak/cetak_pinjaman.php?key=<?=$_GET['key']?>" target="_blank" class="btn btn-y-popup">Cetak</a>
                    <a href="add_transaksi_pinjaman.php" class="btn btn-n-popup">Selesai</a>
                </div>
            </div>
        </section>
        <?php
            endif; 
            if((isset($_GET['status']) && $_GET['status']=='0') && isset($_GET['aksi']) && $_GET['aksi']=='error'): 
        ?>
        <section class="popup">
            <div class="popup-success">
                <div class="head">
                    <img src="../../assets/img/icon/icon-transaction-success.svg" alt="icon sukses">
                </div>
                <div class="body">
                    <h1 class="title">Transaksi Gagal !</h1>
                    <h5 class="small-title">silahkan tekan reload untuk memulai ulang halaman</h5>
                </div>
                <div class="foot">
                    <a href="add_transaksi_pinjaman.php" class="btn btn-n-popup">Reload</a>
                </div>
            </div>
        </section>
        <?php 
            endif; 
        ?>
        <!-- #END Section PopUp -->

    </div>
    <script src="../../assets/js/format-input.js"></script>
    <script src="../../assets/js/all.min.js"></script>
    <script src="../../assets/js/layout.js"></script>
    <script>
        (function(){
            const jangkaMinggu = document.querySelector("input[name='jangkaMinggu']");
            const jangkaBulan  = document.querySelector("input[name='jangkaBulan']");
            const jangkaTahun  = document.querySelector("input[name='jangkaTahun']");

            // ==================Jangka Bula======================
            jangkaBulan.addEventListener('click', function () {
                if(jangkaBulan.value > 0){
                    jangkaTahun.disabled = 'true';
                    jangkaMinggu.disabled = 'true';
                    jangkaTahun.value = '';
                    jangkaMinggu.value = '';
                }
                if(jangkaBulan.value < 1){
                    jangkaTahun.disabled = '';
                    jangkaMinggu.disabled = '';
                }
            });

            // ==================Jangka Minggu======================
            jangkaMinggu.addEventListener('click', function () {
                if(jangkaMinggu.value > 0){
                    jangkaTahun.disabled = 'true';
                    jangkaBulan.disabled = 'true';
                    jangkaTahun.value = '';
                    jangkaBulan.value = '';
                }
                if(jangkaMinggu.value < 1){
                    jangkaTahun.disabled = '';
                    jangkaBulan.disabled = '';
                }
            });

            // ==================Jangka Tahun======================
            jangkaTahun.addEventListener('click', function () {
                if(jangkaTahun.value > 0){
                    jangkaBulan.disabled = 'true';
                    jangkaMinggu.disabled = 'true';
                    jangkaBulan.value = '';
                    jangkaMinggu.value = '';
                }
                if(jangkaTahun.value < 1){
                    jangkaBulan.disabled = '';
                    jangkaMinggu.disabled = '';
                }
            });
            
        })();

        setInterval(() => {
            document.querySelector(".popup").style.opacity = "1";
        }, 1000);
    </script>
</body>

</html>