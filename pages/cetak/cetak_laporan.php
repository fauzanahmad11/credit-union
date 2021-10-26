
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

if(isset($_SESSION['user']) && $_SESSION['user'] == 'anggota' && empty($_GET['nokta'])){
    echo"
    <script>
        window.close();
    </script>
    ";
    die;
}

// Select username 
$queryUsername = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$idpetugas'");
$rowQueryUsername = $queryUsername->fetch(PDO::FETCH_ASSOC);
$username = $rowQueryUsername['nama'];

$dateNow = date("Y-m-d");
// echo $dateNow;
// echo $tglSelesai;
// die;
if(($tglSelesai > $dateNow) || ($tglMulai > $dateNow)){
    echo "
        <script>
            alert('date over');
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
            font-size: .75em;
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
<body>';

// =========Sipokok=============
if(isset($_GET['key']) && $_GET['key'] == 'sipokok' && !isset($_GET['nokta'])){

    
    // cetak data tanpa clausa
    $string = "SELECT sum(subtotal) AS saldo,`view-siwapo`.* FROM `view-siwapo` WHERE keterangan LIKE '%sipokok%'";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
            $string .= " AND DATE(waktudaftar) >= '$tglMulai' AND DATE(waktudaftar) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " AND DATE(waktudaftar) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " AND DATE(waktudaftar) <= '$tglSelesai'";
        }
    }
    $string .= " GROUP BY nokta";

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

    $html .= '
    <header>
        <h3>SIMPANAN POKOK</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>NAMA</th>
            <th>SALDO</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html .= '
        <tr>
            <th>'.$no++.'</th>
            <td>'.$row["nokta"].'</td>
            <td>'.$row["nama"].'</td>
            <td>'.$control->rupiah($row["saldo"]).'</td>
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
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}
if(isset($_GET['key']) && $_GET['key'] == 'sipokok' && isset($_GET['nokta'])){

    
    // cetak data tanpa clausa
    $string = "SELECT*FROM `view-siwapo` WHERE keterangan LIKE '%sipokok%' AND nokta='".$_GET['nokta']."'";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " AND DATE(waktudaftar) >= '$tglMulai' AND DATE(waktudaftar) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " AND DATE(waktudaftar) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " AND DATE(waktudaftar) <= '$tglSelesai'";
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

    $html .= '
    <header>
        <h3>SIMPANAN POKOK</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>NAMA</th>
            <th>KETERANGAN</th>
            <th>SUBTOTAL</th>
            <th>TOTAL</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html .= '
        <tr>
            <th>'.$no++.'</th>
            <td>'.$row["nokta"].'</td>
            <td>'.$row["nama"].'</td>
            <td>'.ucwords($row["keterangan"]).'</td>
            <td>'.$control->rupiah($row["subtotal"]).'</td>
            <td>'.$control->rupiah($row["total"]).'</td>
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
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}

// =========Siwajib=============
if(isset($_GET['key']) && $_GET['key'] == 'siwajib' && !isset($_GET['nokta'])){

    
    // cetak data tanpa clausa
    $string = "SELECT sum(subtotal) AS saldo,`view-siwapo`.* FROM `view-siwapo` WHERE keterangan LIKE '%siwajib%'";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " AND DATE(waktudaftar) >= '$tglMulai' AND DATE(waktudaftar) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " AND DATE(waktudaftar) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " AND DATE(waktudaftar) <= '$tglSelesai'";
        }
    }
    $string .= " GROUP BY nokta";

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

    $html .= '
    <header>
        <h3>SIMPANAN WAJIB</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>NAMA</th>
            <th>SALDO</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html .= '
        <tr>
            <th>'.$no++.'</th>
            <td>'.$row["nokta"].'</td>
            <td>'.$row["nama"].'</td>
            <td>'.$control->rupiah($row["saldo"]).'</td>
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
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}
if(isset($_GET['key']) && $_GET['key'] == 'siwajib' && isset($_GET['nokta'])){

    
    // cetak data tanpa clausa
    $string = "SELECT*FROM `view-siwapo` WHERE keterangan LIKE '%siwajib%' AND nokta='".$_GET['nokta']."'";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " AND DATE(waktudaftar) >= '$tglMulai' AND DATE(waktudaftar) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " AND DATE(waktudaftar) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " AND DATE(waktudaftar) <= '$tglSelesai'";
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

    $html .= '
    <header>
        <h3>SIMPANAN WAJIB</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>NAMA</th>
            <th>KETERANGAN</th>
            <th>SUBTOTAL</th>
            <th>TOTAL</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html .= '
        <tr>
            <th>'.$no++.'</th>
            <td>'.$row["nokta"].'</td>
            <td>'.$row["nama"].'</td>
            <td>'.ucwords($row["keterangan"]).'</td>
            <td>'.$control->rupiah($row["subtotal"]).'</td>
            <td>'.$control->rupiah($row["total"]).'</td>
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
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}

// =========Sisukarela=============
if(isset($_GET['key']) && $_GET['key'] == 'sisukarela' && !isset($_GET['nokta'])){

    
    // cetak data tanpa clausa
    $string = "SELECT MAX(saldo) AS saldomax,`view-sisukarela`.* FROM `view-sisukarela`";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " AND DATE(waktutransaksi) >= '$tglMulai' AND DATE(waktutransaksi) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " AND DATE(waktutransaksi) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " AND DATE(waktutransaksi) <= '$tglSelesai'";
        }
    }

    $string .= " GROUP BY nokta";

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

    $html .= '
    <header>
        <h3>SIMPANAN SUKARELA</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>NAMA</th>
            <th>SALDO</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html .= '
        <tr>
            <th>'.$no++.'</th>
            <td>'.$row["nokta"].'</td>
            <td>'.$row["nama"].'</td>
            <td>'.$control->rupiah($row["saldomax"]).'</td>
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
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}
if(isset($_GET['key']) && $_GET['key'] == 'sisukarela' && isset($_GET['nokta'])){

    
    // cetak data tanpa clausa
    $string = "SELECT*FROM `view-sisukarela` WHERE nokta='".$_GET['nokta']."'";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " AND DATE(waktutransaksi) >= '$tglMulai' AND DATE(waktutransaksi) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " AND DATE(waktutransaksi) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " AND DATE(waktutransaksi) <= '$tglSelesai'";
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

    $html .= '
    <header>
        <h3>SIMPANAN SUKARELA</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>NAMA</th>
            <th>DEBIT</th>
            <th>KREDIT</th>
            <th>TOTAL</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html .= '
        <tr>
            <th>'.$no++.'</th>
            <td>'.$row["nokta"].'</td>
            <td>'.$row["nama"].'</td>
            <td>'.$control->rupiah($row["debit"]).'</td>
            <td>'.$control->rupiah($row["kredit"]).'</td>
            <td>'.$control->rupiah($row["saldo"]).'</td>
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
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}

// =========sianggota=============
if(isset($_GET['key']) && $_GET['key'] == 'sianggota' && !isset($_GET['nokta'])){
    // cetak data tanpa clausa
    $string = "SELECT*FROM `view-sianggota` ";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " WHERE DATE(waktutransaksi) >= '$tglMulai' AND DATE(waktutransaksi) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " WHERE DATE(waktutransaksi) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " WHERE DATE(waktutransaksi) <= '$tglSelesai'";
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

    $html .= '
    <header>
        <h3>SIMPANAN SIANGGOTA</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>ANGGOTA</th>
            <th>MASUK</th>
            <th>KELUAR</th>
            <th>SALDO</th>
            <th>BUNGA</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $saldo = $row['dana']+$row['totalbunga'];
    $html .= '
        <tr>
            <td>'.$no++.'</td>
            <td>'.$row['nokta'].'</td>
            <td>'.$row['nama'].'</td>
            <td>'.$row['tgl_masuk'].'</td>
            <td>'.$row['tgl_keluar'].'</td>
            <td>'.$control->rupiah($row['dana']).'</td>
            <td>'.$row['bunga']*100 .'%</td>
            <td>'.$control->rupiah($saldo).'</td>';
    $html .= '
        </tr>
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}
if(isset($_GET['key']) && $_GET['key'] == 'sianggota' && isset($_GET['nokta'])){
    // cetak data tanpa clausa
    $string = "SELECT*FROM `view-sianggota` WHERE nokta='{$_GET['nokta']}'";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " AND DATE(waktutransaksi) >= '$tglMulai' AND DATE(waktutransaksi) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " AND DATE(waktutransaksi) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " AND DATE(waktutransaksi) <= '$tglSelesai'";
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

    $html .= '
    <header>
        <h3>SIMPANAN SIANGGOTA</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>ANGGOTA</th>
            <th>MASUK</th>
            <th>KELUAR</th>
            <th>SALDO</th>
            <th>BUNGA</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $saldo = $row['dana']+$row['totalbunga'];
    $html .= '
        <tr>
            <td>'.$no++.'</td>
            <td>'.$row['nokta'].'</td>
            <td>'.$row['nama'].'</td>
            <td>'.$row['tgl_masuk'].'</td>
            <td>'.$row['tgl_keluar'].'</td>
            <td>'.$control->rupiah($row['dana']).'</td>
            <td>'.$row['bunga']*100 .'%</td>
            <td>'.$control->rupiah($saldo).'</td>';
    $html .= '
        </tr>
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}

// =========pinjaman=============
if(isset($_GET['key']) && $_GET['key'] == 'pinjaman' && !isset($_GET['nokta'])){
    // cetak data tanpa clausa
    $string = "SELECT*FROM `view-pinjaman` ";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " WHERE DATE(waktutransaksi) >= '$tglMulai' AND DATE(waktutransaksi) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " WHERE DATE(waktutransaksi) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " WHERE DATE(waktutransaksi) <= '$tglSelesai'";
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

    $html .= '
    <header>
        <h3>PINJAMAN</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>ANGGOTA</th>
            <th>MULAI</th>
            <th>SELESAi</th>
            <th>DANA</th>
            <th>POKOK</th>
            <th>BUNGA</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $saldo = $row['dana']+$row['totalbunga'];
    $html .= '
        <tr>
            <td>'.$no++.'</td>
            <td>'.$row['nokta'].'</td>
            <td>'.explode(" ",$row['nama'])[0].'</td>
            <td>'.$row['tgl_mulai_a'].'</td>
            <td>'.$row['tgl_selesai_a'].'</td>
            <td>'.$control->rupiah($row['totalpinjam']).'</td>
            <td>'.$control->rupiah($row['t_pokok']).'</td>
            <td>'.$control->rupiah($row['t_bunga']).'</td>
            <td>'.$control->rupiah($row['jumlah_setor']).'</td>';
    $html .= '
        </tr>
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}
if(isset($_GET['key']) && $_GET['key'] == 'pinjaman' && isset($_GET['nokta'])){
    // cetak data tanpa clausa
    $string = "SELECT*FROM `view-pinjaman` WHERE nokta='{$_GET['nokta']}'";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " AND DATE(waktutransaksi) >= '$tglMulai' AND DATE(waktutransaksi) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " AND DATE(waktutransaksi) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " AND DATE(waktutransaksi) <= '$tglSelesai'";
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

    $html .= '
    <header>
        <h3>PINJAMAN</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>ANGGOTA</th>
            <th>MULAI</th>
            <th>SELESAi</th>
            <th>DANA</th>
            <th>POKOK</th>
            <th>BUNGA</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $saldo = $row['dana']+$row['totalbunga'];
    $html .= '
        <tr>
            <td>'.$no++.'</td>
            <td>'.$row['nokta'].'</td>
            <td>'.explode(" ",$row['nama'])[0].'</td>
            <td>'.$row['tgl_mulai_a'].'</td>
            <td>'.$row['tgl_selesai_a'].'</td>
            <td>'.$control->rupiah($row['totalpinjam']).'</td>
            <td>'.$control->rupiah($row['t_pokok']).'</td>
            <td>'.$control->rupiah($row['t_bunga']).'</td>
            <td>'.$control->rupiah($row['jumlah_setor']).'</td>';
    $html .= '
        </tr>
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}

// =========simapan=============
if(isset($_GET['key']) && $_GET['key'] == 'simapan' && !isset($_GET['nokta'])){
    // cetak data tanpa clausa
    $string = "SELECT SUM(nilai) AS saldo, COUNT(idsimapan) AS jumkartu,`view-simapan`.* FROM `view-simapan`";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " WHERE DATE(waktutransaksi) >= '$tglMulai' AND DATE(waktutransaksi) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " WHERE DATE(waktutransaksi) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " WHERE DATE(waktutransaksi) <= '$tglSelesai'";
        }
    }

    $string .= " GROUP BY `nokta`";

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

    $html .= '
    <header>
        <h3>SIMPANAN MASADEPAN</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>NAMA</th>
            <th>NILAI</th>
            <th>BELI</th>
            <th>JUAL</th>")
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html .= '
        <tr>
            <td>'.$no++.'</td>
            <td>'.$row['nokta'].'</td>
            <td>'.$row['nama'].'</td>
            <td>'.$control->rupiah($row['saldo']).'</td>
            <td>'.date("d-m-Y",strtotime($row['waktutransaksi'])).'</td>
            <td>'.($row['waktutransaksijual'] != "0000-00-00 00:00:00" ? date("d-m-Y",strtotime($row['waktutransaksijual'])) : "--").'</td>
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
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}
if(isset($_GET['key']) && $_GET['key'] == 'simapan' && isset($_GET['nokta'])){
    // cetak data tanpa clausa
    $string = "SELECT * FROM `view-simapan` WHERE `nokta`='{$_GET['nokta']}' ";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " AND DATE(waktutransaksi) >= '$tglMulai' AND DATE(waktutransaksi) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " AND DATE(waktutransaksi) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " AND DATE(waktutransaksi) <= '$tglSelesai'";
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

    $html .= '
    <header>
        <h3>SIMPANAN MASADEPAN</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>NAMA</th>
            <th>NILAI</th>
            <th>BELI</th>
            <th>JUAL</th>")
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html .= '
        <tr>
            <td>'.$no++.'</td>
            <td>'.$row['nokta'].'</td>
            <td>'.$row['nama'].'</td>
            <td>'.$control->rupiah($row['nilai']).'</td>
            <td>'.date("d-m-Y",strtotime($row['waktutransaksi'])).'</td>
            <td>'.($row['waktutransaksijual'] != "0000-00-00 00:00:00" ? date("d-m-Y",strtotime($row['waktutransaksijual'])) : "--").'</td>
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
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}

// =========angsuran=============
if(isset($_GET['key']) && $_GET['key'] == 'angsuran' && !isset($_GET['nokta'])){
    // cetak data tanpa clausa
    $string = "SELECT*FROM `view-angsuran`";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " WHERE DATE(waktutransaksi) >= '$tglMulai' AND DATE(waktutransaksi) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " WHERE DATE(waktutransaksi) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " WHERE DATE(waktutransaksi) <= '$tglSelesai'";
        }
    }

    $string .= " GROUP BY `nokta`";

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

    $html .= '
    <header>
        <h3>ANGSURAN PINJAMAN</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>STRUK</th>
            <th>NO KTA</th>
            <th>ANGGOTA</th>
            <th>SALDO</th>
            <th>STATUS</th>
            <th>WAKTU</th>
            <th>AKSI</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html .= '
        <tr>
            <td>'.$no++.'</td>
            <td>'.$row['notransaksi'].'</td>
            <td>'.$row['nokta'].'</td>
            <td>'.explode(" ",$row['nama'])[0].'</td>
            <td>'.$control->rupiah($row['saldokredit']).'</td>
            <td>'.$row['status'].'</td>
            <td>'.date("d-m-Y",strtotime($row['waktutransaksi'])).'</td>
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
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}
if(isset($_GET['key']) && $_GET['key'] == 'angsuran' && isset($_GET['nokta'])){
    // cetak data tanpa clausa
    $string = "SELECT*FROM `view-angsuran` WHERE nokta='".$_GET['nokta']."'";
    // cetak dengan klausa
    if(!empty($tglMulai) && !empty($tglSelesai)){
        $string .= " AND DATE(waktutransaksi) >= '$tglMulai' AND DATE(waktutransaksi) <= '$tglSelesai'";
    } else{
        if(!empty($tglMulai)){
            $string .= " AND DATE(waktutransaksi) >= '$tglMulai'";
        }

        if(!empty($tglSelesai)){
            $string .= " AND DATE(waktutransaksi) <= '$tglSelesai'";
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

    $html .= '
    <header>
        <h3>ANGSURAN PINJAMAN</h3>
        <h3>Koperasi Simpan Pinjam BKM Sinduadi</h3>
        <p>No. B.H : 129/BH/XV.4/KAB.SLM/VI/2015<br>Kantor : Jl. Magelang Km. 4,5 Sinduadi Mlati Sleman<br>Balai Desa Sinduadi Lt. II<br>No Tlp : 085100366864</p>
    
    </header>
    <table class="table-data">
    <thead>
        <tr>
            <th>NO</th>
            <th>NO KTA</th>
            <th>ANGGOTA</th>
            <th>BUNGA</th>
            <th>TOTAL BUNGA</th>
            <th>POKOK</th>
            <th>TOTAL POKOK</th>
            <th>SALDO</th>
            <th>WAKTU</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
    ';
    $no = 1;
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html .= '
        <tr>
            <td>'.$no++.'</td>
            <td>'.$row['nokta'].'</td>
            <td>'.explode(" ",$row['nama'])[0].'</td>
            <td>'.$control->rupiah($row['angsuranbunga']).'</td>
            <td>'.$control->rupiah($row['totalbunga']).'</td>
            <td>'.$control->rupiah($row['angsuranpokok']).'</td>
            <td>'.$control->rupiah($row['totalpokok']).'</td>
            <td>'.$control->rupiah($row['saldokredit']).'</td>
            <td>'.date("d-m-Y",strtotime($row['waktutransaksi'])).'</td>
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
    ';
    }
        
    $html .= '
    </tbody>
    </table>
    <footer>
    <div class="signature">
    <div class="head">
        <h5>Sunduadi, '.$dateNow.'</h5>
        <h5>Petugas</h5>
    </div>
    <div class="foot">
        <h5>'.$username.'</h5>
    </div>
    </div>
    </footer>';
}

$html .= '
</body>
</html>
';

$mpdf->WriteHTML($html);
$mpdf->Output('TRD/.pdf','I');

?>
