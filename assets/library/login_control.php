<?php
require_once "function.php";

$library = new Library();
$messages = array();
$status = true;
session_start();
$user = "petugas";

try {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $remember = isset($_POST['remember']) ? $_POST['remember'] : '';
    $query = "";

    if (!isset($username) || empty($username)) {
        $status = false;
        $messages[] = "Username tidak boleh kosong ";
    }

    if (!isset($password) || empty($password)) {
        $status = false;
        $messages[] = "Password tidak boleh kosong ";
    }

    if ($status) {
        $queryPetugas = $library->conn->query("SELECT*FROM `login` WHERE username='$username';");
        $rowPetugas = $queryPetugas->fetch(PDO::FETCH_ASSOC);
        $queryAnggota = $library->conn->query("SELECT*FROM `anggota` WHERE username='$username';");
        $rowAnggota = $queryAnggota->fetch(PDO::FETCH_ASSOC);

        if (($queryPetugas->rowCount() < 1) && ($queryAnggota->rowCount() < 1)) {
            $status = false;
            $messages[] = "username anda salah.";
        }

        if ($queryPetugas->rowCount() > 0) {
            // verifikasi password 
            if (password_verify($password, $rowPetugas['password'])) {
                $idPetugas = $rowPetugas['idpetugas'];
                $queryDataPetugas = $library->conn->query("SELECT `status` FROM petugas WHERE idpetugas='$idPetugas';");
                $rowDataPetugas = $queryDataPetugas->fetch(PDO::FETCH_ASSOC);
                if ($rowDataPetugas['status'] === 'nonaktif') {
                    $status = false;
                    $messages[] = "status anda nonaktif. silahkan hubungi petugas koperasi.";
                } else {
                    // Set Session
                    $_SESSION['key'] = $idPetugas;
                    $_SESSION['user'] = "petugas";
                    $_SESSION['login'] = true;
                }
            } else {
                $status = false;
                $messages[] = "password anda salah";
            }
        }

        if ($queryAnggota->rowCount() > 0) {
            $user = "anggota";
            // verifikasi password 
            if (password_verify($password, $rowAnggota['password'])) {
                $noKta = $rowAnggota['nokta'];
                if ($rowAnggota['status'] === 'nonaktif') {
                    $status = false;
                    $messages[] = "status anda nonaktif. silahkan hubungi petugas koperasi.";
                } else {
                    // Set Session
                    $_SESSION['key'] = $noKta;
                    $_SESSION['user'] = "anggota";
                    $_SESSION['login'] = true;
                }
            } else {
                $status = false;
                $messages[] = "password anda salah";
            }
        }
    }
    // Error mode
    $library->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $status = false;
    $messages[] = "Koneksi atau query pada halaman login bermasalah = " . $e->getMessage();
}

echo json_encode(
    array(
        'status' => $status,
        'message' => $messages,
        'user' => $user
    )
);
