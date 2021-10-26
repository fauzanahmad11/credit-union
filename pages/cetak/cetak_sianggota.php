
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once "../../assets/library/function.php";
require_once "../../assets/library/function_control.php";
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../loginpage/login.php");
}

$library = new Library();
$control = new Control();

$idpetugas = $_SESSION['key'];
$tanggal = date(' d-m-Y');

$nota = (isset($_GET['key']) ? $control->hashMethod("decrypt",$_GET['key']) : "");

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$idpetugas'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];

// cetak data tanpa clausa
$string = "SELECT*FROM `view-sianggota`  WHERE notransaksi='$nota'";

$query = $library->conn->query($string);
$row = $query->fetch(PDO::FETCH_ASSOC);

// cek apakah data ada 
if($query->rowCount() === 0){
    echo "
        <script>
            alert('data tidak ada');
            window.close();
        </script>
    ";
    die;
}

// print pdf
$mpdf = new \Mpdf\Mpdf();

// text
    $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Cetak</title>
        <!-- Favicon-->
        <link rel="icon" href="../../favicon.ico" type="image/x-icon">
        <style>
            table{
                border: 2px solid black;
            }
            table tr th center h1{
                padding-bottom:50px;
            }
            tr{
                height:30px;
            }
            table {
                margin: auto;
            }
            .t-a-r p{
                text-align: right;
                padding-left: 100px;
            }
            .b-b-s {
                border-bottom: 1px solid black;
            }
            .b-t-s {
                border-top: 1px solid black;
            }
            body {
                font-family: arial;
            }
            .b-s {
                border: 1px solid black;
                padding : 0 20px;
                margin-top: 30px;
            }
            table tr td {
                padding-bottom:15px;
                font-family:arial;
                font-size: 20px;
            }
        </style>
    </head>
    <body>
        <table border="0">
        <tr>
            <th width="200" rowspan="2"></th>
            <th width="500" rowspan="2"><center><h1>KOPERASI SIMPAN PINJAM <br>BKM SINDUADI</h1></center></th>
            <th colspan="2" width="400"><center><h1>SIMPANAN ANGGOTA</h1></center></th>
        </tr>
        <tr>
            <th colspan="2"></th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Terbilang</td>
            <td>: '.$control->rupiah($row['dana']).'</td>
            <td>NO.KTA</td>
            <td>: '.$row['nokta'].'</td>
        </tr>
        <tr>
            <td></td>
            <td>('.ucwords($control->terbilang($row['dana'])).' Rupiah)</td>
            <td>Tanggal Valuta</td>
            <td>: '.$row['tgl_masuk'].'</td>
        </tr>
        <tr>
            <td>Atas Nama</td>
            <td>: '.ucwords($row['nama']).'</td>
            <td>Tanggal Jatuh Tempo</td>
            <td>: '.$row['tgl_keluar'].'</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: '.ucwords($row['alamat']).'</td>
            <td>Jangka Waktu</td>
            <td>: '.$row['jangkawaktu'].'</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td colspan="2"><center>Yogyakarta, '.$row['tgl_masuk'].'</center></td>
        </tr>
        <tr>
            <td>Bagi Hasil</td>
            <td>: '.($row['bunga']*100).'% per Tahun</td>
            <td colspan="2"><center>KOPERASI SIMPAN PINJAM</center></td>
        </tr>
        <tr>
            <td>Jumlah B.H</td>
            <td>: '.$control->rupiah($row['totalbunga']).'</td>
            <td colspan="2"><center>BKM SINDUADI</center></td>
        </tr>
        <tr>
            <td></td>
            <td>('.ucwords($control->terbilang($row['totalbunga'])).' Rupiah)</td>
            <td><center>Ketua KSP</center></td>
            <td><center>Bendahara KSP</center></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><center>H. Sidekon, SE</center></td>
            <td><center>Kliwon Suhirman</center></td>
        </tr>
    </table>
    </body>
    </html>';

$mpdf->WriteHTML($html);
$mpdf->Output('TRD/.pdf','I');

?>
