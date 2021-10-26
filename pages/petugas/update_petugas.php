<?php

require_once '../../assets/library/function.php';
require_once '../../assets/library/function_control.php';
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../loginpage/login.php");
}

$library = new Library();
$control = new Control();
$idpetugas = $_SESSION['key'];

// Get url data parameter
$data = $_GET['data'];

// decrypt $data
$id = $control->hashMethod('decrypt',$data);
$row = "";
$query = "";    
$execute = "";

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$idpetugas'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];
$nickName = explode(" ",$username);

if($id !== false){
    $query = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$id'");
    if($query->rowCount() === 0){
        echo"
            <script>
                alert('data tidak valid');
                document.location.href = 'update_petugas.php?data=$data';
            </script>
        ";
        die;
    } else {
        $row = $query->fetch(PDO::FETCH_ASSOC);
    }
}else{
    echo"
        <script>
            alert('data tidak valid');
            document.location.href = 'update_petugas.php?data=$data';
        </script>
    ";
    die;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Petugas</title>
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
        <section class="content">

            <!-- Start Head Content -->
            <header class="content-header">
                <div class="content-title">
                    <h2>Update Petugas</h3>
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
                                    <h1>Petugas</h1>
                                    <a href="data_petugas.php" class="link" title="data pinjaman">See all</a>
                                </div>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label for="noKtp" class="form-label">no ktp</label>
                                        <input type="text" name="noKtp" class="form-control"
                                            placeholder="Enter your id" required value="<?=$row['noktp']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="namaPetugas" class="form-label">nama petugas</label>
                                        <input type="text" name="namaPetugas" class="form-control"
                                            placeholder="Enter your name" required value="<?=$row['nama']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="tglLahirPetugas" class="form-label">tanggal lahir</label>
                                        <input type="date" name="tglLahirPetugas" class="form-control"
                                            placeholder="min 16 digit" required value="<?=$row['tgllahir']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="form-label">Jenis Kelamin</label>
                                        <div class="radio-control">
                                            <div class="box">
                                                <label for="gender" class="btn-radio">Laki-Laki
                                                    <input type="radio" name="gender" 
                                                    <?php if($row['jenkel'] === 'laki-laki'){ echo 'checked'; }?>
                                                    value="laki-laki" required>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="box">
                                                <label for="gender" class="btn-radio">Perempuan
                                                    <input type="radio" name="gender" 
                                                    <?php if($row['jenkel'] === 'perempuan'){ echo 'checked'; }?> 
                                                    value="perempuan" required>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea cols="30" rows="10" class="form-control" name="alamat" required><?=$row['alamat']?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="jabatan" class="form-label">Jabatan</label>
                                        <select class="form-control" name="jabatan" required>
                                            <option value="">Pilih</option>
                                            <option <?php if($row['jabatan'] === 'pembina'){ echo 'selected'; } ?>  value="pembina">Pembina</option>
                                            <option <?php if($row['jabatan'] === 'ketua'){ echo 'selected'; } ?>  value="ketua">Ketua</option>
                                            <option <?php if($row['jabatan'] === 'sekretaris'){ echo 'selected'; } ?>  value="sekretaris">Sekretaris</option>
                                            <option <?php if($row['jabatan'] === 'bendahara'){ echo 'selected'; } ?>  value="bendahara">Bendahara</option>
                                            <option <?php if($row['jabatan'] === 'pengawas'){ echo 'selected'; } ?>  value="pengawas">Pengawas</option>
                                            <option <?php if($row['jabatan'] === 'pengelola'){ echo 'selected'; } ?>  value="pengelola">Pengelola</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control" name="status" required>
                                            <option value="">Pilih</option>
                                            <option <?php if($row['status'] === 'aktif'){ echo 'selected'; } ?>  value="aktif">Aktif</option>
                                            <option <?php if($row['status'] === 'nonaktif'){ echo 'selected'; } ?>  value="nonaktif">Non Aktif</option>
                                        </select>
                                    </div>
                                    <div class="btn-group">
                                        <button type="submit" name="submit"
                                            class="btn btn-md btn-submit">Simpan</button>
                                        <button type="button" onclick="goBack()"
                                            class="btn btn-md btn-reset">Kembali</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
            <!-- #END Body Content -->
        </section>
    </div>
    <script src="../../assets/js/all.min.js"></script>
    <script src="../../assets/js/layout.js"></script>
    
    <?php
    if(isset($_POST['submit'])){
        if(!isset($_POST['noKtp']) || !isset($_POST['namaPetugas']) || !isset($_POST['gender']) 
        || !isset($_POST['alamat']) || !isset($_POST['jabatan']) || !isset($_POST['tglLahirPetugas'])){
            echo"
                <script>
                    alert('lengkapi data terlebih dahulu');
                    document.location.href = 'addNew_petugas.php';
                </script>
            ";
            die();
        }else{
            $noKtp = htmlspecialchars($_POST['noKtp']);
            $nama = htmlspecialchars(strtolower($_POST['namaPetugas']));
            $gender = htmlspecialchars(strtolower($_POST['gender']));
            $alamat = htmlspecialchars(strtolower($_POST['alamat']));
            $jabatan = htmlspecialchars(strtolower($_POST['jabatan']));
            $tglLahir = htmlspecialchars($_POST['tglLahirPetugas']);
            $status = htmlspecialchars(strtolower($_POST['status']));
            if( $noKtp === "" || $nama === "" || $gender === "" || $alamat === "" || $jabatan === "" || $tglLahir === "" ){
                echo"
                    <script>
                        alert('lengkapi data terlebih dahulu');
                        document.location.href = 'update_petugas.php?data=$data';
                    </script>
                ";
                die;
            }else {
                $execute = $library->updatePetugas($id, $noKtp, $nama, $gender, $alamat, $jabatan, $tglLahir, $status);
                
                if($execute === 1){
                    echo"
                        <script>
                            document.location.href = 'data_petugas.php';
                        </script>
                    ";
                    die;
                } else{
                    echo"
                        <script>
                            alert('gagal mengubah data');
                            document.location.href = 'update_petugas.php?data=$data';
                        </script>
                    ";
                    die;
                }
            }
        }
    }
    ?>
</body>

</html>