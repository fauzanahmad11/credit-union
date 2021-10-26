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
$execute = null;

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM anggota WHERE nokta='$id'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];
$nickName = explode(" ",$username);

$nokta = $id;
$transaction = [
    $angsuranPokok = (isset($_GET['angsuranPokok']) ? "angsuran pokok"."-".$_GET['angsuranPokok'] : ''),
    $angsuranBunga = (isset($_GET['angsuranBunga']) ? "angsurana bunga"."-".$_GET['angsuranBunga'] : ''),
    $simpananSukarela = (isset($_GET['simpananSukarela']) ? "simpanan sukarela"."-".$_GET['simpananSukarela'] : ''),
    $simpananWajib = (isset($_GET['simpananWajib']) ? "simpanan wajib"."-".$_GET['simpananWajib'] : ''),
    $simpananPokok = (isset($_GET['simpananPokok']) ? "simpanan pokok"."-".$_GET['simpananPokok'] : ''),
    $simpananMasadepan = (isset($_GET['simpananMasadepan']) ? "simpanan masa depan"."-".$_GET['simpananMasadepan'] : '')
];

$total = 0;
foreach($transaction AS $baris){
    if(!isset($baris)){
        echo
        "<script>
            alert('do not edit url. Error !!');
            document.location.href = '../transaksi/add_transaksi_masuk.php';
        </script>";
        die;
    }
}

$angsuranPokok = (isset($_GET['angsuranPokok']) ? $_GET['angsuranPokok'] : '');
$angsuranBunga = (isset($_GET['angsuranBunga']) ? $_GET['angsuranBunga'] : '');
$simpananSukarela = (isset($_GET['simpananSukarela']) ? $_GET['simpananSukarela'] : '');
$simpananWajib = (isset($_GET['simpananWajib']) ? $_GET['simpananWajib'] : '');
$simpananPokok = (isset($_GET['simpananPokok']) ? $_GET['simpananPokok'] : '');
$simpananMasadepan = (isset($_GET['simpananMasadepan']) ? $_GET['simpananMasadepan'] : '');

$query = null;
$saldoKredit = 0;
// cek minimum harga
$hargaSiwajib       = $library->conn->query("SELECT*FROM masterharga WHERE nama='simpanan wajib'")->fetch(PDO::FETCH_ASSOC);
$hargaSipokok       = $library->conn->query("SELECT*FROM masterharga WHERE nama='simpanan pokok'")->fetch(PDO::FETCH_ASSOC);
$hargaSisukarela    = $library->conn->query("SELECT*FROM masterharga WHERE nama='simpanan sukarela'")->fetch(PDO::FETCH_ASSOC);
$hargaSimapan       = $library->conn->query("SELECT*FROM masterharga WHERE nama='simpanan masadepan'")->fetch(PDO::FETCH_ASSOC);
$hargaPinjaman      = $library->conn->query("SELECT*FROM pinjaman WHERE nokta='$nokta' AND `status`='aktif'")->fetch(PDO::FETCH_ASSOC);
$hargaSianggota     = $library->conn->query("SELECT*FROM sianggota WHERE nokta='$nokta' AND `status`='aktif'")->fetch(PDO::FETCH_ASSOC);

// cek saldo
// =======================SISUKARELA=======================
$sisukarela         = $library->conn->query("SELECT * FROM `sisukarela` WHERE nokta='$nokta' AND `status`='aktif' ORDER BY waktutransaksi DESC LIMIT 0,1");
$rowSisukarela      = $sisukarela->fetch(PDO::FETCH_ASSOC);
$saldoSisukarela    = null;
if(!empty($simpananSukarela)){
    $saldoSisukarela= $rowSisukarela['saldo']+$simpananSukarela;
}
// =======================PINJAMAN=======================
$pinjaman           = $library->conn->query("SELECT*FROM pinjaman WHERE nokta='$nokta' AND `status`='aktif'");
$rowPinjaman        = $pinjaman->fetch(PDO::FETCH_ASSOC);
// =======================ANGSURAN=======================
$angsuran           = $library->conn->query("SELECT*FROM angsuran WHERE nokta='$nokta' AND `status`='aktif' ORDER BY idangsuran ASC");
$rowAngsuran        = $angsuran->fetch(PDO::FETCH_ASSOC);
$totalBunga         = null;
$totalPokok         = null;
$totalSetor         = null;
$saldoKredit        = null;
$statusAngsuran     = "aktif";
// =======================REMINDER=======================
$reminder           = $library->conn->query("SELECT MIN(noangsuran) AS noangsuran FROM `reminderpinjaman` WHERE nokta='$nokta' AND status='aktif'");
$rowReminder        = $reminder->fetch(PDO::FETCH_ASSOC);

if(!empty($angsuranPokok) && !empty($angsuranBunga)){
    $totalBunga     = $rowAngsuran['totalbunga']+$angsuranBunga;
    $totalPokok     = $rowAngsuran['totalpokok']+$angsuranPokok;
    $totalSetor     = $angsuranBunga+$angsuranPokok;
    $saldoKredit    = $rowAngsuran['saldokredit']-$totalSetor;
}

// =======================SIWAJIB=======================
$siwajib            = $library->conn->query("SELECT SUM(subtotal) AS saldo FROM siwapo WHERE nokta='$nokta' AND `status`='aktif' AND keterangan='debit siwajib' ORDER BY idsiwapo ASC")->fetch(PDO::FETCH_ASSOC);
$saldoSiwajib       = null;
if(!empty($simpananWajib)){
    $saldoSiwajib   = $siwajib['saldo']+$simpananWajib;
}
// =======================SIPOKOK=======================
$sipokok            = $library->conn->query("SELECT SUM(subtotal) AS saldo FROM siwapo WHERE nokta='$nokta' AND `status`='aktif' AND keterangan='debit sipokok' ORDER BY idsiwapo ASC")->fetch(PDO::FETCH_ASSOC);
$saldoSipokok       = null;
if(!empty($simpananPokok)){
    $saldoSipokok   = $sipokok['saldo']+$simpananPokok;
}
// ====================SIMAPAN====================
$simapan            = $library->conn->query("SELECT MAX(nokartu) AS nomor, simapan.* FROM simapan WHERE nokta='$nokta'")->fetch(PDO::FETCH_ASSOC);


// kondisi transaksi angsuran
if((!empty($angsuranPokok) && empty($angsuranBunga)) || (empty($angsuranPokok) && !empty($angsuranBunga))){
    echo
    "<script>
        alert('angsuran pokok dan angsuran bunga wajib dibayar bersamaan');
        document.location.href='../transaksi/add_transaksi_masuk.php?noKta=$nokta';
    </script>";
    die;
}

if((!empty($angsuranPokok) && !empty($angsuranBunga)) && ($pinjaman->rowCount() < 1)){
    echo
    "<script>
        alert('anda tidak memiliki pinjaman yang aktif');
        document.location.href='../transaksi/add_transaksi_masuk.php?noKta=$nokta';
    </script>";
    die;
}

if(($pinjaman->rowCount() > 0) && (!empty($angsuranPokok) && !empty($angsuranBunga)) && (($angsuranPokok < $rowPinjaman['t_pokok']) || ($angsuranBunga < $rowPinjaman['t_bunga']))){
    echo
    "<script>
        alert('angsuran pokok anda minimal ".$control->rupiah($rowPinjaman['t_pokok'])." dan angsuran bunga ".$control->rupiah($rowPinjaman['t_pokok'])."');
        document.location.href='../transaksi/add_transaksi_masuk.php?noKta=$nokta';
    </script>";
    die;
}

if(($pinjaman->rowCount() > 0) && ($angsuran->rowCount() > 0) && (!empty($angsuranPokok) && !empty($angsuranBunga)) && ($saldoKredit < 0)){
    echo
    "<script>
        alert('saldo kredit anda sisa ".$control->rupiah($rowAngsuran['saldokredit'])." uang anda kebanyakan');
        document.location.href='../transaksi/add_transaksi_masuk.php?noKta=$nokta';
    </script>";
    die;
}

// kondisi siwajib
if((!empty($simpananWajib) && ($simpananWajib < $hargaSiwajib['min'])) 
|| (!empty($simpananWajib) && ($simpananWajib > $hargaSiwajib['max']))
|| (!empty($simpananWajib) && ($saldoSiwajib > $hargaSiwajib['max']))
){
    echo
    "<script>
        alert('simpanan wajib minimum transaksi ".$control->rupiah($hargaSiwajib['min'])." dan maximal ".$control->rupiah($hargaSiwajib['max'])."');
        document.location.href='../transaksi/add_transaksi_masuk.php?noKta=$nokta';
    </script>";
    die;
}

// kondisi sipokok
if((!empty($simpananPokok) && ($simpananPokok < $hargaSipokok['min']))
|| (!empty($simpananPokok) && ($simpananPokok > $hargaSipokok['max']))
|| (!empty($simpananPokok) && ($saldoSipokok > $hargaSipokok['max']))
){
    echo
    "<script>
        alert('simpanan pokok minimum transaksi ".$control->rupiah($hargaSipokok['min'])." dan maximal ".$control->rupiah($hargaSipokok['max'])."');
        document.location.href='../transaksi/add_transaksi_masuk.php?noKta=$nokta';
    </script>";
    die;
}

// kondisi sisukarela
if((!empty($simpananSukarela) && (($sisukarela->rowCount() < 1) && ($simpananSukarela < $hargaSisukarela['min']))) 
|| (!empty($simpananSukarela) && ($simpananSukarela > $hargaSisukarela['max']))
|| (!empty($simpananSukarela) && ($saldoSisukarela > $hargaSisukarela['max']))
){
    echo
    "<script>
        alert('pengguna baru simpanan sukarela minimum transaksi ".$control->rupiah($hargaSisukarela['min'])." dan maximal ".$control->rupiah($hargaSisukarela['max'])."');
        document.location.href='../transaksi/add_transaksi_masuk.php?noKta=$nokta';
    </script>";
    die;
}

// kondisi simapan
if((!empty($simpananMasadepan) && ($simpananMasadepan < $hargaSimapan['min']))
|| (!empty($simpananMasadepan) && ($simpananMasadepan > $hargaSimapan['max']))){
    echo
    "<script>
        alert('simpanan masa depan minimum transaksi ".$control->rupiah($hargaSimapan['min'])." dan maximal ".$control->rupiah($hargaSimapan['max'])."');
        document.location.href='../transaksi/add_transaksi_masuk.php?noKta=$nokta';
    </script>";
    die;
}

if(isset($_POST['pay'])){
    // $params = "";
    
    // foreach($_POST AS $val){
    //     if(!empty($val)){
    //         $data = explode("-",$val);
    //         $params .= "&{$data[0]}={$data[1]}";
    //     }
    // }
    
    // // var_dump($_POST); die;
    // echo
    // "<script>
    // alert('Go to midtrans');
    // document.location.href = '../payments/midtrans.php?noKta=$id$params';
    // </script>";
    $data = $_POST;
    $execute = $library->insertPayment($data);
}

if(isset($_POST['cancel'])){
    echo
    "<script>
    document.location.href = '../transaksi/add_transaksi_masuk.php';
    </script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="icon" href="../../../assets/img/icon/favicon2.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="../../../assets/css/layout.css">
    <link rel="stylesheet" href="../../../assets/css/formStyle.css">
    <link rel="stylesheet" href="../../../assets/css/all.min.css">
</head>

<body>
    <div class="shadow"></div>
    <div class="checkout container">
        <header>
            <div class="brand">
                <img src="../../../assets/img/icon/logo.svg" alt="">
            </div>
            <h2>Checkout</h2>
        </header>
        <form method="post">
            <div class="content">
                <div class="details">
                    <div class="head">
                        <h3>Transaction Detail</h3>
                    </div>
                    <div class="body">
                        <table>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>PROGRAM</th>
                                    <th>SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach($transaction AS $row):
                                if(!empty(explode("-",$row)[1])):
                                $total += explode("-",$row)[1];
                                $dataSet = "";
                                if(explode("-",$row)[0] == "angsuran pokok"){
                                    $dataSet='angsuranPokok';
                                }
                                if(explode("-",$row)[0] == "angsurana bunga"){
                                    $dataSet='angsuranBunga';
                                }
                                if(explode("-",$row)[0] == "simpanan sukarela"){
                                    $dataSet='simpananSukarela';
                                }
                                if(explode("-",$row)[0] == "simpanan wajib"){
                                    $dataSet='simpananWajib';
                                }
                                if(explode("-",$row)[0] == "simpanan pokok"){
                                    $dataSet='simpananPokok';
                                }
                                if(explode("-",$row)[0] == "simpanan masa depan"){
                                    $dataSet='simpananMasadepan';
                                }
                                ?>
                                <input type="hidden" name="value<?=$no?>" value="<?=$dataSet?>-<?=explode("-",$row)[1]?>">
                                <tr>
                                    <th>
                                        <center><?=$no++?>.</center>
                                    </th>
                                    <td><?=explode("-",$row)[0]?></td>
                                    <td><?=$control->rupiahSecound(explode("-",$row)[1])?></td>
                                </tr>
                                <?php
                                endif;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="price">
                    <div class="head">
                        <h3>Checkout Informations</h3>
                    </div>
                    <div class="body">
                        <table>
                            <tr>
                                <td>NOKTA</td>
                                <td><?=$id?></td>
                            </tr>
                                <input type="hidden" name="value<?=$no?>" value="total-<?=$total?>">
                            <tr>
                                <td>SUBTOTAL</td>
                                <td><?=$control->rupiahSecound($total)?></td>
                            </tr>
                            <tr>
                                <td>TOTAL</td>
                                <td class="total"><?=$control->rupiahSecound($total)?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="foot">
                        <h3>Payment Instructions</h3>
                        <p>You will be redirected to another page to pay using GO-PAY</p>
                        <ul class="payment-partner">
                            <li>
                                <img src="../../../assets/img/icon/gopay-icon.png" alt="">
                            </li>
                        </ul>
                        <button type="submit" name="pay">Process</button>
                        <button type="submit" name="cancel">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <footer class="checkout">
        <p>Allright reserve by <a href="http://instagram.com/zworld.id" target="_blank">Zworld</a> &copy; 2020</p>
    </footer>
    <script src="../../../assets/js/all.min.js"></script>
    <script src="../../../assets/js/format-input.js"></script>
    <script src="../../../assets/js/layout.js"></script>
</body>

</html>