<?php
require_once "function.php";
require_once "function_control.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: pages/loginpage/login.php");
}

$library = new Library();
$control = new Control();
$idpetugas = $_SESSION['key'];

$program = htmlspecialchars(strtolower($_GET['program']));
$nokta = htmlspecialchars($_GET['nokta']);
$nokta = $control->hashMethod("decrypt", $nokta);
$nota = htmlspecialchars($_GET['nota']);
$id = htmlspecialchars($_GET['q']);
$noangsuran = ($program == 'angsuran' ? htmlspecialchars($_GET['noangsuran']) : null);
$pesan = null;
$noHp = null;
$gender = null;

// Select Data Anggota
$query = $library->conn->query("SELECT*FROM anggota WHERE nokta='$nokta'");
$row = $query->fetch(PDO::FETCH_ASSOC);

// add nama petugas
$petugas = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$idpetugas'")->fetch(PDO::FETCH_ASSOC);
$namaPetugas = explode(' ', $petugas['nama'])[0];

if ($query->rowCount() < 1) {
    echo "
    <script>
    alert('NO KTA tidak di temukan');
    window.history.back();
    </script>
    ";
    die;
}

// Select Data Angsuran
$angsuran = $library->conn->query("SELECT *, DATE(waktuangsuran) AS tanggalAngsuran FROM reminderpinjaman WHERE idreminderpinjaman=$id")->fetch(PDO::FETCH_ASSOC);

if ($row['jenkel'] == 'laki-laki') {
    $gender = 'Bapak';
} else {
    $gender = 'Ibu';
}

if ($program == "angsuran") {
    $pinjaman = $library->conn->query("SELECT*FROM pinjaman WHERE notransaksi=$nota")->fetch(PDO::FETCH_ASSOC);

    $noHp = $row['notelepon'];
    $waktuAngsuran = $control->tanggalFormat($angsuran['tanggalAngsuran']);
    $waktuAngsuranWa = date("d M Y", strtotime($angsuran['waktuangsuran']));
    $jumlahSetorWa = $control->rupiahSecound($pinjaman['jumlah_setor']);
    $jumlahSetor = $control->terbilang($pinjaman['jumlah_setor']);
    $pesan = "
    Pelanggan yth. $gender " . ucwords($row['nama']) . ", jatuh tempo angsuran pinjaman anda no $nota  di Koperasi BKM Sinduadi pada tanggal $waktuAngsuran sebesar $jumlahSetor. Silahkan membayar angsuran pinjaman anda sebelum tanggal jatuh tempo berakhir.
    ~Admin-$namaPetugas~ 
    ";
    $pesanWa = "
    Pelanggan yth. $gender " . ucwords($row['nama']) . ", jatuh tempo angsuran pinjaman anda no $nota  di Koperasi BKM Sinduadi pada tanggal $waktuAngsuranWa sebesar $jumlahSetorWa. Silahkan membayar angsuran pinjaman anda sebelum tanggal jatuh tempo berakhir.
    ~Admin-$namaPetugas~ 
    ";
    $library->sendMessage($noHp, $pesan);
    $library->sendMessageWa($noHp, $pesan);
}

if ($program == "sianggota") {
    $sianggota = $library->conn->query("SELECT*FROM sianggota WHERE notransaksi=$nota")->fetch(PDO::FETCH_ASSOC);

    $noHp = $row['notelepon'];
    $jatuhTempo = date("d M Y", strtotime($sianggota['tgl_keluar']));
    $totalDeposit = $control->rupiahSecound($sianggota['dana'] + $sianggota['totalbunga']);
    $pesan = "
    Pelanggan yth. $gender " . ucwords($row['nama']) . ", jatuh tempo Simpanan Anggota anda no $nota  di Koperasi BKM Sinduadi telah dapat dicairkan pada tanggal $jatuhTempo sebesar $totalDeposit. 
    ~Admin-$namaPetugas~ 
    ";
    $library->sendMessage($noHp, $pesan);
}
