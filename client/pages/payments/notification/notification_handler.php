<?php
// namespace Midtrans;

require_once '../../../../assets/library/vendor/midtrans/midtrans-php/Midtrans.php';
require_once "../../../../assets/library/function.php";
require_once "../../../../assets/library/function_control.php";
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../../../pages/loginpage/login.php");
}

if(isset($_SESSION['user']) && $_SESSION['user'] == 'petugas'){
    header("Location: ../../../index.php");
}


NotificationHandler();

function NotificationHandler(){
    // $control = new Control();
    // $execute = null;
    $library = new Library();

    // Set your Merchant Server Key
    \Midtrans\Config::$serverKey = 'SB-Mid-server-ui9_UM7CT4irPeOCPd8z7rF8';
    // // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
    \Midtrans\Config::$isProduction = false;
    // // Set sanitization on (default)
    // \Midtrans\Config::$isSanitized = true;
    // // Set 3DS transaction for credit card to true
    // \Midtrans\Config::$is3ds = true;
    // Config::$isProduction = false;
    // Config::$serverKey = 'SB-Mid-server-ui9_UM7CT4irPeOCPd8z7rF8';
    $notif = new \Midtrans\Notification();


    $transaction = $notif->transaction_status;
    $type = $notif->payment_type;
    $order_id = $notif->order_id;
    $fraud = $notif->fraud_status;
    $status = null;
    if ($transaction == 'capture') {
        // For credit card transaction, we need to check whether transaction is challenge by FDS or not
        if ($type == 'credit_card') {
            if ($fraud == 'challenge') {
                // TODO set payment status in merchant's database to 'Challenge by FDS'
                // TODO merchant should decide whether this transaction is authorized or not in MAP
                $status = 'CHALLENGE';
            } else {
                // TODO set payment status in merchant's database to 'Success'
                $status = 'SUCCESS';
            }
        }
    } else if ($transaction == 'settlement') {
        // TODO set payment status in merchant's database to 'Settlement'
        $status = 'SUCCESS';
    } else if ($transaction == 'pending') {
        // TODO set payment status in merchant's database to 'Pending'
        $status = 'PENDING';
    } else if ($transaction == 'deny') {
        // TODO set payment status in merchant's database to 'Denied'
        $status = 'FAILED';
    } else if ($transaction == 'expire') {
        // TODO set payment status in merchant's database to 'expire'
        $status = 'EXPIRE';
    } else if ($transaction == 'cancel') {
        // TODO set payment status in merchant's database to 'Denied'
        $status = 'FAILED';
    } 
    
    $execute = $library->updatePaymentStatus($order_id,$status,$type);

    // if ($transaction == 'capture' && $fraud == 'challenge') {
    //     $json_data = [
    //         'meta'=>[
    //             'code'=>201,
    //             'message'=>'Midtrans Payment Challenge'
    //         ]
    //     ];
    //     $json = json_encode($json_data);

    //     return $json;
    // }else{
    //     $json_data = [
    //         'meta'=>[
    //             'code'=>202,
    //             'message'=>'Midtrans payement not settlement'
    //         ]
    //     ];
    //     $json = json_encode($json_data);

    //     return $json;
    // }

    // $json_data = [
    //     'meta'=>[
    //         'code'=>200,
    //         'message'=>'Midtrans notification success'
    //     ]
    // ];
    // $json = json_encode($json_data);

    // return $json;';

    return 'ok';
}
?>