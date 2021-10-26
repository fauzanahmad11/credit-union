
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once "../../assets/library/function.php";
require_once "../../assets/library/function_control.php";
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../loginpage/login.php");
}

$tglMulai = (isset($_GET['start']) ? $_GET['start'] : "");
$tglSelesai = (isset($_GET['end']) ? $_GET['end'] : "");
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
$string = "SELECT*FROM `view-pinjaman`";
// cetak dengan klausa
if(!empty($tglMulai) && !empty($tglSelesai)){
    $string .= " WHERE DATE(waktudaftar) >= '$tglMulai' AND DATE(waktudaftar) <= '$tglSelesai'";
} else{
    if(!empty($tglMulai)){
        $string .= " WHERE DATE(waktudaftar) >= '$tglMulai'";
    }

    if(!empty($tglSelesai)){
        $string .= " WHERE DATE(waktudaftar) <= '$tglSelesai'";
    }
}

if (isset($_GET['key'])) {
    $string .= " WHERE notransaksi='$nota'";
}
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
            <th class="b-t-s" colspan="5" width="500" height="150"><center><h1>KWITANSI PINJAMAN</h1></center></th>
        </tr>
        <tr>
            <td width="150">No.KTA</td>
            <td width="300">: '.$row['nokta'].'</td>
            <td width="100" colspan="3" align="right"><p>No.Bukti '.$nota.'</p></td>
        </tr>
        <tr>
            <td>Pinjaman A.N</td>
            <td width="100" colspan="4">: '.ucwords($row['nama']).'</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td colspan="4">: '.ucwords($row['alamat']).'</td>
        </tr>
        <tr>
            <td>Total Pinjam</td>
            <td colspan="4">: '.$control->rupiah($row['totalpinjam']).'</td>
        </tr>
        <tr>
            <td>Rencana Penggunaan</td>
            <td colspan="4">: '.ucwords($row['keterangan']).'</td>
        </tr>
        <tr>
            <td>Tanggal Mulai</td>
            <td colspan="4">: '.$row['tgl_mulai_a'].'</td>
        </tr>
        <tr>
            <td>Tanggal Selesai</td>
            <td colspan="4">: '.$row['tgl_selesai_a'].'</td>
        </tr>
        <tr>
            <td colspan="5">Detail Pinjaman</td>
        </tr>';

            $html .= '
            <tr>
            <td></td>
            <td>- Angsuran Pokok</td>
            <td colspan="3">'.$control->rupiah($row['t_pokok']).'</td>
            </tr>
            ';

            $html .= '
            <tr>
            <td></td>
            <td width="200">- Angsuran Bunga</td>
            <td colspan="3">'.$control->rupiah($row['t_bunga']).'</td>
            </tr>
            ';
        
        $html .= '
        <tr>
        <td></td>
        <td align="right"><b>Total Angsuran Bulanan</b></td>
        <td colspan="3"><b class="b-b-s">'.$control->rupiah($row['jumlah_setor']).'</b></td>
        </tr>
        ';

        $html .= '
        <tr>
        <td colspan="5" height="20"></td>
        </tr>
        ';

        $html .= '
        <tr>
        <td colspan="5" class="b-s" height="50"><b>Terbilang : </b> '. ucwords($control->terbilang($row['jumlah_setor'])) .' Rupiah</td>
        </tr>
        ';

        $html .='
        <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td height="50" width="230" align="right"><p>Sinduadi,'.$tanggal.'</p></td>
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
