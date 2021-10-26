<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction || Finish</title>
    <link rel="icon" href="../../../../assets/img/icon/favicon2.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <header>
            <div class="moto">
                <p>Build your website with us</p>
            </div>
            <div class="brand">
                <div class="img-brand">
                    <img src="../img/logo.png" alt="logo">
                </div>
                <h5>Design</h5>
            </div>
        </header>
        <div class="content">
            <div class="icon-hero">
                <img src="../img/succes-1.svg" alt="">
            </div>
            <div class="text">
                <h1>Yay! Success</h1>
                <p>
                    <?= (isset($_GET['transaction_status']) && $_GET['transaction_status'] == 'pending') ? 'Your transaction status is pending.
                    <br>' : ''; ?>
                    We've sent you text messages for this status transaction
                    <br>
                    please read it as well
                </p>
                <a href="../../../index.php">Dashboard</a>
            </div>
        </div>
    </div>
</body>

</html>