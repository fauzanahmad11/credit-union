<?php
require_once "../../assets/library/function.php";
require_once "../../assets/library/function_control.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../loginpage/login.php");
}


$library = new Library();
$control = new Control();
$idpetugas = $_SESSION['key'];

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$idpetugas'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];
$nickName = explode(" ", $username);

// Pagination
$halaman = 10; //jumlah data perhalaman
// if(isset($_GET['halaman'])){(int)$_GET['halaman']} else{1}
$page = isset($_GET['data']) ? (int)$_GET['data'] : 1; //jika $_GET['halaman'] tidak ada maka set jadi 1
$mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
$query = $library->conn->query("SELECT*FROM petugas LIMIT $mulai,$halaman");
$data = $library->conn->query("SELECT*FROM petugas");
$totalData = $data->rowCount();
$pages = ceil($totalData / $halaman);

if (isset($_POST['cetak'])) {
    $tglMulai = htmlspecialchars(strtolower($_POST['tglMulai']));
    $tglSelesai = htmlspecialchars(strtolower($_POST['tglSelesai']));
    if (!empty($tglMulai) && !empty($tglSelesai)) {
        if ($tglMulai > $tglSelesai) {
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
        window.open('../cetak/cetak_petugas.php?start=$tglMulai&end=$tglSelesai');
        document.location.href = 'data_petugas.php';
        </script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas</title>
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
                                    <a href="../petugas/addNew_petugas.php" class="list-link"><span>Tambah Petugas</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../petugas/data_petugas.php" class="list-link active"><span>Data Petugas</span></a>
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
                                    <a href="../anggota/add_anggota.php" class="list-link active"><span>Tambah Anggota</span></a>
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
                                    <a href="../transaksi/add_transaksi_keluar.php" class="list-link active"><span>Transaksi Keluar</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../transaksi/add_transaksi_simapan.php" class="list-link"><span>Transaksi Simapan</span></a>
                                </li>
                                <li class="list-item">
                                    <a href="../transaksi/add_transaksi_sianggota.php" class="list-link active"><span>Transaksi Sianggota</span></a>
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
        <section class="content">

            <!-- Start Head Content -->
            <header class="content-header">
                <div class="content-title">
                    <h2>Petugas</h3>
                </div>
                <div class="user-dropdown">
                    <a href="#" class="dropdown-toggle">
                        <h4 class="user-name"><?= $nickName[0] ?></h4>
                    </a>
                    <div class="dropdown-menu">
                        <ul class="dropdown-item">
                            <li class="list-menu">
                                <a href="../petugas/profile_petugas.php?data=<?= $control->hashMethod('encrypt', $idpetugas) ?>" class="link-menu">
                                    <span class="icon"><i class="fas fa-user-alt"></i></span>
                                    <span class="title">Profil</span>
                                </a>
                            </li>
                            <li class="list-menu">
                                <a href="../petugas/setting_petugas.php?data=<?= $control->hashMethod('encrypt', $idpetugas) ?>" class="link-menu">
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
                        <div class="table">
                            <div class="table-head">
                                <h1>Data</h1>
                                <a href="addNew_petugas.php" class="link" title="data pinjaman">add new</a>
                            </div>
                            <div class="table-control">
                                <div class="tb-search">
                                    <input type="search" name="search" class="input-search petugas" placeholder="Tap to Search" autocomplete="off">
                                </div>
                                <div class="tb-cetak">
                                    <form method="post">
                                        <button type="submit" name="cetak" class="btn btn-print">Cetak</button>
                                        <input type="date" name="tglMulai" class="tgl-mulai">
                                        <span>-</span>
                                        <input type="date" name="tglSelesai" class="tgl-selesai">
                                    </form>
                                </div>
                            </div>
                            <div class="table-body" style="overflow: auto;">
                                <table class="tb_primary">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NO KTP</th>
                                            <th>NAMA</th>
                                            <th>GENDER</th>
                                            <th>ALAMAT</th>
                                            <th>JABATAN</th>
                                            <th>STATUS</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = $mulai+1;
                                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) :
                                        if($row['noktp'] !== '7401072407980001'):
                                        ?>
                                            <tr>
                                                <th><?= $no++ ?></th>
                                                <td><?= $row['noktp'] ?></td>
                                                <td><?= $row['nama'] ?></td>
                                                <td><?= $row['jenkel'] ?></td>
                                                <td><?= $row['alamat'] ?></td>
                                                <td><?= $row['jabatan'] ?></td>
                                                <td>
                                                    <?php
                                                    if ($row['status'] === 'aktif') {
                                                        echo "Aktif";
                                                    } else {
                                                        echo "Non Aktif";
                                                    }
                                                    ?>
                                                </td>
                                                <td style="display: flex; justify-content:center;">
                                                    <a class="icon icon-detail" href="#" title="lihat detail data" value="<?= $control->hashMethod('encrypt', $row['idpetugas']) ?>">
                                                        <img src="../../assets/img/icon/icon-detail2.svg" class="icon-action" alt="icon-detail">
                                                    </a>
                                                    <a href="update_petugas.php?data=<?= $control->hashMethod('encrypt', $row['idpetugas']) ?>" class="icon icon-update" title="ubah data">
                                                        <img src="../../assets/img/icon/icon-update2.svg" class="icon-action" alt="icon-update">
                                                    </a>
                                                    <a href="delete_petugas.php?data=<?= $control->hashMethod('encrypt', $row['idpetugas']) ?>" class="icon icon-delete" title="hapus data" onclick="return confirm('Menghapus data ini adalah menonaktifkan petugas. Yakin?');">
                                                        <img src="../../assets/img/icon/icon-delete2.svg" class="icon-action" alt="icon-delete">
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                        endif;
                                        endwhile;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-footer">
                                <nav>
                                    <ul class="pagination">
                                        <?php
                                        if ($page > 1) {
                                        ?>
                                            <li class="page-item active">
                                                <a href="?data=<?= $page - 1 ?>" class="page-link">Previous</a>
                                            </li>
                                        <?php
                                        } else {
                                        ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">Previous</span>
                                            </li>
                                            <?php
                                        }
                                        for ($i = 1; $i <= $pages; $i++) :
                                            if ($page === $i) {
                                            ?>
                                                <li class="page-item active">
                                                    <span class="page-link"><?= $i ?></span>
                                                </li>
                                            <?php
                                            } else {
                                            ?>
                                                <li class="page-item">
                                                    <a href="?data=<?= $i ?>" class="page-link"><?= $i ?></a>
                                                </li>
                                            <?php
                                            }
                                        endfor;
                                        if ($page < $pages) {
                                            ?>
                                            <li class="page-item active">
                                                <a href="?data=<?= $page + 1 ?>" class="page-link">Next</a>
                                            </li>
                                        <?php
                                        } else {
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
        <!-- #Start Pop Up Detail -->
        <section class="popup-detail">
        </section>
        <!-- #END Pop Up Detail -->
    </div>
    </div>

    <script src="../../assets/js/all.min.js"></script>
    <script src="../../assets/js/layout.js"></script>
    <script>
        const keywordPetugas = document.querySelector('input[type="search"].input-search.petugas');
        const tbodyPetugas = document.querySelector('.tb_primary tbody');

        keywordPetugas.addEventListener('keypress', function() {
            // object ajax
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // console.log(xhr.responseText);
                    tbodyPetugas.innerHTML = xhr.responseText;
                }
            }

            xhr.open('GET', 'search_petugas.php?key=' + keywordPetugas.value, true);
            xhr.send();
        });
    </script>
</body>

</html>