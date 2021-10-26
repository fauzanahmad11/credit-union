<?php
require_once "../assets/library/function.php";
require_once "../assets/library/function_control.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../pages/loginpage/login.php");
}

if (isset($_SESSION['user']) && $_SESSION['user'] == 'petugas') {
    header("Location: ../index.php");
}

$library = new Library();
$control = new Control();
$waktuNow = date_create(date("Y-m-d"));
$id = $_SESSION['key'];

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM anggota WHERE nokta='$id'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];
$nickName = explode(" ", $username);

// count simpanan
$countSiwajib       = $library->conn->query("SELECT SUM(subtotal) AS saldo FROM `siwapo` WHERE nokta='$id' AND status='aktif' AND keterangan LIKE '%siwajib%'")->fetch(PDO::FETCH_ASSOC);
$countSipokok       = $library->conn->query("SELECT SUM(subtotal) AS saldo FROM `siwapo` WHERE nokta='$id' AND status='aktif' AND keterangan LIKE '%sipokok%'")->fetch(PDO::FETCH_ASSOC);
$querySianggota     = $library->conn->query("SELECT * FROM sianggota WHERE nokta='$id' AND `status`='aktif'")->fetch(PDO::FETCH_ASSOC);
$countSianggota     = $querySianggota['dana'] + $querySianggota['totalbunga'];
$countAngsuran      = $library->conn->query("SELECT saldokredit AS saldo FROM angsuran WHERE nokta='$id' AND `status`='aktif' ORDER BY idangsuran ASC")->fetch(PDO::FETCH_ASSOC);
$countSisukarela    = $library->conn->query("SELECT saldo FROM `sisukarela` WHERE nokta='$id' AND `status`='aktif' ORDER BY waktutransaksi DESC LIMIT 0,1")->fetch(PDO::FETCH_ASSOC);
$countSimapan       = $library->conn->query("SELECT SUM(nilai) AS saldo FROM `simapan` WHERE nokta='$id' AND status='aktif'")->fetch(PDO::FETCH_ASSOC);

// Reminder SIANGGOTA
$stringSianggota = "SELECT * FROM `view-sianggota` WHERE `status`='aktif' AND nokta='$id'";
$sianggota = $library->conn->query($stringSianggota);

// Reminder Pinjaman
$stringPinjaman = "SELECT *, DATE(waktuangsuran) AS waktuakhir FROM `reminderpinjaman` WHERE `status`='aktif' AND nokta='$id'";
$pinjaman = $library->conn->query($stringPinjaman);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/layout.css">
    <link rel="stylesheet" href="../assets/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/formStyle.css">
    <link rel="icon" href="../assets/img/icon/favicon2.png" type="image/png" sizes="16x16">
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
                        <img src="../assets/img/icon/logo.svg" alt="">
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
                            <a href="index.php" class="navbar-link active">
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
                                    <a href="../client/pages/transaksi/add_transaksi_masuk.php" class="list-link"><span>Transaksi Masuk</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../client/pages/transaksi/add_transaksi_simapan.php" class="list-link"><span>Transaksi Simapan</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../client/pages/transaksi/add_transaksi_sianggota.php" class="list-link"><span>Transaksi Sianggota</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../client/pages/transaksi/add_transaksi_pinjaman.php" class="list-link"><span>Transaksi Pinjaman</span></a>
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
                                    <a href="../client/pages/data/simpanan_pokok.php" class="list-link"><span>Simpanan Pokok</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../client/pages/data/simpanan_wajib.php" class="list-link"><span>Simpanan Wajib</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../client/pages/data/simpanan_sukarela.php" class="list-link"><span>Simpanan Sukarela</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../client/pages/data/simpanan_anggota.php" class="list-link"><span>Simpanan Anggota</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../client/pages/data/simpanan_masadepan.php" class="list-link"><span>Simpanan Masa Depan</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../client/pages/data/pinjaman.php" class="list-link"><span>Pinjaman</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../client/pages/data/angsuran.php" class="list-link"><span>Angsuran</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <h5 class="head" style="margin-top: 0px;">Help</h5>
                    <ul class="navbar-nav">
                        <li class="navbar-items">
                            <a href="../client/pages/riwayat/add_riwayat.php" class="navbar-link">
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
                    <h2>Dashboard</h3>
                </div>
                <div class="user-dropdown">
                    <a href="#" class="dropdown-toggle">
                        <h4 class="user-name"><?= $nickName[0] ?></h4>
                    </a>
                    <div class="dropdown-menu">
                        <ul class="dropdown-item">
                            <li class="list-menu">
                                <a href="#" class="link-menu">
                                    <span class="icon"><i class="fas fa-id-badge"></i></span>
                                    <span class="title"><?= $id ?></span>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="../pages/loginpage/logout.php" class="link-menu">
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

                    <!-- Start Counter -->
                    <div class="counter d-count">
                        <div class="counter-items">
                            <a href="#" class="link-items">
                                <div class="head">
                                    <span class="icon"><i class="fas fa-wallet fa-3x"></i></span>
                                    <h1 class="small-count"><?= $control->rupiahSecound($countSipokok['saldo']) ?></h1>
                                </div>
                                <div class="title">
                                    <h5>Simpanan Pokok</h5>
                                </div>
                            </a>
                        </div>
                        <div class="counter-items">
                            <a href="#" class="link-items">
                                <div class="head">
                                    <span class="icon"><i class="fas fa-wallet fa-3x"></i></span>
                                    <h1 class="small-count"><?= $control->rupiahSecound($countSiwajib['saldo']) ?></h1>
                                </div>
                                <div class="title">
                                    <h5>Simpanan Wajib</h5>
                                </div>
                            </a>
                        </div>
                        <div class="counter-items">
                            <a href="#" class="link-items">
                                <div class="head">
                                    <span class="icon"><i class="fas fa-wallet fa-3x"></i></span>
                                    <h1 class="small-count"><?= $control->rupiahSecound($countSimapan['saldo']) ?></h1>
                                </div>
                                <div class="title">
                                    <h5>Simpanan Masa Depan</h5>
                                </div>
                            </a>
                        </div>
                        <div class="counter-items">
                            <a href="#" class="link-items">
                                <div class="head">
                                    <span class="icon"><i class="fas fa-wallet fa-3x"></i></span>
                                    <h1 class="small-count"><?= $control->rupiahSecound($countSisukarela['saldo']) ?></h1>
                                </div>
                                <div class="title">
                                    <h5>Simpanan Sukarela</h5>
                                </div>
                            </a>
                        </div>
                        <div class="counter-items">
                            <a href="#" class="link-items">
                                <div class="head">
                                    <span class="icon"><i class="fas fa-wallet fa-3x"></i></span>
                                    <h1 class="small-count"><?= $control->rupiahSecound($countSianggota) ?></h1>
                                </div>
                                <div class="title">
                                    <h5>Simpanan Anggota</h5>
                                </div>
                            </a>
                        </div>
                        <div class="counter-items">
                            <a href="#" class="link-items">
                                <div class="head">
                                    <span class="icon"><i class="fas fa-wallet fa-3x"></i></span>
                                    <h1 class="small-count"><?= $control->rupiahSecound($countAngsuran['saldo']) ?></h1>
                                </div>
                                <div class="title">
                                    <h5>Angsuran</h5>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- #END Counter -->

                    <!-- Start Table View -->
                    <div class="data-view">
                        <div class="table">
                            <div class="table-head">
                                <h1>Angsuran</h1>
                                <a href="pages/data/angsuran.php" target="_blank" class="link" title="data pinjaman">See all</a>
                            </div>
                            <div class="table-body">
                                <table class="tb_secondary capitalize">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>WAKTU</th>
                                            <th>KE-</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $idPinjaman = null;
                                        $sumDataPinjaman = false;
                                        $angsuranPertama = null;

                                        while ($row = $pinjaman->fetch(PDO::FETCH_ASSOC)) :
                                            $waktuAkhirPinjaman = date_create($row['waktuakhir']);
                                            $selisihPinjaman = date_diff($waktuNow, $waktuAkhirPinjaman)->format("%R%a");
                                            $angsuranPertama .= $selisihPinjaman . " ";

                                            if ($selisihPinjaman > 0 && $selisihPinjaman <= 5) :
                                                $sumDataPinjaman = true;
                                        ?>
                                                <tr>
                                                    <th><?= $no ?></th>
                                                    <td><?= $row['nokta'] . " - " . $row['nama'] ?></td>
                                                    <td><span <?= ($selisihPinjaman <= 3 ? "class='red-count'" : "") ?>> <?= $selisihPinjaman ?> </span> Hari Lagi</td>
                                                    <td><?= $row['noangsuran'] ?></td>
                                                    <td>
                                                        <a href="pages/transaksi/add_transaksi_masuk.php" class="icon-action" title="pay">
                                                            <!-- <img src="assets/img/icon/icon-detail.svg" class="icon"
                                                        alt="icon-detail"> -->
                                                            <i class="fas fa-receipt fa-lg icon-send"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                        <?php
                                                $no++;
                                            endif;
                                        endwhile;

                                        if ($sumDataPinjaman != true && $pinjaman->rowCount() > 0) {
                                            echo "
                                            <tr>
                                                <td colspan='5'>
                                                    <div class='no-exist'>
                                                        <img src='../assets/img/icon/icon-data-not-found.svg' alt=''> 
                                                        <div class='shadow'></div>
                                                        <div class='text'><h4><span>Angsuran selanjutnya</span> dalam <span> " . explode(' ', $angsuranPertama)[0] . " hari lagi</span></h4></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            ";
                                        }
                                        if ($sumDataPinjaman != true && $pinjaman->rowCount() < 1) {
                                            echo "
                                            <tr>
                                                <td colspan='5'>
                                                    <div class='no-exist'>
                                                        <img src='../assets/img/icon/icon-data-not-found.svg' alt=''> 
                                                        <div class='shadow'></div>
                                                        <div class='text'><h4><span>Tidak</span> ada <span>data</span></h4></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            ";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="table">
                            <div class="table-head">
                                <h1>Deposit</h1>
                                <a href="pages/data/simpanan_anggota.php" target="_blank" class="link" title="data simpanan">See all</a>
                            </div>
                            <div class="table-body">
                                <table class="tb_secondary capitalize">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>WAKTU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $idSianggota = null;
                                        $sumDataSianggota = false;
                                        $sianggotaPertama = null;

                                        while ($row = $sianggota->fetch(PDO::FETCH_ASSOC)) :
                                            $waktuAkhirSianggota = date_create($row['tgl_keluar']);
                                            $selisihSianggota = date_diff($waktuNow, $waktuAkhirSianggota)->format("%R%a");
                                            $sianggotaPertama .= $selisihSianggota . " ";

                                            if ($selisihSianggota > 0 && $selisihSianggota <= 5) :
                                                $sumDataSianggota = true;
                                        ?>
                                                <tr>
                                                    <th><?= $no ?></th>
                                                    <td><?= $row['nokta'] . " - " . $row['nama'] ?></td>
                                                    <td><span <?= ($selisihSianggota <= 3 ? "class='red-count'" : "") ?>> <?= $selisihSianggota ?> </span> Hari Lagi</td>
                                                </tr>
                                        <?php
                                                $no++;
                                            endif;
                                        endwhile;


                                        if ($sumDataSianggota != true && $sianggota->rowCount() > 0) {
                                            echo "
                                            <tr>
                                                <td colspan='4'>
                                                    <div class='no-exist'>
                                                        <img src='../assets/img/icon/icon-data-not-found.svg' alt=''> 
                                                        <div class='shadow'></div>
                                                        <div class='text'><h4><span>Deposit selanjutnya</span> dalam <span> " . explode(' ', $sianggotaPertama)[0] . " hari lagi</span></h4></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            ";
                                        }
                                        if ($sumDataSianggota != true && $sianggota->rowCount() < 1) {
                                            echo "
                                            <tr>
                                                <td colspan='4'>
                                                    <div class='no-exist'>
                                                        <img src='../assets/img/icon/icon-data-not-found.svg' alt=''> 
                                                        <div class='shadow'></div>
                                                        <div class='text'><h4><span>Tidak</span> ada <span>data</span></h4></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            ";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- #END Table View -->
                </div>
            </div>
            <!-- #END Body Content -->
        </section>
    </div>

    <script src="../assets/js/all.min.js"></script>
    <script src="../assets/js/layout.js"></script>
</body>

</html>