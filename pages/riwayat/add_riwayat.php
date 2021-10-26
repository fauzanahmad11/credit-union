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

// Pagination
$halaman = 10; //jumlah data perhalaman
// if(isset($_GET['halaman'])){(int)$_GET['halaman']} else{1}
$pageAnggota = isset($_GET['dataAnggota']) ? (int)$_GET['dataAnggota']:1; //jika $_GET['halaman'] tidak ada maka set jadi 1
$mulaiAnggota = ($pageAnggota > 1) ? ($pageAnggota * $halaman) - $halaman : 0;
$queryAnggota = $library->conn->query("SELECT*FROM riwayatanggota LIMIT $mulaiAnggota,$halaman");
$dataAnggota = $library->conn->query("SELECT*FROM riwayatanggota");
$totalDataAnggota = $dataAnggota->rowCount();
$pagesAnggota = ceil($totalDataAnggota/$halaman);

// Pagination Transaksi
$pageTransaksi = isset($_GET['dataTransaksi']) ? (int)$_GET['dataTransaksi']:1; //jika $_GET['halaman'] tidak ada maka set jadi 1
$mulaiTransaksi = ($pageTransaksi > 1) ? ($pageTransaksi * $halaman) - $halaman : 0;
$queryTransaksi = $library->conn->query("SELECT riwayattransaksi.*, SUM(total) AS totaltransaksi FROM `riwayattransaksi` GROUP BY notransaksi LIMIT $mulaiTransaksi,$halaman");
$dataTransaksi = $library->conn->query("SELECT riwayattransaksi.*, SUM(total) AS totaltransaksi FROM `riwayattransaksi` GROUP BY notransaksi");
$totalDataTransaksi = $dataTransaksi->rowCount();
$pagesTransaksi = ceil($totalDataTransaksi/$halaman);


if(isset($_POST['cetak'])){
    $tglMulai = htmlspecialchars(strtolower($_POST['tglMulai']));
    $tglSelesai = htmlspecialchars(strtolower($_POST['tglSelesai']));
    $parameterLink = "&key=simapan";
    $parameterLink .= (isset($_GET['search']) && !empty($_GET['search']) ? "&nokta={$_GET['search']}" : "");

    if(!empty($tglMulai) && !empty($tglSelesai)){
        $parameterLink .= "&start=$tglMulai&end=$tglSelesai";
        if($tglMulai > $tglSelesai){
            echo "
                <script>
                    alert('format tanggal salah');
                    window.history.back();
                </script>
            ";
            die;
        }
    }
    echo
    "<script>
    window.open('../../../pages/cetak/cetak_laporan.php?nokta=$id$parameterLink');
    document.location.href = 'simpanan_masadepan.php';
    </script>";
}   

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat</title>
    <link rel="icon" href="../../assets/img/icon/favicon2.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/formStyle.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
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
                            <a href="add_riwayat.php" class="navbar-link active">
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
        <section class="content">

            <!-- Start Head Content -->
            <header class="content-header">
                <div class="content-title">
                    <h2>Riwayat</h3>
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
                    <!-- Start Table View -->
                    <div class="data-view">
                        <div class="tab">
                            <nav class="tab-nav">
                                <ul>
                                    <li class="tab-item"><a class="tab-link">Riwayat Anggota</a></li>
                                    <li class="tab-item"><a class="tab-link">Riwayat Transaksi</a></li>
                                </ul>
                            </nav>
                            <div class="table tab-content anggota">
                                <div class="table-control">
                                    <div class="tb-search">
                                        <div class="form-group">
                                            <input type="search" name="searchAnggota" class="form-control search"
                                                placeholder="Tap to Search" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="table-body" style="overflow: auto;">
                                    <table class="tb_primary">
                                        <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NO KTA</th>
                                                <th>NAMA</th>
                                                <th>WAKTU</th>
                                                <th>KETERANGAN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = $mulaiAnggota+1;
                                                while($row = $queryAnggota->fetch(PDO::FETCH_ASSOC)):
                                            ?>
                                            <tr>
                                                <th><?=$no++?></th>
                                                <td><?=$row['nokta']?></td>
                                                <td><?=$row['nama']?></td>
                                                <td><?=$row['waktu']?></td>
                                                <td><?=$row['keterangan']?></td>
                                            </tr>
                                            <?php
                                                endwhile;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-footer">
                                    <nav>
                                        <ul class="pagination">
                                            <?php 
                                                if($pageAnggota > 1){ 
                                            ?>
                                                <li class="page-item active">
                                                    <a href="?dataAnggota=<?= $pageAnggota - 1 ?>" class="page-link">Previous</a>
                                                </li>
                                            <?php
                                                } else{
                                            ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">Previous</span>
                                                </li>
                                            <?php
                                                }
                                            for($i = 1; $i <= $pagesAnggota; $i++): 
                                                if($pageAnggota === $i){    
                                            ?>
                                                <li class="page-item active">
                                                    <span class="page-link"><?= $i ?></span>
                                                </li>
                                            <?php 
                                                } else {
                                            ?>
                                                <li class="page-item">
                                                    <a href="?dataAnggota=<?=$i?>" class="page-link"><?= $i ?></a>
                                                </li>    
                                            <?php 
                                                } 
                                                endfor; 
                                                if($pageAnggota < $pagesAnggota){ 
                                            ?>
                                                <li class="page-item active">
                                                    <a href="?dataAnggota=<?= $pageAnggota + 1 ?>" class="page-link">Next</a>
                                                </li>
                                            <?php
                                                } else{
                                            ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">Next</span>
                                                </li>
                                            <?php
                                                }
                                            ?>
                                        </ul>
                                    </nav>  
                                </div>
                            </div>
                            <div class="table tab-content  transaksi">
                                <div class="table-control">
                                    <div class="tb-search">
                                        <div class="form-group">
                                            <input type="search" name="searchTransaksi" class="form-control search"
                                                placeholder="Tap to Search" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="table-body" style="overflow: auto;">
                                    <table class="tb_primary">
                                        <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>WAKTU</th>
                                                <th>KTA</th>
                                                <th>STRUK</th>
                                                <th>TRANSAKSI</th>
                                                <th>KETERANGAN</th>
                                                <th>TOTAL</th>
                                                <th>AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = $mulaiTransaksi+1;
                                                while($row = $queryTransaksi->fetch(PDO::FETCH_ASSOC)):
                                            ?>
                                            <tr>
                                                <th><?=$no++?></th>
                                                <td><?=$row['waktu']?></td>
                                                <td><?=$row['nokta']?></td>
                                                <td><?=$row['notransaksi']?></td>
                                                <td><?=$row['namatransaksi']?></td>
                                                <td><?=$row['keterangan']?></td>
                                                <td><?php
                                                $unMinus = explode('-',$row['totaltransaksi']);
                                                if(count($unMinus) > 1){
                                                echo $control->rupiah($unMinus[1]);
                                                }else{
                                                echo $control->rupiah($unMinus[0]);
                                                }
                                                ?></td>
                                                <td style="display: flex; justify-content:center;">
                                                <?php 
                                                $keterangan = explode(" ",$row['keterangan']);
                                                if($keterangan[0] == 'debit'){
                                                    if($row['namatransaksi'] == "sianggota"){
                                                        echo "
                                                            <a class='icon icon-cetak' target='_blank' href='../cetak/cetak_sianggota.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                                                                <i class='fas fa-print'></i>
                                                            </a>
                                                        ";
                                                    }else{
                                                        echo "
                                                            <a class='icon icon-cetak' target='_blank' href='../cetak/cetak_debit.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                                                                <i class='fas fa-print'></i>
                                                            </a>
                                                        ";
                                                    }
                                                    ?>
                                                <?php }else { 
                                                    if($row['namatransaksi'] == "pengajuan pinjaman"){
                                                        echo "
                                                            <a class='icon icon-cetak' target='_blank' href='../cetak/cetak_pinjaman.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                                                                <i class='fas fa-print'></i>
                                                            </a>
                                                        ";
                                                    }else{
                                                        echo "
                                                            <a class='icon icon-cetak' target='_blank' href='../cetak/cetak_kredit.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                                                                <i class='fas fa-print'></i>
                                                            </a>
                                                        ";
                                                    }
                                                    ?>
                                                <?php }?>
                                                </td>
                                            </tr>
                                            <?php
                                                endwhile;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-footer">
                                    <nav>
                                        <ul class="pagination">
                                            <?php 
                                                if($pageTransaksi > 1){ 
                                            ?>
                                                <li class="page-item active">
                                                    <a href="?dataTransaksi=<?= $pageTransaksi - 1 ?>" class="page-link">Previous</a>
                                                </li>
                                            <?php
                                                } else{
                                            ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">Previous</span>
                                                </li>
                                            <?php
                                                }
                                            for($i = 1; $i <= $pagesTransaksi; $i++): 
                                                if($pageTransaksi === $i){    
                                            ?>
                                                <li class="page-item active">
                                                    <span class="page-link"><?= $i ?></span>
                                                </li>
                                            <?php 
                                                } else {
                                            ?>
                                                <li class="page-item">
                                                    <a href="?dataTransaksi=<?=$i?>" class="page-link"><?= $i ?></a>
                                                </li>    
                                            <?php 
                                                } 
                                                endfor; 
                                                if($pageTransaksi < $pagesTransaksi){ 
                                            ?>
                                                <li class="page-item active">
                                                    <a href="?dataTransaksi=<?= $pageTransaksi + 1 ?>" class="page-link">Next</a>
                                                </li>
                                            <?php
                                                } else{
                                            ?>
                                                <li class="page-item disabled">
                                                    <span class="page-link">Next</span>
                                                </li>
                                            <?php
                                                }
                                            ?>
                                        </ul>
                                    </nav>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- #END Table View -->
                </div>
            </div>
            <!-- #END Body Content -->
        </section>
    </div>
    <script src="../../assets/js/all.min.js"></script>
    <script src="../../assets/js/layout.js"></script>
    <script>
        // tab
        const tabTogle = document.querySelectorAll(".tab .tab-nav ul .tab-item");
        const tabContent = document.querySelectorAll(".table.tab-content");
        tabTogle[0].addEventListener('click', function () {
            if (tabTogle[0].getAttribute('class') == 'tab-item active') {
                tabContent[0].classList.add("active");
                tabTogle[0].classList.add("active");
            } else {
                tabContent[0].classList.add("active");
                tabTogle[0].classList.add("active");
                tabContent[1].classList.remove("active");
                tabTogle[1].classList.remove("active");
            }
        });
        tabTogle[1].addEventListener('click', function () {
            if (tabTogle[1].getAttribute('class') == 'tab-item active') {
                tabContent[1].classList.add("active");
                tabTogle[1].classList.add("active");
            } else {
                tabContent[1].classList.add("active");
                tabTogle[1].classList.add("active");
                tabContent[0].classList.remove("active");
                tabTogle[0].classList.remove("active");
            }
        });
        tabContent[0].classList.add("active");
        tabTogle[0].classList.add("active");
    </script>

    <script>
        const searchAnggota = document.querySelector("input[name='searchAnggota']");
        const tbodyAnggota = document.querySelector(".table.tab-content.anggota tbody");
        searchAnggota.addEventListener('keypress', function() {
        // object ajax
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                tbodyAnggota.innerHTML = xhr.responseText;
            }
        }

        xhr.open('GET', 'search_anggota.php?key=' + searchAnggota.value, true);
        xhr.send();
        });

        const searchTransaksi = document.querySelector("input[name='searchTransaksi']");
        const tbodyTransaksi = document.querySelector(".table.tab-content.transaksi tbody");
        searchTransaksi.addEventListener('keypress', function() {
        // object ajax
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                tbodyTransaksi.innerHTML = xhr.responseText;
            }
        }

        xhr.open('GET', 'search_transaksi.php?key=' + searchTransaksi.value, true);
        xhr.send();
        });
    </script>
</body>

</html>