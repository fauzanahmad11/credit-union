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

$query = null;
$string = "INSERT INTO payment VALUES ";
$total = 0;
$nokta = $_SESSION['key'];


$library->updatePaymentStatus("18072000001","SUCCESS","gopay");

die;
// ====================SELECT PETUGAS====================
$queryPetugas = $library->conn->query("SELECT * FROM petugas LIMIT 0,1")->fetch(PDO::FETCH_ASSOC);
// ====================SELECT ANGGOTA====================
$queryAnggota = $library->conn->query("SELECT * FROM anggota WHERE nokta='$nokta'")->fetch(PDO::FETCH_ASSOC);
// ====================SELECT TRANSAKSI SELECT TRANSAKSI====================
$queryPayment = "SELECT * FROM payment WHERE notransaksi='18072000001'";


$change = $library->conn->query($queryPayment);
$rows = [];
while($row = $change->fetch(PDO::FETCH_ASSOC)){
    $rows[] = $row;
}

$angsuranPokok = null;
$angsuranBunga = null;
$simpananSukarela = null;
$simpananWajib = null;
$simpananPokok = null;
$simpananMasadepan = null;
$nota = null;
$nokta = null;
$idpetugas = null;

foreach($rows AS $key){
    if($key['namatransaksi'] == "angsuranPokok"){
        $angsuranPokok = $key['total'];
    }
    if($key['namatransaksi'] == "angsuranBunga"){
        $angsuranBunga = $key['total'];
    }
    if($key['namatransaksi'] == "simpananSukarela"){
        $simpananSukarela = $key['total'];
    }
    if($key['namatransaksi'] == "simpananWajib"){
        $simpananWajib = $key['total'];
    }
    if($key['namatransaksi'] == "simpananPokok"){
        $simpananPokok = $key['total'];
    }
    if($key['namatransaksi'] == "simpananMasadepan"){
        $simpananMasadepan = $key['total'];
    }
    $nota = $key['notransaksi'];
    $nokta = $key['nokta'];
    $idpetugas = $key['idpetugas'];
}


echo $angsuranPokok."<br>".
    $angsuranBunga."<br>".
    $simpananSukarela."<br>".
    $simpananWajib."<br>".
    $simpananPokok."<br>".
    $simpananMasadepan."<br>".
    $nota."<br>".
    $nokta."<br>".
    $idpetugas."<br>"
    ;
die;
$library->insertTransaksiDebitPayment($nota, $idpetugas, $nokta, $angsuranPokok, $angsuranBunga, $simpananSukarela, $simpananWajib, $simpananPokok, $simpananMasadepan);
var_dump($rows);
foreach($_POST AS $val){
    $data = explode("-",$val);
    if(!empty($val) && $data[0] != 'total'){
        // $angsuranPokok .= ($data[0] == 'angsuranPokok' ? $data[1] : '');
        // $angsuranBunga .= ($data[0] == 'angsuranBunga' ? $data[1] : '');
        // $simpananSukarela .= ($data[0] == 'simpananSukarela' ? $data[1] : '');
        // $simpananWajib .= ($data[0] == 'simpananWajib' ? $data[1] : '');
        // $simpananPokok .= ($data[0] == 'simpananPokok' ? $data[1] : '');
        // $simpananMasadepan .= ($data[0] == 'simpananMasadepan' ? $data[1] : '');
        $string .="('','$nokta','{$queryPetugas['idpetugas']}','$nota','{$data[0]}','{$data[1]}',NOW(),'PROCESS',''),";
    }
    if($data[0] == 'total'){
        $total = $data[1];
    }
}

$string = rtrim($string, ', ');