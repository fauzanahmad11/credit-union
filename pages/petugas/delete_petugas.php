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
$id = $control->hashMethod('decrypt',$data);
$query = "";
$row = "";

if($idpetugas === $id){
    echo"
        <script>
            alert('Data online tidak dapat di hapus');
            document.location.href = 'data_petugas.php';
        </script>
    ";
    die;
}else{ 
    if($id !== false){
        $query = $library->conn->query("SELECT*FROM petugas WHERE idpetugas='$id'");
        $row = $query->fetch(PDO::FETCH_ASSOC);
        if($query->rowCount() === 0){
            echo"
                <script>
                    alert('data tidak valid');
                    document.location.href = 'data_petugas.php';
                </script>
            ";
            die;
        } else {
            $library->deletePetugas($id);
        }
    }else{
        echo"
            <script>
                alert('data tidak valid');
                document.location.href = 'data_petugas.php';
            </script>
        ";
        die;
    }
}