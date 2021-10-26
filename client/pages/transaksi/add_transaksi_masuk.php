<?php
require_once "../../../assets/library/function.php";
require_once "../../../assets/library/function_control.php";
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../../../pages/loginpage/login.php");
}

if(isset($_SESSION['user']) && $_SESSION['user'] == 'petugas'){
    header("Location: ../../../index.php");
}

$library = new Library();
$control = new Control();
$id = $_SESSION['key'];

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM anggota WHERE nokta='$id'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];
$nickName = explode(" ",$username);

// select master harga
$queryMasterHarga = $library->conn->query("SELECT*FROM masterharga");

$execute = "";

$noKta           = null;
$queryNoKta      = null;
$rowNoKta        = null;
$rowPinjaman     = null;
$angsuranPokok   = null;
$angsuranBunga   = null;
$totalAngsuran   = null;
$queryPinjaman   = null;

// cek anggota
$noKta           = htmlspecialchars($id);
$queryNoKta      = $library->conn->query("SELECT*FROM anggota WHERE noKta='$noKta' AND `status`='aktif'");
$rowNoKta        = $queryNoKta->fetch(PDO::FETCH_ASSOC);
// cek siwajib
$queryPinjaman   = $library->conn->query("SELECT*FROM `view-pinjaman` WHERE nokta='$noKta' AND `status`='aktif'");
$rowPinjaman     = $queryPinjaman->fetch(PDO::FETCH_ASSOC);
$angsuranPokok   = $rowPinjaman['t_pokok'];
$angsuranBunga   = $rowPinjaman['t_bunga'];
$totalAngsuran   = $rowPinjaman['jumlah_setor'];

if(isset($_POST['submit'])){
    $angsuranPokok    = (isset($_POST['angsuranPokok']) ? htmlspecialchars($control->unRupiah($_POST['angsuranPokok'])) : "");
    $angsuranBunga    = (isset($_POST['angsuranBunga']) ? htmlspecialchars($control->unRupiah($_POST['angsuranBunga'])) : "");
    $simpananSukarela    = htmlspecialchars($control->unRupiah($_POST['simpananSukarela']));
    $simpananWajib  = htmlspecialchars($control->unRupiah($_POST['simpananWajib']));
    $simpananPokok    = htmlspecialchars($control->unRupiah($_POST['simpananPokok']));
    $simpananMasadepan  = htmlspecialchars($control->unRupiah($_POST['simpananMasadepan']));
    $nokta = htmlspecialchars($_POST['nokta']);
        if(empty($angsuranPokok) && empty($angsuranBunga) && empty($simpananSukarela) && empty($simpananWajib) && empty($simpananPokok) && empty($simpananMasadepan)){
    
            echo
            "<script>
            alert('lakukan minim 1 transaksi');
            document.location.href = 'add_transaksi_masuk.php?noKta=$nokta';
            </script>";
            die;
        }
        
    echo
    "<script>
    document.location.href = '../checkout/checkout.php?angsuranPokok=$angsuranPokok&angsuranBunga=$angsuranBunga&simpananSukarela=$simpananSukarela&simpananWajib=$simpananWajib&simpananPokok=$simpananPokok&simpananMasadepan=$simpananMasadepan';
    </script>";

    // $execute = $library->insertTransaksiDebit($idpetugas, $nokta, $angsuranPokok, $angsuranBunga, $simpananSukarela, $simpananWajib, $simpananPokok, $simpananMasadepan);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Transaction || Masuk</title>
    <link rel="icon" href="../../../assets/img/icon/favicon2.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="../../../assets/css/layout.css">
    <link rel="stylesheet" href="../../../assets/css/formStyle.css">
    <link rel="stylesheet" href="../../../assets/css/all.min.css">
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
                        <img src="../../../assets/img/icon/logo.svg" alt="">
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
                                <span class="icon"><i class="fas fa-balance-scale"></i></span>
                                <span class="title">transkasi</span>
                            </a>
                            <ul class="navbar-list">
                                <li class="list-item">
                                    <a href="../transaksi/add_transaksi_masuk.php" class="list-link active"><span>Transaksi Masuk</span></a>
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
                                <a href="#" class="link-menu">
                                    <span class="icon"><i class="fas fa-id-badge"></i></span>
                                    <span class="title"><?=$id?></span>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="../../../pages/loginpage/logout.php" class="link-menu">
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
                            <div class="form-head">
                                <h1>Transaksi Masuk</h1>
                            </div>
                            <form method="post" class="form-submit">
                            <?php
                            if($queryMasterHarga->rowCount() > 0){
                            ?>
                            <div class="form-body">
                            <input name="nokta" 
                            <?php 
                            if(!empty($noKta)){
                                if($queryNoKta->rowCount() > 0){
                                    echo "type='hidden'";
                                }else{ 
                                    echo "style=':0; visibility: hidden; position:absolute; z-index:-9999999;'";
                                    echo "type='text'";
                                }
                            }else{
                                echo "style=':0; visibility: hidden; position:absolute; z-index:-9999999;'";
                                echo "type='text'";
                            }
                            ?> value="<?=$noKta?>" required>

                                <div class="form-group">
                                    <label for="angsuranPokok" class="form-label">Angsuran Pokok</label>
                                    <div class="s-f-group">
                                        <input type="text" name="angsuranPokok" class="form-control rupiah"
                                            placeholder="Example : Rp. 500.000" 
                                            <?php if(!empty($noKta)){if($queryPinjaman->rowCount() > 0){echo "";}else{echo "disabled";}}else{echo "disabled";}?>
                                        >
                                            <h5 class="limit-saldo rupiahText"><?=$control->rupiah((empty($angsuranPokok) ? 0 : $angsuranPokok))?></h5>
                                    </div>
                                    <small>total angsuran bulanan anda adalah <?=$control->rupiah((empty($totalAngsuran) ? 0 : $totalAngsuran))?></small>
                                </div>
                                <div class="form-group">
                                    <label for="angsuranBunga" class="form-label">Angsuran Pokok</label>
                                    <div class="s-f-group">
                                        <input type="text" name="angsuranBunga" class="form-control rupiah"
                                            placeholder="Example : Rp. 500.000" 
                                            <?php if(!empty($noKta)){if($queryPinjaman->rowCount() > 0){echo "";}else{echo "disabled";}}else{echo "disabled";}?>
                                            >
                                            <h5 class="limit-saldo rupiahText"><?=$control->rupiah((empty($angsuranBunga) ? 0 : $angsuranBunga))?></h5>
                                    </div>
                                    <small>total angsuran bulanan anda adalah <?=$control->rupiah((empty($totalAngsuran) ? 0 : $totalAngsuran))?></small>
                                </div>

                                <div class="form-group">
                                    <label for="simpananSukarela" class="form-label">simpanan sukarela</label>
                                    <input type="text" name="simpananSukarela" class="form-control rupiah"
                                        placeholder="Example : Rp. 500.000" <?php if(!empty($noKta)){if($queryNoKta->rowCount() > 0){echo "";}else{ echo "disabled";}}else{echo "disabled";}?>>
                                </div>
                                <div class="form-group">
                                    <label for="simpananWajib" class="form-label">simpanan wajib</label>
                                    <input type="text" name="simpananWajib" class="form-control rupiah"
                                        placeholder="Example : Rp. 500.000" <?php if(!empty($noKta)){if($queryNoKta->rowCount() > 0){echo "";}else{ echo "disabled";}}else{echo "disabled";}?>>
                                </div>
                                <div class="form-group">
                                    <label for="simpananPokok" class="form-label">simpanan pokok</label>
                                    <input type="text" name="simpananPokok" class="form-control rupiah"
                                        placeholder="Example : Rp. 500.000" <?php if(!empty($noKta)){if($queryNoKta->rowCount() > 0){echo "";}else{ echo "disabled";}}else{echo "disabled";}?>>
                                </div>
                                <div class="form-group">
                                    <label for="simpananMasadepan" class="form-label">simpanan Masa Depan</label>
                                    <input type="text" name="simpananMasadepan" class="form-control rupiah"
                                        placeholder="Example : Rp. 500.000" <?php if(!empty($noKta)){if($queryNoKta->rowCount() > 0){echo "";}else{ echo "disabled";}}else{echo "disabled";}?>>
                                </div>
                                <div class="btn-group">
                                    <button type="submit" name="submit"
                                        class="btn btn-md btn-submit">Process</button>
                                    <button type="reset" name="reset"
                                        class="btn btn-md btn-reset">Reset</button>
                                </div>
                            </div>
                            <?php
                            }else{
                            ?>
                            <div class="form-body">
                                <div class="not-found">
                                    <img src="../../assets/img/icon/icon-master-not-found.svg" alt="">
                                    <h5>Master Data <br> Not Found</h5>
                                    <a href="../settings/add_settings.php">Lengkapi master data</a>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
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
                    <a href="../cetak/cetak_debit.php?key=<?=$_GET['key']?>" target="_blank" class="btn btn-y-popup">Cetak</a>
                    <a href="add_transaksi_masuk.php" class="btn btn-n-popup">Selesai</a>
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
                    <a href="add_transaksi_masuk.php" class="btn btn-n-popup">Reload</a>
                </div>
            </div>
        </section>
        <?php 
            endif; 
        ?>
        <!-- #END Section PopUp -->
    </div>
    <script src="../../../assets/js/all.min.js"></script>
    <script src="../../../assets/js/format-input.js"></script>
    <script src="../../../assets/js/layout.js"></script>
    <script>
    setInterval(() => {
        document.querySelector(".popup").style.opacity = "1";
    }, 1000);
    </script>
</body>

</html>