
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

$nota = $control->hashMethod("decrypt",$_GET['key']);
$totalCairSianggota = null;
// Select username 
$queryUsername = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$idpetugas'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];

$dataTransaksi  = $library->conn->query("SELECT*FROM `view-transaksi` WHERE notransaksi='$nota'");
$dataUser  = $library->conn->query("SELECT*FROM `view-transaksi` WHERE notransaksi='$nota'")->fetch(PDO::FETCH_ASSOC);
$totalTransaksi = $library->conn->query("SELECT SUM(total) AS total FROM `riwayattransaksi` WHERE notransaksi='$nota' GROUP BY nokta")->fetch(PDO::FETCH_ASSOC);

// cek apakah data ada 
if($dataTransaksi->rowCount() === 0){
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
    <style>
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
    </style>
</head>
<body>
    <table border="0">
        <tr>
        <th colspan="5" ><center><h1>KOPERASI SIMPAN PINJAM</h3></center></th>
        </tr>
        <tr>
        <th colspan="5" ><center><h1>"BKM SINDUADI"</h1></center></th>
        </tr>
        <tr>
        <td colspan="5" ><center><h4>No. B.H: 129/BH/XV.4/KAB.SLM/VI/2015</h6></center></td>
        </tr>
        <tr>
        <td colspan="5" ><center><h4>Jl. Magelang Km. 4,5 Sinduadi, Mlati, Sleman Telp. 085100366864</h4></center></td>
        </tr>
        <tr>
            <th class="b-t-s" colspan="5" width="500" height="150"><center><h1>KWITANSI KELUAR</h1></center></th>
        </tr>
        <tr>
            <td width="150">No.KTA</td>
            <td width="300">: '.$dataUser['nokta'].'</td>
            <td width="100" colspan="3" align="right"><p>No.Bukti '.$dataUser['notransaksi'].'</p></td>
        </tr>
        <tr>
            <td>Dibayarkan Kepada</td>
            <td width="100" colspan="4">: '.ucwords($dataUser['namaanggota']).'</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td colspan="4">: '.ucwords($dataUser['alamat']).'</td>
        </tr>
        <tr>
            <td colspan="5">Untuk Pembayaran</td>
        </tr>';
        while($row = $dataTransaksi->fetch(PDO::FETCH_ASSOC)):
        if($row['namatransaksi'] == "sipokok"){
            $html .= '
            <tr>
            <td></td>
            <td width="200">- Simpanan Pokok</td>
            <td colspan="3">'.$control->rupiah($row['total']).'</td>
            </tr>
            ';
        }
        if($row['namatransaksi'] == "siwajib"){
            $html .= '
            <tr>
            <td></td>
            <td width="200">- Simpanan Wajib</td>
            <td colspan="3">'.$control->rupiah($row['total']).'</td>
            </tr>
            ';
        }
        if($row['namatransaksi'] == "sisukarela"){
            $html .= '
            <tr>
            <td></td>
            <td width="200">- Simpanan Sukarela</td>
            <td colspan="3">'.$control->rupiah($row['total']).'</td>
            </tr>
            ';
        }
        if($row['namatransaksi'] == "beli simapan"){
            $html .= '
            <tr>
            <td></td>
            <td width="200">- Simpanan Masa Depan</td>
            <td colspan="3">'.$control->rupiah($row['total']).'</td>
            </tr>
            ';
        }
        if ($row['namatransaksi'] == "pencairan sianggota") {
            $getDataSianggotaFirst = $library->conn->query("SELECT*FROM sianggota WHERE notransaksi='$nota'")->fetch(PDO::FETCH_ASSOC);
            $getTanggalKeluar = $getDataSianggotaFirst['tgl_keluar'];
            $getNoktaSianggota = $getDataSianggotaFirst['nokta'];
            $getSianggotaParent = $library->conn->query("SELECT*FROM sianggota WHERE nokta='$getNoktaSianggota' AND tgl_keluar='$getTanggalKeluar'")->fetch(PDO::FETCH_ASSOC);
            $totalCairSianggota = $getSianggotaParent['dana'] + $getSianggotaParent['totalbunga'];
            $html .= '
                <tr>
                <td></td>
                <td width="200">- Simpanan Berjangka</td>
                <td colspan="3">' . $control->rupiah($totalCairSianggota) . '</td>
                </tr>
                ';
        }
    endwhile;
        $html .= '
        <tr>
        <td></td>
        <td align="right"><b>Jumlah</b></td>
        <td colspan="3"><b class="b-b-s">'.$control->rupiah($totalTransaksi['total']+$totalCairSianggota).'</b></td>
        </tr>
        ';

        $html .= '
        <tr>
        <td colspan="5" height="20"></td>
        </tr>
        ';

        $html .= '
        <tr>
        <td colspan="5" class="b-s" height="50"><b>Terbilang : </b> '. ucwords($control->terbilang($totalTransaksi['total']+$totalCairSianggota)) .' Rupiah</td>
        </tr>
        ';

        $html .='
        <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td height="50" width="230" align="right"><p>Sinduadi, '.date('d/m/Y',strtotime($dataUser['waktu'])).'</p></td>
        </tr>
        ';

        $html .='
        <tr>
        <td></td>
        <td></td>
        <td><center>Penyetor</center></td>
        <td></td>
        <td align="right"><center>Penerima</center></td>
        </tr>
        ';

        $html .= '
        <tr>
        <td></td>
        <td></td>
        <td height="70"><center>(.....................................)</center></tdh>
        <td></td>
        <td height="70" align="right"><center>(.....................................)</center></td>
        </tr>
        ';

$html .= '
</table>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output('TRD/.pdf','I');

?>
