<?php

require_once '../../../assets/library/vendor/autoload.php';
require_once "../../../assets/library/function.php";
require_once "../../../assets/library/function_control.php";
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../../pages/loginpage/login.php");
}

if(isset($_SESSION['user']) && $_SESSION['user'] == 'petugas'){
    header("Location: ../../index.php");
}

$library = new Library();
$control = new Control();
$id = $_SESSION['key'];

$nota = $control->hashMethod('decrypt',htmlspecialchars($_GET['id']));
$firstname = $control->hashMethod('decrypt',htmlspecialchars($_GET['name']));
$total = $control->hashMethod('decrypt',htmlspecialchars($_GET['value']));

// SELECT PAYMENTS
$queryPayment = $library->conn->query("SELECT*FROM payment WHERE notransaksi='$nota'");
$rowPayment = $queryPayment->fetch(PDO::FETCH_ASSOC);
$nokta = $rowPayment['nokta'];
$rowAnggota = $library->conn->query("SELECT*FROM anggota WHERE nokta='$nokta'")->fetch(PDO::FETCH_ASSOC);
$noTelepon = $rowAnggota['notelepon'];

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-ui9_UM7CT4irPeOCPd8z7rF8';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

$params = array(
    'transaction_details' => [
        'order_id' => $nota,
        'gross_amount' => $total,
    ],
    'customer_details' => [
        'first_name' => $firstname,
        'phone' => $noTelepon,
        'email' => 'fauzannnahmad26@gmail.com',
    ],
    'enabled_payments' => ['gopay'],
    'vtweb' => []
);

try {
  // Get Snap Payment Page URL
    $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
    
  // Redirect to Snap Payment Page
    // echo "
    //     <script>
    //         document.location.href=' '$paymentUrl;
    //     </script>
    // ";
    header('Location: ' . $paymentUrl);
}
catch (Exception $e) {
    echo $e->getMessage();
}