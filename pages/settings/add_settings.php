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

// tampilkan data master harga
$masterSiwajib      = $library->conn->query("SELECT*FROM masterharga WHERE nama='simpanan wajib';");
$rowMasterSiwajib   = $masterSiwajib->fetch(PDO::FETCH_ASSOC);
$masterSipokok      = $library->conn->query("SELECT*FROM masterharga WHERE nama='simpanan pokok';");
$rowMasterSipokok   = $masterSipokok->fetch(PDO::FETCH_ASSOC);
$masterSimapan      = $library->conn->query("SELECT*FROM masterharga WHERE nama='simpanan masadepan';");
$rowMasterSimapan   = $masterSimapan->fetch(PDO::FETCH_ASSOC);
$masterSisukarela   = $library->conn->query("SELECT*FROM masterharga WHERE nama='simpanan sukarela';");
$rowMasterSisukarela= $masterSisukarela->fetch(PDO::FETCH_ASSOC);
$masterSianggota    = $library->conn->query("SELECT*FROM masterharga WHERE nama='simpanan anggota';");
$rowMasterSianggota = $masterSianggota->fetch(PDO::FETCH_ASSOC);
$masterPinjaman     = $library->conn->query("SELECT*FROM masterharga WHERE nama='pinjaman';");
$rowMasterPinjaman  = $masterPinjaman->fetch(PDO::FETCH_ASSOC);

// tampilkan data master bunga
$queryBunga = $library->conn->query("SELECT*FROM masterbunga");

if(isset($_POST['submitHarga'])){

    $masterHarga = array(
        $minHargaSiwajib    = $control->unRupiah($_POST['minHargaSiwajib']),
        $maxHargaSiwajib    = $control->unRupiah($_POST['maxHargaSiwajib']),
        $minHargaSipokok    = $control->unRupiah($_POST['minHargaSipokok']),
        $maxHargaSipokok    = $control->unRupiah($_POST['maxHargaSipokok']),
        $minHargaSimapan    = $control->unRupiah($_POST['minHargaSimapan']),
        $maxHargaSimapan    = $control->unRupiah($_POST['maxHargaSimapan']),
        $minHargaSisukarela = $control->unRupiah($_POST['minHargaSisukarela']),
        $maxHargaSisukarela = $control->unRupiah($_POST['maxHargaSisukarela']),
        $minHargaSianggota  = $control->unRupiah($_POST['minHargaSianggota']),
        $maxHargaSianggota  = $control->unRupiah($_POST['maxHargaSianggota']),
        $minHargaPinjaman   = $control->unRupiah($_POST['minHargaPinjaman']),
        $maxHargaPinjaman   = $control->unRupiah($_POST['maxHargaPinjaman'])
    );
    
    // CEK INPUT VALUE WAJIB NUMERIC/format yang telah ditentukan
    for($i = 0; $i < count($masterHarga); $i++){
        // $data = explode(" ",$masterHarga[$i]);
        if(!is_numeric($masterHarga[$i])){
            echo "
                <script>
                    alert('Format yang anda masukan salah');
                    document.location.href='add_settings.php';
                </script>
            ";
            die;
        }
    }
    
    $result = $library->insertMasterHarga($idpetugas, $minHargaSiwajib, $maxHargaSiwajib, $minHargaSipokok, $maxHargaSipokok, $minHargaSimapan
    , $maxHargaSimapan, $minHargaSisukarela, $maxHargaSisukarela, $minHargaSianggota, $maxHargaSianggota, $minHargaPinjaman, $maxHargaPinjaman);
}

$editBunga = null;
$jangkaBunga = null;
$waktuBunga = null;
$jenisBunga = null;


if(isset($_GET['action']) && isset($_GET['key']) && $_GET['action'] == "edit"){
    $query = $library->conn->query("SELECT*FROM masterbunga WHERE idbunga='".$_GET['key']."'");
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if($query->rowCount() < 1){
        echo "
            <script>
                document.location.href='add_settings.php';
            </script>
        ";
        die;
    }

    $explodeBunga = explode('.',$row['total']);
    $editBunga = ((count($explodeBunga) > 1) && ($explodeBunga[0] != 0) ? $row['total']*1 : $row['total']*100);

    $data = explode(" ",$row['keterangan']);
    $jangkaBunga = $data[0];
    $waktuBunga = $data[1];
    $jenisBunga = $row['namabunga'];
}

if(isset($_POST['submitBunga'])){
    $id         = (isset($_POST['idBunga']) ? htmlspecialchars($_POST['idBunga']) : 0);
    $getBunga   = htmlspecialchars($_POST['bunga']);
    $waktu      = htmlspecialchars($_POST['waktu']);
    $jangka     = htmlspecialchars(strtolower($_POST['jangka']));
    $jenis      = htmlspecialchars(strtolower($_POST['jenis']));

    $bunga = $getBunga * 0.01;
    // $bunga = null;
    // if(is_float($getBunga)){
    //     $bunga = $getBunga;
    // }else{
    //     $bunga = $getBunga*0.01;
    // }

    $result = $library->insertMasterBunga($idpetugas, $id, $bunga, $waktu, $jangka, $jenis);
    
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="icon" href="../../assets/img/icon/favicon2.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="../../assets/css/layout.css">
    <link rel="stylesheet" href="../../assets/css/formStyle.css">
    <link rel="stylesheet" href="../../assets/css/all.min.css">
</head>

<body>
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
                            <a href="add_settings.php" class="navbar-link active">
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
        <section class="content">

            <!-- Start Head Content -->
            <header class="content-header">
                <div class="content-title">
                    <h2>Settings</h3>
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
                                    <li class="tab-item"><a class="tab-link">Master Harga</a></li>
                                    <li class="tab-item"><a class="tab-link">Master Bunga</a></li>
                                </ul>
                            </nav>
                            <div class="table tab-content">
                                <div class="tab-form">
                                    <form method="post">
                                        <div class="form-group">
                                            <label class="form-label">simpanan wajib</label>
                                            <!-- double group -->
                                            <div class="d-group">
                                                <div class="group-item">
                                                    <label class="form-label" for="minHargaSiwajib">min</label>
                                                    <input type="text" name="minHargaSiwajib" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterSiwajib['min'] ?>" required>
                                                </div>
                                                <div class="group-item">
                                                    <label class="form-label" for="maxHargaSiwajib">max</label>
                                                    <input type="text" name="maxHargaSiwajib" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterSiwajib['max'] ?>" required>
                                                </div>
                                            <!-- double group -->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">simpanan pokok</label>
                                            <!-- double group -->
                                            <div class="d-group">
                                                <div class="group-item">
                                                    <label class="form-label" for="minHargaSipokok">min</label>
                                                    <input type="text" name="minHargaSipokok" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterSipokok['min'] ?>" required>
                                                </div>
                                                <div class="group-item">
                                                    <label class="form-label" for="maxHaragaSipokok">max</label>
                                                    <input type="text" name="maxHargaSipokok" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterSipokok['max'] ?>" required>
                                                </div>
                                            <!-- double group -->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">simpanan masa depan</label>
                                            <!-- double group -->
                                            <div class="d-group">
                                                <div class="group-item">
                                                    <label class="form-label" for="minHargaSimapan">min</label>
                                                    <input type="text" name="minHargaSimapan" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterSimapan['min'] ?>" required>
                                                </div>
                                                <div class="group-item">
                                                    <label class="form-label" for="masHargaSimapan">max</label>
                                                    <input type="text" name="maxHargaSimapan" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterSimapan['max'] ?>" required>
                                                </div>
                                            <!-- double group -->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">simpanan sukarela</label>
                                            <!-- double group -->
                                            <div class="d-group">
                                                <div class="group-item">
                                                    <label class="form-label" for="minHargaSisukarela">min</label>
                                                    <input type="text" name="minHargaSisukarela" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterSisukarela['min'] ?>" required>
                                                </div>
                                                <div class="group-item">
                                                    <label class="form-label" for="maxHargaSisukarela">max</label>
                                                    <input type="text" name="maxHargaSisukarela" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterSisukarela['max'] ?>" required>
                                                </div>
                                            <!-- double group -->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">simpanan anggota</label>
                                            <!-- double group -->
                                            <div class="d-group">
                                                <div class="group-item">
                                                    <label class="form-label" for="minHargaSianggota">min</label>
                                                    <input type="text" name="minHargaSianggota" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterSianggota['min'] ?>" required>
                                                </div>
                                                <div class="group-item">
                                                    <label class="form-label" for="maxHargaSianggota">max</label>
                                                    <input type="text" name="maxHargaSianggota" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterSianggota['max'] ?>" required>
                                                </div>
                                            <!-- double group -->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">pinjaman</label>
                                            <!-- double group -->
                                            <div class="d-group">
                                                <div class="group-item">
                                                    <label class="form-label" for="minHargaPinjaman">min</label>
                                                    <input type="text" name="minHargaPinjaman" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterPinjaman['min'] ?>" required>
                                                </div>
                                                <div class="group-item">
                                                    <label class="form-label" for="maxHargaPinjaman">max</label>
                                                    <input type="text" name="maxHargaPinjaman" class="form-control rupiah"
                                                        placeholder="Example : 100000" value="<?= $rowMasterPinjaman['max'] ?>" required>
                                                </div>
                                            <!-- double group -->
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <button type="submit" name="submitHarga"
                                                class="btn btn-md btn-submit">Simpan</button>
                                            <button type="reset" name="reset"
                                                class="btn btn-md btn-reset">Reset</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tb_primary" style="display:none;">
                                    <div class="tbody"><input type="search" class="input-search"></div>
                                </div>
                            </div>
                            <div class="table tab-content">
                                <div class="tab-form">
                                    <form method="post">
                                        <div class="d-s-group">
                                            <div class="group-item">
                                                <div class="head-item"><h5>Bunga Pinjaman & Bunga Sianggota</h5></div>
                                                <div class="form-group">
                                                    <label for="" class="form-label">Jenis Bunga</label>
                                                    <div class="radio-control">
                                                        <div class="box">
                                                            <label for="jenis" class="btn-radio">Pinjaman
                                                                <input type="radio" <?php if($jenisBunga == 'bunga pinjaman' ){echo 'checked';} ?> name="jenis" value="bunga pinjaman" required>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                        <div class="box">
                                                            <label for="jenis" class="btn-radio">Simpanan Anggota
                                                                <input type="radio" <?php if($jenisBunga == 'bunga sianggota' ){echo 'checked';} ?> name="jenis" value="bunga sianggota" required>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="bunga" class="form-label">Bunga</label>
                                                    <!-- Sticky Form Group -->
                                                    <div class="s-f-group">
                                                        <input type="text" id="bunga" value="<?= $editBunga ?>" name="bunga" autocomplete="off" class="form-control" max="100" min="0"
                                                            placeholder="Example : 8 atau 9.6" required>
                                                        <span class="symbol">%</span>
                                                    </div>
                                                    <!-- Sticky Form Group -->
                                                </div>

                                                <div class="form-group">
                                                    <label for="waktu" class="form-label">Jangka Waktu</label>
                                                    <!-- Sticky Form Group -->
                                                    <div class="s-f-group">
                                                        <input type="number" name="waktu" id="waktu" value="<?= $jangkaBunga ?>" autocomplete="off" class="form-control d-typing" max="12" min="0"
                                                        placeholder="Example : 1 || Max = 12, Min = 0" required>
                                                        <select class="form-control" name="jangka" required>
                                                            <option <?php if($waktuBunga == 'tahun' ){echo 'selected';} ?> value="Tahun">Tahun</option>
                                                            <option <?php if($waktuBunga == 'bulan' ){echo 'selected';} ?> value="Bulan">Bulan</option>
                                                            <option <?php if($waktuBunga == 'minggu' ){echo 'selected';} ?> value="Minggu">Minggu</option>
                                                        </select>
                                                    </div>
                                                    <!-- Sticky Form Group -->
                                                    <?php
                                                    if(isset($_GET['action']) && isset($_GET['key'])){
                                                    ?>
                                                    <input type="hidden" name="idBunga" value="<?= $_GET['key'] ?>">
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <button type="submit" name="submitBunga"
                                                class="btn btn-md btn-submit">Simpan</button>
                                            <button type="reset" name="reset"
                                                class="btn btn-md btn-reset">Reset</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="table-body">
                                    <table class="tb_primary">
                                        <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>NAMA</th>
                                                <th>JANGKA</th>
                                                <th>TOTAL</th>
                                                <th>AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $no = 1;
                                                while($row = $queryBunga->fetch(PDO::FETCH_ASSOC)):
                                                $explodeBunga = explode('.',$row['total']);
                                            ?>
                                            <tr>
                                                <th><?=$no?></th>
                                                <td><?=$row['namabunga']?></td>
                                                <td><?=$row['keterangan']?></td>
                                                <td><?=$bunga = ((count($explodeBunga) > 1) && ($explodeBunga[0] != 0) ? $row['total']*1 : $row['total']*100)?> %</td>
                                                <td>
                                                    <a href="?action=edit&key=<?=$row['idbunga']?>" class="icon icon-update" title="ubah data">
                                                        <img src="../../assets/img/icon/icon-update2.svg"
                                                            class="icon-action" alt="icon-update">
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                                $no++;
                                                endwhile;
                                            ?>
                                        </tbody>
                                    </table>
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
    <script src="../../assets/js/format-input.js"></script>
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
</body>

</html>