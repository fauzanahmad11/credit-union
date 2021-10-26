<?php
require_once '../../assets/library/function.php';
require_once '../../assets/library/function_control.php';

$keyword = $_POST['key'];
$library = new Library();
$control = new Control();
$messages = array();
$nokta = null;
$status = true;

$query = $library->conn->query("SELECT*FROM anggota WHERE nokta='$keyword' AND `status`='aktif'");
$row = $query->fetch(PDO::FETCH_ASSOC);
if($query->rowCount() === 1){
    $messages[] = "";
    $nokta = $keyword;
}else{
    $status = false;
    $messages[] = "unavailable";
}

echo json_encode(
    array(
        'status' => $status,
        'message' => $messages,
        'nokta' => $nokta
    )
);