<?php
require_once "../../../assets/library/function.php";
require_once "../../../assets/library/function_control.php";
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../loginpage/login.php");
}


$library = new Library();
$control = new Control();
$id = $_SESSION['key'];

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM anggota WHERE nokta='$id'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];
$nickName = explode(" ",$username);

// Pagination
$halaman = 10; //jumlah data perhalaman

// Pagination Transaksi
$pageTransaksi = isset($_GET['dataTransaksi']) ? (int)$_GET['dataTransaksi']:1; //jika $_GET['halaman'] tidak ada maka set jadi 1
$mulaiTransaksi = ($pageTransaksi > 1) ? ($pageTransaksi * $halaman) - $halaman : 0;

$queryRiwayat = "SELECT riwayattransaksi.*, SUM(total) AS totaltransaksi FROM `riwayattransaksi` WHERE nokta='$id' GROUP BY notransaksi";

if(isset($_GET['search'])){
    $queryRiwayat = "SELECT * FROM `riwayattransaksi` WHERE nokta='$id' AND notransaksi='{$_GET['search']}'";
}

if(isset($_GET['search']) && empty($_GET['search'])){
    echo
    "<script>
    document.location.href = 'add_riwayat.php';
    </script>";
    die;
}
$query = $library->conn->query("$queryRiwayat LIMIT $mulaiTransaksi,$halaman");
$dataTransaksi = $library->conn->query("SELECT riwayattransaksi.*, SUM(total) AS totaltransaksi FROM `riwayattransaksi` WHERE nokta='$id' GROUP BY notransaksi");
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
    document.location.href = 'add_riwayat.php';
    </script>";
}   
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat</title>
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
                                    <a href="../transaksi/add_transaksi_masuk.php" class="list-link"><span>Transaksi Masuk</span></a>
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
                            <a href="../riwayat/add_riwayat.php" class="navbar-link active">
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
                    <!-- Start Table View -->
                    <div class="data-view">
                        <div class="table">
                            <div class="table-head">
                                <h1>Data</h1>
                            </div>
                            <div class="table-control">
                                <form action="?" method="get" style="width:400px;">
                                    <div class="tb-search">
                                        <input type="search" name="search" class="input-search"
                                            placeholder="Tap to Search" autocomplete="off">
                                    </div>
                                </form>
                                <!-- <div class="tb-cetak">
                                    <form method="post">
                                        <button type="submit" class="btn btn-print" name="cetak">Cetak</button>
                                        <input type="date" name="tglMulai" class="tgl-mulai">
                                        <span>-</span>
                                        <input type="date" name="tglSelesai" class="tgl-selesai">
                                    </form>
                                </div> -->
                            </div>
                            <div class="table-body" style="overflow: auto;">
                            <?php if(isset($_GET['search'])){ ?>
                                <table class="tb_primary">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>WAKTU</th>
                                            <th>STRUK</th>
                                            <th>TRANSAKSI</th>
                                            <th>KETERANGAN</th>
                                            <th>TOTAL</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no = 1;
                                            $total = 0;
                                            while($row = $query->fetch(PDO::FETCH_ASSOC)):
                                            $total += $row['total'];
                                        ?>
                                        <tr>
                                            <th><?=$no++?></th>
                                            <td><?=$row['waktu']?></td>
                                            <td><?=$row['notransaksi']?></td>
                                            <td><?=$row['namatransaksi']?></td>
                                            <td><?=$row['keterangan']?></td>
                                            <td><?=$control->rupiah($row['total'])?></td>
                                            <td style="display: flex; justify-content:center;">
                                            <?php 
                                            $keterangan = explode(" ",$row['keterangan']);
                                            if($keterangan[0] == 'debit'){
                                                if($row['namatransaksi'] == "sianggota"){
                                                    echo "
                                                        <a class='icon icon-cetak' target='_blank' href='../../../pages/cetak/cetak_sianggota.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                                                            <i class='fas fa-print'></i>
                                                        </a>
                                                    ";
                                                }else{
                                                    echo "
                                                        <a class='icon icon-cetak' target='_blank' href='../../../pages/cetak/cetak_debit.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                                                            <i class='fas fa-print'></i>
                                                        </a>
                                                    ";
                                                }
                                                ?>
                                            <?php }else { 
                                                if($row['namatransaksi'] == "pengajuan pinjaman"){
                                                    echo "
                                                        <a class='icon icon-cetak' target='_blank' href='../../../pages/cetak/cetak_pinjaman.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                                                            <i class='fas fa-print'></i>
                                                        </a>
                                                    ";
                                                }else{
                                                    echo "
                                                        <a class='icon icon-cetak' target='_blank' href='../../../pages/cetak/cetak_kredit.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
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
                                        <tr style="font-weight:900;">
                                            <td colspan="5">Saldo</td>
                                            <td><?=$control->rupiahSecound($total)?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php }else{ ?>
                                <table class="tb_primary">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>WAKTU</th>
                                            <th>STRUK</th>
                                            <th>TOTAL</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no = 1;
                                            while($row = $query->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <tr>
                                            <th><?=$no++?></th>
                                            <td><?=$row['waktu']?></td>
                                            <td><?=$row['notransaksi']?></td>
                                            <td><?=$control->rupiah($row['totaltransaksi'])?></td>
                                            <td style="display: flex; justify-content:center;">
                                            <?php 
                                            $keterangan = explode(" ",$row['keterangan']);
                                            if($keterangan[0] == 'debit'){
                                                if($row['namatransaksi'] == "sianggota"){
                                                    echo "
                                                        <a class='icon icon-cetak' target='_blank' href='../../../pages/cetak/cetak_sianggota.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                                                            <i class='fas fa-print'></i>
                                                        </a>
                                                    ";
                                                }else{
                                                    echo "
                                                        <a class='icon icon-cetak' target='_blank' href='../../../pages/cetak/cetak_debit.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                                                            <i class='fas fa-print'></i>
                                                        </a>
                                                    ";
                                                }
                                                ?>
                                            <?php }else { 
                                                if($row['namatransaksi'] == "pengajuan pinjaman"){
                                                    echo "
                                                        <a class='icon icon-cetak' target='_blank' href='../../../pages/cetak/cetak_pinjaman.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                                                            <i class='fas fa-print'></i>
                                                        </a>
                                                    ";
                                                }else{
                                                    echo "
                                                        <a class='icon icon-cetak' target='_blank' href='../../../pages/cetak/cetak_kredit.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
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
                            <?php } ?>
                            </div>
                            <div class="table-footer">
                                <nav>
                                    <ul class="pagination">
                                        <?php 
                                            if($pageTransaksi > 1){ 
                                        ?>
                                            <li class="page-item active">
                                                <a href="?dataTransaksi=<?= $pageTransaksi - 1 ?>&nokta=<?=$id?>" class="page-link">Previous</a>
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
                                                <a href="?dataTransaksi=<?=$i?>&nokta=<?=$id?>" class="page-link"><?= $i ?></a>
                                            </li>    
                                        <?php 
                                            } 
                                            endfor; 
                                            if($pageTransaksi < $pagesTransaksi){ 
                                        ?>
                                            <li class="page-item active">
                                                <a href="?dataTransaksi=<?= $pageTransaksi + 1 ?>&nokta=<?=$id?>" class="page-link">Next</a>
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
                    <!-- #END Table View -->
                </div>
            </div>
            <!-- #END Body Content -->
        </section>
    </div>
    <script src="../../../assets/js/all.min.js"></script>
    <script src="../../../assets/js/layout.js"></script>

    <script>
        // const searchTransaksi = document.querySelector("input[name='searchTransaksi']");
        // const tbodyTransaksi = document.querySelector(".table.tab-content.transaksi tbody");
        // searchTransaksi.addEventListener('keypress', function() {
        // // object ajax
        // const xhr = new XMLHttpRequest();
        // xhr.onreadystatechange = function () {
        //     if (xhr.readyState === 4 && xhr.status === 200) {
        //         console.log(xhr.responseText);
        //         tbodyTransaksi.innerHTML = xhr.responseText;
        //     }
        // }

        // xhr.open('GET', 'search_transaksi.php?key=' + searchTransaksi.value, true);
        // xhr.send();
        // });
    </script>
</body>

</html>