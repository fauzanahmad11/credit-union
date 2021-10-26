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

$key = $_GET['key'];
$id = $control->hashMethod('decrypt', $key);
$query = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$id'");
$row = $query->fetch(PDO::FETCH_ASSOC);
$tglLahir = date('Y',strtotime($row['tgllahir']));
$tahun = date('Y');
$umur = $tahun-$tglLahir;
?>


<div class="data-detail">
    <div class="container-detail">
        <div class="profile-image">
            <?php
            if($row['jenkel'] === 'perempuan' && $umur < 40){
                echo '<img src="../../assets/img/profile/user-profile-3.svg" alt="profile">';
            } 
            else if ($row['jenkel'] === 'perempuan' && $umur > 40){
                echo '<img src="../../assets/img/profile/user-profile-2.svg" alt="profile">';
            }
            else if($row['jenkel'] === 'laki-laki' && $umur < 40){
                echo '<img src="../../assets/img/profile/user-profile-5.svg" alt="profile">';
            } 
            else if ($row['jenkel'] === 'laki-laki' && $umur > 40){
                echo '<img src="../../assets/img/profile/user-profile-1.svg" alt="profile">';
            }
            
            ?>
            <h4 class="profile-id"><?= $row['noktp'] ?></h4>
        </div>
        <div class="profile-detail">
            <div class="head-detail">
                <h1><?= $row['nama'] ?> <span><?= $umur ?>th</span></h1>
            </div>
            <div class="body-detail">
                <table>
                    <tr>
                        <td><h5>Tanggal Lahir</h5></td>
                        <td><h5>:</h5></td>
                        <td><p><?= date("d-m-Y", strtotime($row['tgllahir'])) ?></p></td>
                    </tr>
                    <tr>
                        <td><h5>Tanggal Daftar</h5></td>
                        <td><h5>:</h5></td>
                        <td><p><?= date("d-m-Y", strtotime($row['waktudaftar'])) ?></p></td>
                    </tr>
                    <tr>
                        <td><h5>Status</h5></td>
                        <td><h5>:</h5></td>
                        <td><p><?php echo $row['status']; ?></p></td>
                    </tr>
                    <tr>
                        <td><h5>Jabatan</h5></td>
                        <td><h5>:</h5></td>
                        <td><p><?php echo $row['jabatan']; ?></p></td>
                    </tr>
                    <tr>
                        <td valign="top"><h5>Alamat</h5></td>
                        <td valign="top"><h5>:</h5></td>
                        <td><p><?php echo $row['alamat']; ?></p></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="btn-close">
            <i class="far fa-times-circle fa-2x"></i>
        </div>
    </div>
</div>