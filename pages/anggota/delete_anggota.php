<?php

require_once '../../assets/library/function.php';
require_once '../../assets/library/function_control.php';

session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../pages/loginpage/login.php");
}

$idpetugas = $_SESSION['key'];

// panggil class library() dan Control()
$library = new Library();
$control = new Control();

// Get url data parameter
$data = $_GET['data'];
// decrypt $data
$noKta = $control->hashMethod('decrypt',$data);
if($noKta !== false){
    $query = $library->conn->query("SELECT*FROM anggota WHERE nokta='$noKta'");
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if($query->rowCount() === 0){
        echo"
            <script>
                alert('data tidak valid');
                document.location.href = 'data_anggota.php';
            </script>
        ";
        die;
    } else {
        $library->deleteAnggota($noKta);
    }
}else{
    echo"
        <script>
            alert('data tidak valid');
            document.location.href = 'data_anggota.php';
        </script>
    ";
    die;
}