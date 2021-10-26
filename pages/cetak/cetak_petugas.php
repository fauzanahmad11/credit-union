
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once "../../assets/library/function.php";
require_once "../../assets/library/function_control.php";
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../loginpage/login.php");
}

$tglMulai = $_GET['start'];
$tglSelesai = $_GET['end'];
$library = new Library();
$control = new Control();

$idpetugas = $_SESSION['key'];

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$idpetugas'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];

// cetak data tanpa clausa
$string = "SELECT*FROM petugas";
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

$query = $library->conn->query($string);

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
    <title>Document</title>
    <style>
    @page{
        padding: 0;
    }
        *, header,h3,p,h4,h5,h6{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        header{
            text-align: center;
            border-bottom: 2px solid black;
            margin-bottom: 20px;
            padding: 20px;
            color: #333;
        }
        header h3{
            text-transform: Uppercase;
            font-size: .9em;
        }
        header p{
            font-size: .8em;
        }
        table.table-data{
            border: collapse;
            width: 100%;
            font-family: arial;
            border-collapse: collapse;
        }
        table.table-data thead tr{
            background-color: #61CC7E;
        }

        table.table-data td, table.table-data th{
            padding: 5px 10px;
            border: 0.2px solid #333;
            text-transform: Capitalize;
        }
        table.table-data th{
            text-transform: Uppercase;
        }
        
        .signature{
            text-align: right; 
            padding-top: 30px;
        }

        .signature .foot{
            margin-top: 40px;
            font-size: .9em;
            text-transform: Capitalize;
        }
        .signature .head{
            font-size: .9em;
        }
    </style>
</head>
<body>
<header>
    <h3>Daftar Petugas</h3>
    <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
    <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>

</header>
<table class="table-data">
<thead>
    <tr>
        <th>NO</th>
        <th>NO KTP</th>
        <th>NAMA</th>
        <th>GENDER</th>
        <th>ALAMAT</th>
        <th>JABATAN</th>
        <th>STATUS</th>
    </tr>
</thead>
';
$no = 1;
while($row = $query->fetch(PDO::FETCH_ASSOC)){
if($row['noktp'] !== '7401072407980001'):
$html .= '
<tbody>
    <tr>
        <th>'.$no++.'</th>
        <td>'.$row["noktp"].'</td>
        <td>'.$row["nama"].'</td>
        <td>'.$row["jenkel"].'</td>
        <td>'.$row["alamat"].'</td>
        <td>'.$row["jabatan"].'</td>
        <td>
            <center>';
            if($row["status"] === "aktif"){
                $html .= '<h2 class="status">&#10004;</h2>';
            }else{
                $html .= '<h2 class="status">&#215;</h2>';
            }
$html .= '
            </center>
        </td>
    </tr>
</tbody>
';
endif;
}
    
$html .= '
</table>
<footer>
<div class="signature">
<div class="head">
    <h5>Sunduadi, 08 mei 2020</h5>
    <h5>Petugas</h5>
</div>
<div class="foot">
    <h5>'.$username.'</h5>
</div>
</div>
</footer>
</body>
</html>
';

$mpdf->WriteHTML($html);
$mpdf->Output('TRD/.pdf','I');

?>
