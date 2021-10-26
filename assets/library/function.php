<?php
require_once 'function_control.php';
define('STATUS_DEV', 'production');
// date_default_timezone_set('Asia/Ujung_Pandang');

class Library
{
    // Start koneksi DATABASE
    public function __construct()
    {
        try {
            // String koneksi
            $host       = "localhost";
            $db_name    = "dbkoperasi";
            $username   = "root";
            $password   = "";
            // $host       = "localhost";
            // $db_name    = "bumdeskl_koperasi";
            // $username   = "bumdeskl_bumdesjimbung";
            // $password   = "WyJiN~u+1zQ+";

            // koneksi database dengan PDO
            $this->conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
            // mode error/exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // hapus koneksi
            // $this->conn = null;

        } catch (PDOException $e) {
            print "Koneksi atau query bermasalah : " . $e->getMessage() . "<br>";
            die();
        }
    }
    // #END koneksi DATABASE

    // Start Section Petugas
    //================== 1.SIMPAN ===================
    public function insertPetugas($noKtp, $nama, $gender, $alamat, $jabatan, $tglLahir)
    {
        try {
            // Cek kesamaan ktp
            $query = $this->conn->query("SELECT*FROM petugas WHERE noktp='$noKtp';");
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if (($query->rowCount() === 1) && ($row['status'] === "aktif")) {
                echo "
                <script>
                    alert('No KTP " . $noKtp . " Telah terdaftar');
                    document.location.href = 'addNew_petugas.php';
                </script>
                ";
                die;
            } else {
                if (($query->rowCount() === 1) && ($row['status'] === "nonaktif")) {
                    $query = $this->conn->query("UPDATE petugas SET `status`='aktif'");
                    if ($query->rowCount() === 1) {
                        echo "
                            <script>
                                alert('berhasil menambahkan data');
                                document.location.href = 'addNew_petugas.php';
                            </script>
                        ";
                        die;
                    } else {
                        echo "
                            <script>
                                alert('gagal menambahkan data');
                                document.location.href = 'addNew_petugas.php';
                            </script>
                        ";
                        die;
                    }
                } else {
                    $query = $this->conn->query("INSERT INTO petugas 
                                                VALUES
                                                (''
                                                , '$noKtp'
                                                , '$nama'
                                                , '$gender'
                                                , '$alamat'
                                                , '$jabatan'
                                                , '$tglLahir'
                                                , 'aktif'
                                                , NOW()
                                                );");
                    if ($query->rowCount() === 1) {
                        echo "
                            <script>
                                alert('berhasil menambahkan data');
                                document.location.href = 'addNew_petugas.php';
                            </script>
                        ";
                        die;
                    } else {
                        echo "
                            <script>
                                alert('gagal menambahkan data');
                                document.location.href = 'addNew_petugas.php';
                            </script>
                        ";
                        die;
                    }
                }
            }

            // ERROR MODE
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Koneksi atau Query insert petugas bermasalah : " . $e->getMessage() . "<br>";
            die();
        }
    }
    //================== 2.EDIT =====================
    public function updatePetugas($id, $noKtp, $nama, $gender, $alamat, $jabatan, $tglLahir, $status)
    {
        try {
            // Cek kesamaan ktp
            $query = $this->conn->query("SELECT*FROM petugas WHERE noktp='$noKtp';");
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $query2 = $this->conn->query("SELECT*FROM petugas WHERE noktp='$noKtp' AND idpetugas='$id';");
            $row2 = $query->fetch(PDO::FETCH_ASSOC);

            if (($query->rowCount() === 1) && ($query2->rowCount() !== 1)) {
                echo "
                <script>
                    alert('No KTP " . $noKtp . " Telah terdaftar');
                    window.history.back();
                </script>
                ";
                die;
            } else {
                $query = $this->conn->query("UPDATE petugas 
                                            SET
                                            noktp='$noKtp', 
                                            nama='$nama', 
                                            jenkel='$gender', 
                                            alamat='$alamat', 
                                            jabatan='$jabatan', 
                                            `status`='$status', 
                                            tgllahir='$tglLahir'
                                            WHERE 
                                            idpetugas='$id';
                                            ");
                if ($query->rowCount() === 1) {
                    return $query->rowCount();
                    die;
                } else {
                    return $query->rowCount();
                    die;
                }
            }

            // ERROR MODE
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Koneksi atau Query update petugas bermasalah : " . $e->getMessage() . "<br>";
            die();
        }
    }
    //================== 3.DELETE ===================
    public function deletePetugas($id)
    {
        try {
            $query = $this->conn->query("UPDATE petugas SET `status`='nonaktif' WHERE idpetugas='$id'");

            if ($query->rowCount() > 0) {
                echo "
                    <script>
                        document.location.href = 'data_petugas.php';
                    </script>
                ";
                exit;
            } else {
                echo "
                    <script>
                        alert('Gagal Menghapus data');
                        document.location.href = 'data_petugas.php';
                    </script>
                ";
                exit;
            }
            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Koneksi atau Query delete petugas bermasalah : " . $e->getMessage() . "<br>";
            die();
        }
    }
    // #END Section Petugas

    // Start Section LOGIN
    //================== 1.SIMPAN ===================
    public function insertAkunPetugas($noKtp, $username, $password, $konfPassword)
    {
        try {
            $query = $this->conn->query("SELECT*FROM petugas WHERE noktp='$noKtp';");
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $idPetugas = $row['idpetugas'];
            $passwordHash = "";
            $akunUsername = $this->conn->query("SELECT*FROM anggota WHERE username='$username';");
            $akunUsernamePetugas = $this->conn->query("SELECT*FROM login WHERE username='$username';");
            if (($akunUsername->rowCount() > 0) || ($akunUsernamePetugas->rowCount() > 0)) {
                echo "
                    <script>
                        alert('USERNAME tidak tersedia');
                        document.location.href = 'singup.php';
                    </script>
                ";
                die();
            }

            if ($query->rowCount() === 0) {
                echo "
                    <script>
                        alert('No KTP anda tidak terdaftar');
                        document.location.href = 'singup.php';
                    </script>
                ";
                die();
            } else {
                $query = $this->conn->query("SELECT*FROM `login` WHERE idpetugas='$idPetugas';");
                if ($query->rowCount() > 0) {
                    echo "
                        <script>
                            alert('Akun anda telah terdaftar');
                            document.location.href = 'singup.php';
                        </script>
                    ";
                    die();
                } else {
                    if ($password !== $konfPassword) {
                        echo "
                            <script>
                                alert('Pastikan mengkonfirmasi password anda');
                                document.location.href = 'singup.php';
                            </script>
                        ";
                        die();
                    } else {
                        if (strlen($password) < 10) {
                            echo "
                                <script>
                                    alert('password minimal 10 karakter');
                                    document.location.href = 'singup.php';
                                </script>
                            ";
                            exit;
                        } else {
                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                            $query = $this->conn->query("INSERT INTO `login` VALUES('$idPetugas','$username','$passwordHash');");
                            if ($query->rowCount() > 0) {
                                echo "
                                    <script>
                                        alert('Berhasil');
                                        document.location.href = 'login.php';
                                    </script>
                                ";
                                exit;
                            } else {
                                echo "
                                    <script>
                                        alert('Gagal Menambahkan data');
                                        document.location.href = 'singup.php';
                                    </script>
                                ";
                                exit;
                            }
                        }
                    }
                }
            }

            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Koneksi atau Query insert akun petugas bermasalah : " . $e->getMessage() . "<br>";
            die();
        }
    }
    public function insertAkunAnggota($noKtp, $username, $password, $konfPassword)
    {
        try {
            $query = null;
            $query = $this->conn->query("SELECT*FROM anggota WHERE nokta='$noKtp';");
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $passwordHash = "";

            $akunUsername = $this->conn->query("SELECT*FROM anggota WHERE username='$username';");
            $akunUsernamePetugas = $this->conn->query("SELECT*FROM login WHERE username='$username';");
            if (($akunUsername->rowCount() > 0) || ($akunUsernamePetugas->rowCount() > 0)) {
                echo "
                    <script>
                        alert('USERNAME tidak tersedia');
                        document.location.href = 'singup.php';
                    </script>
                ";
                die();
            }

            if ($query->rowCount() < 1) {
                echo "
                    <script>
                        alert('No KTA anda tidak terdaftar');
                        document.location.href = 'singup.php';
                    </script>
                ";
                die();
            } else {
                if (!empty($row['username']) && !empty($row['password'])) {
                    echo "
                        <script>
                            alert('Akun anda telah terdaftar');
                            document.location.href = 'singup.php';
                        </script>
                    ";
                    die();
                } else {
                    if ($password !== $konfPassword) {
                        echo "
                            <script>
                                alert('Pastikan mengkonfirmasi password anda');
                                document.location.href = 'singup.php';
                            </script>
                        ";
                        die();
                    } else {
                        if (strlen($password) < 10) {
                            echo "
                                <script>
                                    alert('password minimal 10 karakter');
                                    document.location.href = 'singup.php';
                                </script>
                            ";
                            exit;
                        } else {
                            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                            // var_dump($passwordHash);die;
                            // echo $noKtp."  ".$username."  ".$password."  ".$konfPassword."  ".$passwordHash;
                            $query = $this->conn->query("UPDATE anggota 
                                    SET
                                    username='$username',
                                    password='$passwordHash'
                                    WHERE
                                    nokta='$noKtp';
                            ");

                            if ($query->rowCount() > 0) {
                                echo "
                                    <script>
                                        alert('Berhasil');
                                        document.location.href = 'login.php';
                                    </script>
                                ";
                                exit;
                            } else {
                                echo "
                                    <script>
                                        alert('Gagal Menambahkan data');
                                        document.location.href = 'singup.php';
                                    </script>
                                ";
                                exit;
                            }
                        }
                    }
                }
            }

            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Koneksi atau Query insert akun anggota bermasalah : " . $e->getMessage() . "<br>";
            die();
        }
    }
    //================== 2.EDIT Reset Password ===================
    public function resetPass($noKtp, $password, $konfPassword)
    {
        try {
            $query = "";
            if (is_numeric($noKtp)) {
                $query = $this->conn->query("SELECT*FROM petugas WHERE noktp='$noKtp'");
                $row = $query->fetch(PDO::FETCH_ASSOC);
                $idPetugas = $row['idpetugas'];

                if ($query->rowCount() > 0) {
                    $query = $this->conn->query("SELECT*FROM `login` WHERE idpetugas='$idPetugas'");
                    if ($query->rowCount() > 0) {
                        if ($password !== $konfPassword) {
                            echo "
                                <script>
                                    alert('Pastikan mengkonfirmasi password anda');
                                    document.location.href = 'forgot.php';
                                </script>
                            ";
                            die();
                        } else {
                            if (strlen($password) < 10) {
                                echo "
                                    <script>
                                        alert('password minimal 10 karakter');
                                        document.location.href = 'forgot.php';
                                    </script>
                                ";
                                exit;
                            } else {
                                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                                $query = $this->conn->query("UPDATE `login` SET `password`='$passwordHash';");
                                if ($query->rowCount() > 0) {
                                    echo "
                                        <script>
                                            alert('Berhasil');
                                            document.location.href = 'login.php';
                                        </script>
                                    ";
                                    exit;
                                } else {
                                    echo "
                                        <script>
                                            alert('Gagal Menambahkan data');
                                            document.location.href = 'singup.php';
                                        </script>
                                    ";
                                    exit;
                                }
                            }
                        }
                    } else {
                        echo "
                            <script>
                                alert('Akun anda belum terdaftar');
                                document.location.href='forgot.php';
                            </script>
                        ";
                        exit;
                    }
                } else {
                    echo "
                        <script>
                            alert('No KTP anda tidak terdaftar');
                            document.location.href='forgot.php';
                        </script>
                    ";
                    exit;
                }
            } else {
                echo "
                    <script>
                        alert('Masukkan No KTP yang benar. Contoh : 7401072455488788');
                        document.location.href='forgot.php';
                    </script>
                ";
                exit;
            }
            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Koneksi atau Query reset pass petugas bermasalah : " . $e->getMessage() . "<br>";
            die();
        }
    }
    public function resetAccount($id, $usernameAccount)
    {
        $control = new Control();
        try {
            $query = "";
            $query = $this->conn->query("SELECT*FROM petugas WHERE idpetugas='$id'");
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($query->rowCount() > 0) {
                $query = $this->conn->query("SELECT*FROM `login` WHERE username='$usernameAccount'");
                if ($query->rowCount() > 0) {
                    $id = $control->hashMethod('encrypt', $id);
                    echo "
                        <script>
                            alert('Username tidak tersedia');
                            document.location.href = 'setting_petugas.php?data=$id';
                        </script>
                    ";
                    exit;
                } else {
                    $query = $this->conn->query("UPDATE `login` SET `username`='$usernameAccount';");
                    if ($query->rowCount() > 0) {
                        $id = $control->hashMethod('encrypt', $id);
                        echo "
                            <script>
                                alert('Berhasil. silahkan login kembali');
                                document.location.href = '../loginpage/logout.php';
                            </script>
                        ";
                        exit;
                    } else {
                        echo "
                            <script>
                                alert('Gagal Menambahkan data');
                                document.location.href = '../../index.php';
                            </script>
                        ";
                        exit;
                    }
                }
            } else {
                echo "
                    <script>
                        alert('Data tidak ditemukan');
                        document.location.href='../../index.php';
                    </script>
                ";
                exit;
            }
            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Koneksi atau Query reset username petugas bermasalah : " . $e->getMessage() . "<br>";
            die();
        }
    }
    public function changePassword($id, $passwordLama, $passwordBaru, $konfirmasiPassword)
    {
        $control = new Control();
        try {
            $query = "";
            $query = $this->conn->query("SELECT*FROM petugas WHERE idpetugas='$id'");
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($query->rowCount() > 0) {
                $query = $this->conn->query("SELECT*FROM `login` WHERE idpetugas='$id'")->fetch(PDO::FETCH_ASSOC);
                if (password_verify($passwordLama, $query['password'])) {
                    if ($passwordBaru != $konfirmasiPassword) {
                        $id = $control->hashMethod('encrypt', $id);
                        echo "
                            <script>
                                alert('Pastikan mengkonfirmasi password anda');
                                document.location.href = 'reset_password.php?data=$id';
                            </script>
                        ";
                        die();
                    } else {
                        if (strlen($passwordBaru) < 10) {
                            $id = $control->hashMethod('encrypt', $id);
                            echo "
                                <script>
                                    alert('password minimal 10 karakter');
                                    document.location.href = 'reset_password.php?data=$id';
                                </script>
                            ";
                            exit;
                        } else {
                            $passwordHash = password_hash($passwordBaru, PASSWORD_DEFAULT);
                            $query = $this->conn->query("UPDATE `login` SET `password`='$passwordHash';");
                            if ($query->rowCount() > 0) {
                                echo "
                                    <script>
                                        alert('Berhasil');
                                        document.location.href = '../loginpage/logout.php';
                                    </script>
                                ";
                                exit;
                            } else {
                                echo "
                                    <script>
                                        alert('Gagal Menambahkan data');
                                        document.location.href='../../index.php';
                                    </script>
                                ";
                                exit;
                            }
                        }
                    }
                } else {
                    $id = $control->hashMethod('encrypt', $id);
                    echo "
                        <script>
                            alert('Password Lama Anda Salah');
                            document.location.href = 'reset_password.php?data=$id';
                        </script>
                    ";
                    exit;
                }
            } else {
                echo "
                    <script>
                        alert('Gagal Menambahkan data');
                        document.location.href='../../index.php';
                    </script>
                ";
                exit;
            }
            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Koneksi atau Query reset username petugas bermasalah : " . $e->getMessage() . "<br>";
            die();
        }
    }
    // #END Section LOGIN

    // Start Section Anggota
    //================== 1.SIMPAN ===================
    public function insertAnggota($nama, $alamat, $noTelepon, $pekerjaan, $gender)
    {
        // SET TIMEZONE
        date_default_timezone_set('Asia/Jakarta');
        $control = new Control();
        try {
            // create noKta
            // 1.Select max dari 5 string terakhir
            $tahun = date('Y');
            $query = $this->conn->query("SELECT MAX(SUBSTRING(nokta, -5)) AS nokta FROM anggota");
            // $query = $this->conn->query("SELECT MAX(SUBSTRING(nokta, 7, 5)) AS nokta FROM anggota WHERE nokta LIKE '%{$tahun}%'");
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $maxKta = $row['nokta'];
            // 2. pecah menjadi 2 bagian : 6 digit pertama bulan-tahun, 5 digit kedua nomor urut 
            $bulanTahun = date('mY');
            $maxKta++;
            $noKta = $bulanTahun . sprintf("%05d", $maxKta); //sprintf adalah zero padding berfungsi menampilkan angka 0 didepan
            // Insert Data
            $queryInsert = $this->conn->query("INSERT INTO anggota
                                        VALUES 
                                        ('$noKta',
                                        '$nama',
                                        '$gender',
                                        '$alamat',
                                        '$noTelepon',
                                        '$pekerjaan',
                                        'aktif',
                                        NOW(),
					'',
					'')
                                        ");

            if ($queryInsert->rowCount() > 0) {
                $noKta = $control->hashMethod('encrypt', $noKta);
                echo "
                    <script>
                        document.location.href = 'add_anggota.php?data=success $noKta';
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Gagal menambahkan anggota baru');
                        document.location.href = 'add_anggota.php';
                    </script>
                ";
            }
            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Koneksi atau query pada insert anggota baru bermasalah : " . $e->getMessage() . "<br>";
            die;
        }
    }
    //================== 2.EDIT =====================
    public function updateAnggota($data, $nama, $gender, $alamat, $noTelepon, $pekerjaan, $status)
    {
        try {
            $control = new Control();
            // cek no kta 
            $noKta = $control->hashMethod('decrypt', $data);
            $query = $this->conn->query("SELECT*FROM anggota WHERE nokta='$noKta'");
            if ($query->rowCount() < 1) {
                echo "
                    <script>
                        alert('no kta tidak terdaftar');
                        document.location.href = 'update_anggota.php?data=" . $data . "';
                    </script>
                ";
                die;
            } else {
                if ($status === null) {
                    $query = $this->conn->query("UPDATE anggota 
                                                SET
                                                nama='$nama',
                                                jenkel='$gender',
                                                alamat='$alamat',
                                                notelepon='$noTelepon',
                                                pekerjaan='$pekerjaan'
                                                WHERE
                                                nokta='$noKta';
                                                ");
                    if ($query->rowCount() > 0) {
                        echo "
                            <script>
                                alert('Data berhasil di ubah');
                                document.location.href = 'data_anggota.php';
                            </script>
                        ";
                        die;
                    } else {
                        echo "
                            <script>
                                alert('Data gagal di ubah');
                                document.location.href = 'update_anggota.php?data=" . $data . "';
                            </script>
                        ";
                        die;
                    }
                } else {
                    $query = $this->conn->query("UPDATE anggota 
                                                SET
                                                nama='$nama',
                                                jenkel='$gender',
                                                alamat='$alamat',
                                                notelepon='$noTelepon',
                                                pekerjaan='$pekerjaan',
                                                `status`='$status'
                                                WHERE
                                                nokta='$noKta';
                                                ");
                    if ($query->rowCount() > 0) {
                        echo "
                            <script>
                                alert('Data dan status berhasil di ubah');
                                document.location.href = 'data_anggota.php';
                            </script>
                        ";
                        die;
                    } else {
                        echo "
                            <script>
                                alert('Data gagal di ubah');
                                document.location.href = 'update_anggota.php?data=" . $data . "';
                            </script>
                        ";
                        die;
                    }
                }
            }
            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Koneksi atau query database bermasalah update anggota : " . $e->getMessage() . "<br>";
            exit;
        }
    }
    //================== 3.DELETE ===================
    public function deleteAnggota($noKta)
    {
        try {
            $query = $this->conn->query("SELECT*FROM anggota WHERE nokta='$noKta' AND `status`='nonaktif'");
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $nama = $row['nama'];

            if ($query->rowCount() > 0) {
                echo "
                    <script>
                        alert('Data tidak dapat dihapus. " . $nama . " Sudah berstatus nonaktif');
                        document.location.href = 'data_anggota.php';
                    </script>
                ";
                die;
            } else {
                // cek siwapo
                $siwapo = $this->conn->query("SELECT*FROM siwapo WHERE nokta='$noKta' AND `status`='aktif';");
                // cek simapan
                $simapan = $this->conn->query("SELECT*FROM simapan WHERE nokta='$noKta' AND `status`='aktif';");
                // cek pinjaman
                $pinjaman = $this->conn->query("SELECT*FROM pinjaman WHERE nokta='$noKta' AND `status`='aktif';");
                // cek sisukarela
                $sisukarela = $this->conn->query("SELECT*FROM sisukarela WHERE nokta='$noKta' AND `status`='aktif';");
                // cek sianggota
                $sianggota = $this->conn->query("SELECT*FROM sianggota WHERE nokta='$noKta' AND `status`='aktif';");
                // nama anggota
                $query = $this->conn->query("SELECT*FROM anggota WHERE nokta='$noKta';");
                $row = $query->fetch(PDO::FETCH_ASSOC);
                $nama = $row['nama'];

                if (
                    $siwapo->rowCount() > 0 || $simapan->rowCount() > 0 || $pinjaman->rowCount() > 0
                    || $sisukarela->rowCount() > 0 || $sianggota->rowCount() > 0
                ) {
                    echo "
                        <script>
                            alert('" . $nama . " Masih memiliki tabungan atau pinjaman yang aktif');
                            document.location.href = 'data_anggota.php';
                        </script>
                    ";
                    die;
                } else {
                    $query = $this->conn->query("UPDATE anggota SET `status`='nonaktif' WHERE nokta='$noKta';");
                    if ($query->rowCount() > 0) {
                        echo "
                            <script>
                                alert('Status " . $nama . " berhasil di nonaktif');
                                document.location.href = 'data_anggota.php';
                            </script>
                        ";
                        die;
                    } else {
                        echo "
                            <script>
                                alert('Status " . $nama . " gagal di nonaktif');
                                document.location.href = 'data_anggota.php';
                            </script>
                        ";
                        die;
                    }
                }
            }
            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Koneksi atau query delete anggota bermasalah : " . $e->getMessage() . "<br>";
            die;
        }
    }
    // #END Section Anggota

    // Start Section Settings
    // ========================== 1.SIMPAN MASTERHARGA ==========================
    public function insertMasterHarga($idpetugas, $minHargaSiwajib, $maxHargaSiwajib, $minHargaSipokok, $maxHargaSipokok, $minHargaSimapan, $maxHargaSimapan, $minHargaSisukarela, $maxHargaSisukarela, $minHargaSianggota, $maxHargaSianggota, $minHargaPinjaman, $maxHargaPinjaman)
    {
        try {
            $query = null;
            $masterSiwajib    = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan wajib';");
            if ($masterSiwajib->rowCount() > 0) {
                $query = $this->conn->query("UPDATE masterharga set `max`='$maxHargaSiwajib', `min`='$minHargaSiwajib', waktuubah=NOW() WHERE nama='simpanan wajib';");
            } else {
                $query = $this->conn->query("INSERT INTO masterharga VALUES('','$idpetugas','simpanan wajib','$maxHargaSiwajib','$minHargaSiwajib',NOW(),'');");
            }

            $masterSipokok   = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan pokok';");
            if ($masterSipokok->rowCount() > 0) {
                $query = $this->conn->query("UPDATE masterharga set `max`='$maxHargaSipokok', `min`='$minHargaSipokok', waktuubah=NOW() WHERE nama='simpanan pokok';");
            } else {
                $query = $this->conn->query("INSERT INTO masterharga VALUES('','$idpetugas','simpanan pokok','$maxHargaSipokok','$minHargaSipokok',NOW(),'');");
            }

            $masterSimapan    = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan masadepan';");
            if ($masterSimapan->rowCount() > 0) {
                $query = $this->conn->query("UPDATE masterharga set `max`='$maxHargaSimapan', `min`='$minHargaSimapan', waktuubah=NOW() WHERE nama='simpanan masadepan';");
            } else {
                $query = $this->conn->query("INSERT INTO masterharga VALUES('','$idpetugas','simpanan masadepan','$maxHargaSimapan','$minHargaSimapan',NOW(),'');");
            }

            $masterSisukarela = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan sukarela';");
            if ($masterSisukarela->rowCount() > 0) {
                $query = $this->conn->query("UPDATE masterharga set `max`='$maxHargaSisukarela', `min`='$minHargaSisukarela', waktuubah=NOW() WHERE nama='simpanan sukarela';");
            } else {
                $query = $this->conn->query("INSERT INTO masterharga VALUES('','$idpetugas','simpanan sukarela','$maxHargaSisukarela','$minHargaSisukarela',NOW(),'');");
            }

            $masterSianggota  = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan anggota';");
            if ($masterSianggota->rowCount() > 0) {
                $query = $this->conn->query("UPDATE masterharga set `max`='$maxHargaSianggota', `min`='$minHargaSianggota', waktuubah=NOW() WHERE nama='simpanan anggota';");
            } else {
                $query = $this->conn->query("INSERT INTO masterharga VALUES('','$idpetugas','simpanan anggota','$maxHargaSianggota','$minHargaSianggota',NOW(),'');");
            }

            $masterPinjaman   = $this->conn->query("SELECT*FROM masterharga WHERE nama='pinjaman';");
            if ($masterPinjaman->rowCount() > 0) {
                $query = $this->conn->query("UPDATE masterharga set `max`='$maxHargaPinjaman', `min`='$minHargaPinjaman' ,waktuubah=NOW() WHERE nama='pinjaman';");
            } else {
                $query = $this->conn->query("INSERT INTO masterharga VALUES('','$idpetugas','pinjaman','$maxHargaPinjaman','$minHargaPinjaman',NOW(),'');");
            }

            if ($query->rowCount() > 0) {
                echo "
                    <script>
                        alert('Berhasil');
                        document.location.href = 'add_settings.php';
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Gagal');
                        document.location.href = 'add_settings.php';
                    </script>
                ";
            }
            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Koneksi atau query settings master harga bermasalah : " . $e->getMessage() . "<br>";
            die;
        }
    }
    // ========================== 2.INSERT MASTERBUNGA ==========================
    public function insertMasterBunga($idpetugas, $id, $bunga, $waktu, $jangka, $jenis)
    {
        try {
            $query = null;
            $keterangan = $waktu . " " . $jangka;

            if ($id != 0) {
                $query = $this->conn->query("UPDATE masterbunga 
                                            SET 
                                            idpetugas='$idpetugas',
                                            keterangan='$keterangan',
                                            namabunga='$jenis',
                                            total='$bunga',
                                            waktuubah=NOW()
                                            WHERE 
                                            idbunga='$id';
                                            ");
            }

            if ($id == 0) {
                $query = $this->conn->query("INSERT INTO 
                                            masterbunga 
                                            VALUES
                                            (
                                            ''
                                            ,'$idpetugas'
                                            ,'$jenis'
                                            ,'$keterangan'
                                            ,'$bunga'
                                            ,NOW()
                                            ,'---'
                                            );");
            }

            if ($query->rowCount() > 0) {
                echo "
                    <script>
                        alert('Berhasil');
                        document.location.href = 'add_settings.php';
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Gagal');
                        document.location.href = 'add_settings.php';
                    </script>
                ";
            }
            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Koneksi atau query settings master bunga bermasalah : " . $e->getMessage() . "<br>";
            die;
        }
    }
    // #END Section Settings

    // Start Section Transaksi
    // ========================== 1.SIMPAN TRANSAKSI DEBIT ==========================
    public function insertTransaksiDebit($idpetugas, $nokta, $angsuranPokok, $angsuranBunga, $simpananSukarela, $simpananWajib, $simpananPokok, $simpananMasadepan)
    {
        $control = new Control();
        try {
            $query = null;
            $saldoKredit = 0;
            // cek minimum harga
            $hargaSiwajib       = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan wajib'")->fetch(PDO::FETCH_ASSOC);
            $hargaSipokok       = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan pokok'")->fetch(PDO::FETCH_ASSOC);
            $hargaSisukarela    = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan sukarela'")->fetch(PDO::FETCH_ASSOC);
            $hargaSimapan       = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan masadepan'")->fetch(PDO::FETCH_ASSOC);
            $hargaPinjaman      = $this->conn->query("SELECT*FROM pinjaman WHERE nokta='$nokta' AND `status`='aktif'")->fetch(PDO::FETCH_ASSOC);
            $hargaSianggota     = $this->conn->query("SELECT*FROM sianggota WHERE nokta='$nokta' AND `status`='aktif'")->fetch(PDO::FETCH_ASSOC);

            // cek saldo
            // =======================SISUKARELA=======================
            $sisukarela         = $this->conn->query("SELECT * FROM `sisukarela` WHERE nokta='$nokta' AND `status`='aktif' ORDER BY waktutransaksi DESC LIMIT 0,1");
            $rowSisukarela      = $sisukarela->fetch(PDO::FETCH_ASSOC);
            $saldoSisukarela    = null;
            if (!empty($simpananSukarela)) {
                $saldoSisukarela = $rowSisukarela['saldo'] + $simpananSukarela;
            }
            // =======================PINJAMAN=======================
            $pinjaman           = $this->conn->query("SELECT*FROM pinjaman WHERE nokta='$nokta' AND `status`='aktif'");
            $rowPinjaman        = $pinjaman->fetch(PDO::FETCH_ASSOC);
            // =======================ANGSURAN=======================
            $angsuran           = $this->conn->query("SELECT*FROM angsuran WHERE nokta='$nokta' AND `status`='aktif' ORDER BY idangsuran ASC");
            $rowAngsuran        = $angsuran->fetch(PDO::FETCH_ASSOC);
            $totalBunga         = null;
            $totalPokok         = null;
            $totalSetor         = null;
            $saldoKredit        = null;
            $statusAngsuran     = "aktif";
            // =======================REMINDER=======================
            $reminder           = $this->conn->query("SELECT MIN(noangsuran) AS noangsuran FROM `reminderpinjaman` WHERE nokta='$nokta' AND status='aktif'");
            $rowReminder        = $reminder->fetch(PDO::FETCH_ASSOC);

            if (!empty($angsuranPokok) && !empty($angsuranBunga)) {
                $totalBunga     = $rowAngsuran['totalbunga'] + $angsuranBunga;
                $totalPokok     = $rowAngsuran['totalpokok'] + $angsuranPokok;
                $totalSetor     = $angsuranBunga + $angsuranPokok;
                $saldoKredit    = $rowAngsuran['saldokredit'] - $totalSetor;
            }

            // =======================SIWAJIB=======================
            $siwajib            = $this->conn->query("SELECT SUM(subtotal) AS saldo FROM siwapo WHERE nokta='$nokta' AND `status`='aktif' AND keterangan='debit siwajib' ORDER BY idsiwapo ASC")->fetch(PDO::FETCH_ASSOC);
            $saldoSiwajib       = null;
            if (!empty($simpananWajib)) {
                $saldoSiwajib   = $siwajib['saldo'] + $simpananWajib;
            }
            // =======================SIPOKOK=======================
            $sipokok            = $this->conn->query("SELECT SUM(subtotal) AS saldo FROM siwapo WHERE nokta='$nokta' AND `status`='aktif' AND keterangan='debit sipokok' ORDER BY idsiwapo ASC")->fetch(PDO::FETCH_ASSOC);
            $saldoSipokok       = null;
            if (!empty($simpananPokok)) {
                $saldoSipokok   = $sipokok['saldo'] + $simpananPokok;
            }
            // ====================SIMAPAN====================
            $simapan            = $this->conn->query("SELECT MAX(nokartu) AS nomor, simapan.* FROM simapan WHERE nokta='$nokta'")->fetch(PDO::FETCH_ASSOC);

            // ====================Create NOKTA====================
            // 1.Select max dari 5 string terakhir
            $bulanTahun = date('dmy');
            $queryNota = $this->conn->query("SELECT MAX(SUBSTRING(notransaksi, 7, 11)) AS nota FROM riwayattransaksi WHERE notransaksi LIKE '$bulanTahun%'");
            $row = $queryNota->fetch(PDO::FETCH_ASSOC);
            $maxNota = $row['nota'];
            // 2. pecah menjadi 2 bagian : 6 digit pertama bulan-tahun, 5 digit kedua nomor urut 
            $maxNota++;
            $nota = $bulanTahun . sprintf("%05d", $maxNota); //sprintf adalah zero padding berfungsi menampilkan angka 0 didepan

            // kondisi transaksi angsuran
            if ((!empty($angsuranPokok) && empty($angsuranBunga)) || (empty($angsuranPokok) && !empty($angsuranBunga))) {
                echo
                    "<script>
                alert('angsuran pokok dan angsuran bunga wajib dibayar bersamaan');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            if ((!empty($angsuranPokok) && !empty($angsuranBunga)) && ($pinjaman->rowCount() < 1)) {
                echo
                    "<script>
                alert('anda tidak memiliki pinjaman yang aktif');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            if (($pinjaman->rowCount() > 0) && (!empty($angsuranPokok) && !empty($angsuranBunga)) && (($angsuranPokok < $rowPinjaman['t_pokok']) || ($angsuranBunga < $rowPinjaman['t_bunga']))) {
                echo
                    "<script>
                alert('angsuran pokok anda minimal " . $control->rupiah($rowPinjaman['t_pokok']) . " dan angsuran bunga " . $control->rupiah($rowPinjaman['t_pokok']) . "');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            if (($pinjaman->rowCount() > 0) && ($angsuran->rowCount() > 0) && (!empty($angsuranPokok) && !empty($angsuranBunga)) && ($saldoKredit < 0)) {
                echo
                    "<script>
                alert('saldo kredit anda sisa " . $control->rupiah($rowAngsuran['saldokredit']) . " uang anda kebanyakan');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            // kondisi siwajib
            if ((!empty($simpananWajib) && ($simpananWajib < $hargaSiwajib['min']))
                || (!empty($simpananWajib) && ($simpananWajib > $hargaSiwajib['max']))
                || (!empty($simpananWajib) && ($saldoSiwajib > $hargaSiwajib['max']))
            ) {
                echo
                    "<script>
                alert('simpanan wajib minimum transaksi " . $control->rupiah($hargaSiwajib['min']) . " dan maximal " . $control->rupiah($hargaSiwajib['max']) . "');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            // kondisi sipokok
            if ((!empty($simpananPokok) && ($simpananPokok < $hargaSipokok['min']))
                || (!empty($simpananPokok) && ($simpananPokok > $hargaSipokok['max']))
                || (!empty($simpananPokok) && ($saldoSipokok > $hargaSipokok['max']))
            ) {
                echo
                    "<script>
                alert('simpanan pokok minimum transaksi " . $control->rupiah($hargaSipokok['min']) . " dan maximal " . $control->rupiah($hargaSipokok['max']) . "');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            // kondisi sisukarela
            if ((!empty($simpananSukarela) && (($sisukarela->rowCount() < 1) && ($simpananSukarela < $hargaSisukarela['min'])))
                || (!empty($simpananSukarela) && ($simpananSukarela > $hargaSisukarela['max']))
                || (!empty($simpananSukarela) && ($saldoSisukarela > $hargaSisukarela['max']))
            ) {
                echo
                    "<script>
                alert('pengguna baru simpanan sukarela minimum transaksi " . $control->rupiah($hargaSisukarela['min']) . " dan maximal " . $control->rupiah($hargaSisukarela['max']) . "');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            // kondisi simapan
            if ((!empty($simpananMasadepan) && ($simpananMasadepan < $hargaSimapan['min']))
                || (!empty($simpananMasadepan) && ($simpananMasadepan > $hargaSimapan['max']))
            ) {
                echo
                    "<script>
                alert('simpanan masa depan minimum transaksi " . $control->rupiah($hargaSimapan['min']) . " dan maximal " . $control->rupiah($hargaSimapan['max']) . "');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            if (!empty($angsuranPokok) && !empty($angsuranBunga)) {
                if ($angsuran->rowCount() == 0) {
                    $saldoKredit = $rowPinjaman['totalpinjam'] - $totalSetor;
                }

                if ($saldoKredit == 0) {
                    $statusAngsuran = 'nonaktif';
                }

                $query = $this->conn->query("UPDATE reminderpinjaman SET `status`='nonaktif' WHERE noangsuran='{$rowReminder['noangsuran']}' AND nokta='$nokta'");

                $query = $this->conn->query("INSERT INTO angsuran
                                        VALUES
                                        (
                                            ''
                                            ,'" . $rowPinjaman['idpinjaman'] . "'
                                            ,'$nota'
                                            ,'$idpetugas'
                                            ,'$nokta'
                                            ,'$angsuranBunga'
                                            ,'$totalBunga'
                                            ,'$angsuranPokok'
                                            ,'$totalPokok'
                                            ,'$saldoKredit'
                                            ,'$statusAngsuran'
                                            ,NOW()
                                        );");
            }

            if ((!empty($angsuranPokok) && !empty($angsuranBunga)) && ($saldoKredit == 0)) {
                $idpinjaman = $rowPinjaman['idpinjaman'];
                $query = $this->conn->query("UPDATE angsuran SET `status`='$statusAngsuran' WHERE idpinjaman='$idpinjaman';");
                $query = $this->conn->query("UPDATE pinjaman SET `status`='$statusAngsuran' WHERE idpinjaman='$idpinjaman';");
                $query = $this->conn->query("UPDATE reminderpinjaman SET `status`='nonaktif' WHERE `status`='aktif' AND nokta='$nokta'");
            }

            if (!empty($simpananSukarela)) {
                $idharga = $hargaSisukarela['idharga'];
                $query = $this->conn->query("INSERT INTO sisukarela
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$nokta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'$simpananSukarela'
                                            ,'0'
                                            ,'$saldoSisukarela'
                                            ,'aktif'
                                            ,NOW()
                                        );");
            }

            if (!empty($simpananWajib)) {
                $idharga = $hargaSiwajib['idharga'];
                $query = $this->conn->query("INSERT INTO siwapo
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$nokta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'debit siwajib'
                                            ,'$simpananWajib'
                                            ,'$saldoSiwajib'
                                            ,'aktif'
                                            ,NOW()
                                        );");
            }

            if (!empty($simpananPokok)) {
                $idharga = $hargaSipokok['idharga'];
                $query   = $this->conn->query("INSERT INTO siwapo
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$nokta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'debit sipokok'
                                            ,'$simpananPokok'
                                            ,'$saldoSipokok'
                                            ,'aktif'
                                            ,NOW()
                                        );");
            }

            if (!empty($simpananMasadepan)) {
                $noSimapan = $simapan['nomor'];
                $noSimapan++;
                $idharga   = $hargaSimapan['idharga'];

                $query = $this->conn->query("INSERT INTO simapan
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$nokta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'$noSimapan'
                                            ,'$simpananMasadepan'
                                            ,'aktif'
                                            ,NOW()
                                            ,''
                                        );");
            }

            if ($query->rowCount() > 0) {
                echo
                    "<script>
                document.location.href='add_transaksi_masuk.php?status=1&aksi=print&key=" . $control->hashMethod('encrypt', $nota) . "';
            </script>";
                die;
            } else {
                echo
                    "<script>
                document.location.href='add_transaksi_masuk.php?status=0&aksi=error';
            </script>";
                die;
            }

            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r("Koneksi atau query pada insert transaksi debit bermasalah : " . $e->getMessage() . "<br>");
            die;
        }
    }
    // ========================== 2.SIMPAN TRANSAKSI KREDIT ==========================
    public function insertTransaksiKredit($idpetugas, $noKta, $tSiwajib, $tSipokok, $tSisukarela, $tSianggota)
    {
        $control = new Control();
        try {
            $query = null;
            $messages = null;

            // cek minimum harga
            $hargaSiwajib       = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan wajib'")->fetch(PDO::FETCH_ASSOC);
            $hargaSipokok       = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan pokok'")->fetch(PDO::FETCH_ASSOC);
            $hargaSisukarela    = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan sukarela'")->fetch(PDO::FETCH_ASSOC);
            $hargaSianggota     = $this->conn->query("SELECT*FROM sianggota WHERE nokta='$noKta' AND `status`='aktif'")->fetch(PDO::FETCH_ASSOC);

            // ====================cek saldo====================
            // cek siwajib
            $querySiwajib    = $this->conn->query("SELECT SUM(subtotal) AS saldo FROM `siwapo` WHERE nokta='$noKta' AND `status`='aktif' AND keterangan LIKE '%siwajib%'");
            $rowSiwajib      = $querySiwajib->fetch(PDO::FETCH_ASSOC);
            $siwajib         = $rowSiwajib['saldo'];
            // cek sipokok
            $querySipokok    = $this->conn->query("SELECT SUM(subtotal) AS saldo FROM `siwapo` WHERE nokta='$noKta' AND `status`='aktif' AND keterangan LIKE '%sipokok%'");
            $rowSipokok      = $querySipokok->fetch(PDO::FETCH_ASSOC);
            $sipokok         = $rowSipokok['saldo'];
            // cek sianggota
            $querySianggota  = $this->conn->query("SELECT * FROM `sianggota` WHERE nokta='$noKta' AND `status`='aktif'");
            $rowSianggota    = $querySianggota->fetch(PDO::FETCH_ASSOC);
            $sianggota       = $rowSianggota['dana'] + $rowSianggota['totalbunga'];
            // cek sisukarela
            $querySisukarela = $this->conn->query("SELECT * FROM `sisukarela` WHERE nokta='$noKta' AND `status`='aktif' ORDER BY waktutransaksi DESC LIMIT 0,1");
            $rowSisukarela   = $querySisukarela->fetch(PDO::FETCH_ASSOC);
            $sisukarela      = $rowSisukarela['saldo'];
            // cek siwajib
            $querySaldoSimapan = $this->conn->query("SELECT * FROM `simapan` WHERE nokta='$noKta' AND `status`='aktif'");
            $rowSaldoSimapan  = $querySaldoSimapan->fetch(PDO::FETCH_ASSOC);

            // ====================Create NOTA====================
            // 1.Select max dari 5 string terakhir
            $bulanTahun = date('dmy');
            $queryNota = $this->conn->query("SELECT MAX(SUBSTRING(notransaksi, 7, 11)) AS nota FROM riwayattransaksi WHERE notransaksi LIKE '$bulanTahun%'");
            $row = $queryNota->fetch(PDO::FETCH_ASSOC);
            $maxNota = $row['nota'];
            // 2. pecah menjadi 2 bagian : 6 digit pertama bulan-tahun, 5 digit kedua nomor urut 
            $maxNota++;
            $nota = $bulanTahun . sprintf("%05d", $maxNota); //sprintf adalah zero padding berfungsi menampilkan angka 0 didepan

            // kondisi siwajib
            if ((!empty($tSiwajib)) && ($tSiwajib > $siwajib || $tSiwajib < $siwajib)) {
                echo
                    "<script>
                alert('saldo siwajib under/over limit');
                document.location.href='add_transaksi_keluar.php?key=$noKta';
            </script>";
                die;
            }

            if (!empty($tSiwajib) && empty($tSipokok)) {
                echo
                    "<script>
                alert('simpanan wajib dan pokok wajib di ambil bersamaan');
                document.location.href='add_transaksi_keluar.php?key=$noKta';
            </script>";
                die;
            }

            // kondisi sipokok
            if ((!empty($tSipokok)) && ($tSipokok > $sipokok || $tSipokok < $sipokok)) {
                echo
                    "<script>
                alert('saldo sipokok under/over limit');
                document.location.href='add_transaksi_keluar.php?key=$noKta';
            </script>";
                die;
            }

            if (empty($tSiwajib) && !empty($tSipokok)) {
                echo
                    "<script>
                alert('simpanan wajib dan pokok wajib di ambil bersamaan');
                document.location.href='add_transaksi_keluar.php?key=$noKta';
            </script>";
                die;
            }

            if ((!empty($tSiwajib) && !empty($tSipokok) && ($sianggota != 0 || $sisukarela != 0 || $querySaldoSimapan->rowCount() > 0))) {
                echo
                    "<script>
                alert('tidak dapat mengambil simpanan pokok dan wajib karena anggota masih memiliki simpanan yang lain');
                document.location.href='add_transaksi_keluar.php?key=$noKta';
            </script>";
                die;
            }

            // kondisi sisukarela
            if ((!empty($tSisukarela)) && ($tSisukarela > $sisukarela)) {
                echo
                    "<script>
                alert('saldo sisukarela under/over limit');
                document.location.href='add_transaksi_keluar.php?key=$noKta';
            </script>";
                die;
            }

            // kondisi sianggota
            if ((!empty($tSianggota) && $tSianggota > $sianggota) || (!empty($tSianggota) && $tSianggota < $sianggota)) {
                echo
                    "<script>
                alert('saldo sianggota under/over limit');
                document.location.href='add_transaksi_keluar.php?key=$noKta';
            </script>";
                die;
            }

            // ====================== INSERT SISUKARELA ======================
            if (!empty($tSisukarela)) {
                $idharga = $hargaSisukarela['idharga'];
                $saldoSisukarela = $sisukarela - $tSisukarela;

                $query = $this->conn->query("INSERT INTO sisukarela
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$noKta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'0'
                                            ,'$tSisukarela'
                                            ,'$saldoSisukarela'
                                            ,'aktif'
                                            ,NOW()
                                        );");

                if ($saldoSisukarela == 0) {
                    $query = $this->conn->query("UPDATE sisukarela SET `status`='nonaktif' WHERE `status`='aktif' AND nokta='$noKta';");
                }
            }

            if (!empty($tSiwajib)) {
                $idharga = $hargaSiwajib['idharga'];

                $query = $this->conn->query("INSERT INTO siwapo
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$noKta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'kredit siwajib'
                                            ,'-$tSiwajib'
                                            ,'0'
                                            ,'aktif'
                                            ,NOW()
                                        );");

                // cek siwajib
                $querySiwajib    = $this->conn->query("SELECT SUM(subtotal) AS saldo FROM `siwapo` WHERE nokta='$noKta' AND `status`='aktif' AND keterangan LIKE '%siwajib%'");
                $rowSiwajib      = $querySiwajib->fetch(PDO::FETCH_ASSOC);
                $saldoSiwajib    = $rowSiwajib['saldo'];
            }

            if (!empty($tSipokok)) {
                $idharga = $hargaSipokok['idharga'];
                $query   = $this->conn->query("INSERT INTO siwapo
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$noKta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'kredit sipokok'
                                            ,'-$tSipokok'
                                            ,'0'
                                            ,'aktif'
                                            ,NOW()
                                        );");

                // cek sipokok
                $querySipokok    = $this->conn->query("SELECT SUM(subtotal) AS saldo FROM `siwapo` WHERE nokta='$noKta' AND `status`='aktif' AND keterangan LIKE '%sipokok%'");
                $rowSipokok      = $querySipokok->fetch(PDO::FETCH_ASSOC);
                $saldoSipokok    = $rowSipokok['saldo'];

                if (($saldoSipokok == 0) && ($saldoSiwajib == 0)) {
                    $query = $this->conn->query("UPDATE anggota SET `status`='nonaktif' WHERE `status`='aktif' AND nokta='$noKta';");
                    $query = $this->conn->query("UPDATE siwapo SET `status`='nonaktif' WHERE `status`='aktif' AND nokta='$noKta';");
                }
            }

            if (!empty($tSianggota)) {
                $query = $this->conn->query("UPDATE sianggota SET `status`='nonaktif' WHERE `status`='aktif' AND nokta='$noKta';");

                $idHargaSianggota = $rowSianggota['idharga'];
                $idBungaSianggota = $rowSianggota['idbunga'];
                $tglMasukSianggota = $rowSianggota['tgl_masuk'];
                $tglKeluarSianggota = $rowSianggota['tgl_keluar'];
                $bungaSianggota = $rowSianggota['bunga'];
                $waktuTransaksiSianggota = $rowSianggota['waktutransaksi'];

                $query = $this->conn->query("INSERT INTO sianggota
                VALUES
                (
                    ''
                    ,'$nota'
                    ,'$noKta'
                    ,'$idHargaSianggota'
                    ,'$idpetugas'
                    ,'$idBungaSianggota'
                    ,'$tglMasukSianggota'
                    ,'$tglKeluarSianggota'
                    ,'0'
                    ,'$bungaSianggota'
                    ,'0'
                    ,'nonaktif'
                    ,'$waktuTransaksiSianggota'
                );");
            }

            if ($query->rowCount() > 0) {
                echo
                    "<script>
                document.location.href='add_transaksi_keluar.php?status=1&aksi=print&nokta=" . $control->hashMethod('encrypt', $nota) . "';
            </script>";
                die;
            } else {
                echo
                    "<script>
                document.location.href='add_transaksi_keluar.php?status=0&aksi=error';
            </script>";
                die;
            }

            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r("Koneksi atau query pada insert transaksi keluar bermasalah : " . $e->getMessage() . "<br>");
            die;
        }
    }
    // ========================== 3.SIMPAN TRANSAKSI PINJAMAN ==========================
    public function insertTransaksiPinjaman($idpetugas, $nokta, $jangkaWaktu, $jumlah, $tglMulai, $keterangan)
    {
        $control = new Control();
        try {
            $query    = null;
            $jangka   = explode(" ", $jangkaWaktu);
            $ketWaktu = null;
            $tglAkhir = null;
            $bunga    = null;
            $pokok    = null;

            // ====================Create NOTA====================
            $bulanTahun = date('dmy'); // 1.Select max dari 5 string terakhir
            $queryNota = $this->conn->query("SELECT MAX(SUBSTRING(notransaksi, 7, 11)) AS nota FROM riwayattransaksi WHERE notransaksi LIKE '$bulanTahun%'");
            $row = $queryNota->fetch(PDO::FETCH_ASSOC);
            $maxNota = $row['nota'];
            $maxNota++; // 2. pecah menjadi 2 bagian : 6 digit pertama bulan-tahun, 5 digit kedua nomor urut 
            $nota = $bulanTahun . sprintf("%05d", $maxNota); //sprintf adalah zero padding berfungsi menampilkan angka 0 didepan

            if ($jumlah >= 2000000) {
                $ketWaktu = "2 tahun";
            }

            if ($jumlah < 2000000) {
                $ketWaktu = "10 bulan";
            }
            // ==================== Master Bunga ====================
            $masterBunga    = $this->conn->query("SELECT*FROM masterbunga WHERE keterangan='$ketWaktu'")->fetch(PDO::FETCH_ASSOC);
            // ==================== CEK NOKTA ====================
            $queryNokta     = $this->conn->query("SELECT*FROM anggota WHERE nokta='$nokta' AND `status`='aktif'");
            $rowNokta       = $queryNokta->fetch(PDO::FETCH_ASSOC);
            // ==================== Master Harga ====================
            $masterHarga    = $this->conn->query("SELECT*FROM masterharga WHERE nama='pinjaman'")->fetch(PDO::FETCH_ASSOC);
            // ==================== pinjaman ====================
            $pinjaman       = $this->conn->query("SELECT*FROM pinjaman WHERE nokta='$nokta' AND `status`='aktif'");

            // cek anggota exist or not
            if ($queryNokta->rowCount() < 1) {
                echo
                    "<script>
                alert('nokta anda tidak terdaftar');
                window.history.back();
            </script>";
                die;
            }

            // cek pinjaman exist or not
            if ($pinjaman->rowCount() > 0) {
                echo
                    "<script>
                alert('anda memiliki 1 pinjaman yang aktif, silahkan lunaskan dan kembali untuk meminjam');
                window.history.back();
            </script>";
                die;
            }

            // cek tanggal mulai
            $dateNow = date("Y-m-d");
            if ($tglMulai < $dateNow) {
                echo
                    "<script>
                alert('tanggal kadaluarsa');
                window.history.back();
            </script>";
                die;
            }

            // cek limit dana
            if (($jumlah > $masterHarga['max']) || ($jumlah < $masterHarga['min'])) {
                echo
                    "<script>
                alert('pengajuan pinjaman min " . $control->rupiah($masterHarga['min']) . " dan max " . $control->rupiah($masterHarga['max']) . "');
                window.history.back();
            </script>";
                die;
            }

            // cek jangka waktu
            if (($jumlah < 2000000) && ($jangka[1] == "month") && ($jangka[0] > 10)) {
                echo
                    "<script>
                        alert('jangka waktu tidak sesuai aturan');
                        window.history.back();
                    </script>";
                die;
            }

            if (($jumlah >= 2000000) && ($jangka[1] == "month") && ($jangka[0] > 24)) {
                echo
                    "<script>
                        alert('jangka waktu tidak sesuai aturan');
                        window.history.back();
                    </script>";
                die;
            }

            if (($jumlah >= 2000000) && ($jangka[1] == "year") && ($jangka[0] > 2)) {
                echo
                    "<script>
                        alert('jangka waktu tidak sesuai aturan');
                        window.history.back();
                    </script>";
                die;
            }

            // akumulasikan tanggal akhir
            $jangkaLength = ($jangka[1] == "year" ? $jangka[0] * 12 : $jangka[0]);

            $tglAkhir = date('Y-m-d', strtotime('+' . $jangka[0] . ' ' . $jangka[1] . '', strtotime($tglMulai)));
            $bunga = ceil(($jumlah * $masterBunga['total']) / $jangkaLength);
            $pokok = ceil($jumlah / $jangkaLength);

            // if($jangka[1] == 'year'){
            //     $tglAkhir = date('Y-m-d', strtotime('+'.$jangka[0].' '.$jangka[1].'', strtotime($tglMulai)));
            //     $bunga = ceil(($jumlah*$masterBunga['total'])/$jangka[0]);
            //     $pokok = $jumlah/$jangka[0];
            // }
            // if($jangka[1] == 'month'){
            //     $tglAkhir = date('Y-m-d', strtotime('+'.$jangka[0].' '.$jangka[1].'', strtotime($tglMulai)));
            //     $bunga = ceil(($jumlah*$masterBunga['total'])/$jangka[0]);
            //     $pokok = $jumlah/$jangka[0];
            // }
            // if($jangka[1] == 'week'){
            //     $tglAkhir = date('Y-m-d', strtotime('+'.$jangka[0].' '.$jangka[1].'', strtotime($tglMulai)));
            //     $bunga = ceil(($jumlah*$masterBunga['total'])/$jangka[0]);
            //     $pokok = $jumlah/$jangka[0];
            // }

            $jumSetor = $pokok + $bunga;
            $idharga = $masterHarga['idharga'];
            $idbunga = $masterBunga['idbunga'];
            $stringReminder = "INSERT INTO reminderpinjaman VALUES ";

            // echo
            //     $idpetugas . " idpetugas<br>" .
            //         $tglAkhir . " tglAkhir<br>" .
            //         $bunga . " bunga<br>" .
            //         $pokok . " pokok<br>" .
            //         $jumSetor . " jumSetor<br>" .
            //         $idharga . " idharga<br>" .
            //         $idbunga . " idbunga<br>" .
            //         $nokta . " nokta<br>" .
            //         $jangkaWaktu . " jangkaWaktu<br>" .
            //         $tglMulai . " tglMulai<br>" .
            //         $keterangan . " keterangan<br>" .
            //         $nota . " nota<br>" .
            //         $tglMulai . " tglMulai<br>" .
            //         $jumlah . " jumlah<br>" .
            //         $jangka[1] . " jumlah<br>" .
            //         $rowNokta['nama'] . " nama<br>";

            // die;

            $jangkaKet = ($jangka[1] == "year" ? "month" : $jangka[1]);
            for ($i = 1; $i <= $jangkaLength; $i++) {
                $selisiWaktu = date('Y-m-d', strtotime('+' . $i . ' ' . $jangkaKet . '', strtotime($tglMulai)));
                $stringReminder .= "('','$nokta','{$rowNokta['nama']}','$nota','$idpetugas','aktif','$i','$selisiWaktu')";
                if ($i != $jangkaLength) {
                    $stringReminder .= ",";
                }
            }
            // echo $stringReminder;die;
            $query = $this->conn->query("INSERT INTO pinjaman
                                    VALUES
                                    (
                                        ''
                                        ,'$nota'
                                        ,'$nokta'
                                        ,'$idharga'
                                        ,'$idpetugas'
                                        ,'$idbunga'
                                        ,'$jumlah'
                                        ,'$jangkaWaktu'
                                        ,'$keterangan'
                                        ,'$pokok'
                                        ,'$bunga'
                                        ,'$jumSetor'
                                        ,'$tglMulai'
                                        ,'$tglAkhir'
                                        ,'aktif'
                                        ,NOW()
                                    );");

            if ($query->rowCount() > 0) {
                $query = $this->conn->query($stringReminder);
                echo
                    "<script>
                document.location.href='add_transaksi_pinjaman.php?status=1&aksi=print&key=" . $control->hashMethod('encrypt', $nota) . "';
            </script>";
                die;
            } else {
                echo
                    "<script>
                document.location.href='add_transaksi_pinjaman.php?status=0&aksi=error';
            </script>";
                die;
            }

            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r("Koneksi atau query pada insert transaksi pinjaman bermasalah : " . $e->getMessage() . "<br>");
            die;
        }
    }
    // ========================== 4.SIMPAN TRANSAKSI ANGSURAN ==========================
    // ========================== 5.SIMPAN TRANSAKSI ANGGOTA ==========================
    public function inserTransaksiSianggota($idpetugas, $nokta, $tglMasuk, $idBunga, $jumlah)
    {

        $control = new Control();
        try {
            $query = null;

            // ====================Create NOTA====================
            $bulanTahun = date('dmy'); // 1.Select max dari 5 string terakhir
            $queryNota = $this->conn->query("SELECT MAX(SUBSTRING(notransaksi, 7, 11)) AS nota FROM riwayattransaksi WHERE notransaksi LIKE '$bulanTahun%'");
            $row = $queryNota->fetch(PDO::FETCH_ASSOC);
            $maxNota = $row['nota'];
            $maxNota++; // 2. pecah menjadi 2 bagian : 6 digit pertama bulan-tahun, 5 digit kedua nomor urut 
            $nota = $bulanTahun . sprintf("%05d", $maxNota); //sprintf adalah zero padding berfungsi menampilkan angka 0 didepan

            // ==================== CEK NOKTA ====================
            $queryNokta     = $this->conn->query("SELECT*FROM anggota WHERE nokta='$nokta' AND `status`='aktif'");
            // ==================== Master Bunga ====================
            $masterBunga    = $this->conn->query("SELECT*FROM masterbunga WHERE idbunga='$idBunga'")->fetch(PDO::FETCH_ASSOC);
            // ==================== Master Harga ====================
            $masterHarga    = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan anggota'")->fetch(PDO::FETCH_ASSOC);
            // ==================== Sianggota ====================
            $sianggota      = $this->conn->query("SELECT*FROM sianggota WHERE nokta='$nokta' AND `status`='aktif'");

            // cek anggota exist or not
            if ($queryNokta->rowCount() < 1) {
                echo
                    "<script>
                alert('nokta anda tidak terdaftar');
                window.history.back();
            </script>";
                die;
            }

            // cek sianggota exist or not
            if ($sianggota->rowCount() > 0) {
                echo
                    "<script>
                alert('anda memiliki 1 simpanan anggota yang aktif');
                window.history.back();
            </script>";
                die;
            }

            // cek tanggal mulai
            $dateNow = date("Y-m-d");
            if ($tglMasuk < $dateNow) {
                echo
                    "<script>
                alert('tanggal kadaluarsa');
                window.history.back();
            </script>";
                die;
            }

            // cek limit dana
            if (($jumlah > $masterHarga['max']) || ($jumlah < $masterHarga['min'])) {
                echo
                    "<script>
                alert('simpanan anggota min " . $control->rupiah($masterHarga['min']) . " dan max " . $control->rupiah($masterHarga['max']) . "');
                window.history.back();
            </script>";
                die;
            }

            // akumulasikan tanggal akhir
            $tglAkhir = null;
            $bunga = null;
            $keterangan = explode(" ", $masterBunga['keterangan']);
            if ($keterangan[1] == 'tahun') {
                $tglAkhir = date('Y-m-d', strtotime('+' . $keterangan[0] . ' year', strtotime($tglMasuk)));
                $bunga = $control->pembulatan(($jumlah * $masterBunga['total']) / 12);
                //$bunga = ceil(($jumlah*$masterBunga['total'])/12);
            }
            if ($keterangan[1] == 'bulan') {
                $tglAkhir = date('Y-m-d', strtotime('+' . $keterangan[0] . ' month', strtotime($tglMasuk)));
                $bunga = $control->pembulatan(($jumlah * $masterBunga['total']) / 12);
                //$bunga = ceil(($jumlah*$masterBunga['total'])/12);
            }
            if ($keterangan[1] == 'minggu') {
                $tglAkhir = date('Y-m-d', strtotime('+' . $keterangan[0] . ' week', strtotime($tglMasuk)));
                $bunga = $control->pembulatan(($jumlah * $masterBunga['total']) / 12);
                //$bunga = ceil(($jumlah*$masterBunga['total'])/12);
            }

            $idharga = $masterHarga['idharga'];
            $idbunga = $masterBunga['idbunga'];

            $query = $this->conn->query("INSERT INTO sianggota
                                    VALUES
                                    (
                                        ''
                                        ,'$nota'
                                        ,'$nokta'
                                        ,'$idharga'
                                        ,'$idpetugas'
                                        ,'$idbunga'
                                        ,'$tglMasuk'
                                        ,'$tglAkhir'
                                        ,'$jumlah'
                                        ," . $masterBunga['total'] . "
                                        ,'$bunga'
                                        ,'aktif'
                                        ,NOW()
                                    );");

            if ($query->rowCount() > 0) {
                echo
                    "<script>
                document.location.href='add_transaksi_sianggota.php?status=1&aksi=print&key=" . $control->hashMethod('encrypt', $nota) . "';
            </script>";
                die;
            } else {
                echo
                    "<script>
                document.location.href='add_transaksi_sianggota.php?status=0&aksi=error';
            </script>";
                die;
            }

            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r("Koneksi atau query pada insert transaksi sianggota bermasalah : " . $e->getMessage() . "<br>");
            die;
        }
    }
    // ========================== 6.SIMPAN TRANSAKSI SIMAPAN ==========================
    public function insertTransaksiSimapan($idpetugas, $noktaPembeli, $nominalSimapan, $nokta, $noKartu)
    {
        $control = new Control();
        try {
            $query = null;

            // ====================Create NOTA====================
            $bulanTahun = date('dmy'); // 1.Select max dari 5 string terakhir
            $queryNota = $this->conn->query("SELECT MAX(SUBSTRING(notransaksi, 7, 11)) AS nota FROM riwayattransaksi WHERE notransaksi LIKE '$bulanTahun%'");
            $row = $queryNota->fetch(PDO::FETCH_ASSOC);
            $maxNota = $row['nota'];
            $maxNota++; // 2. pecah menjadi 2 bagian : 6 digit pertama bulan-tahun, 5 digit kedua nomor urut 
            $nota = $bulanTahun . sprintf("%05d", $maxNota); //sprintf adalah zero padding berfungsi menampilkan angka 0 didepan

            // ====================CEK NOKTA====================
            $penjual           = $this->conn->query("SELECT*FROM simapan WHERE nokta='$nokta' AND nokartu='$noKartu' AND `status`='aktif'");
            $rowPenjual        = $penjual->fetch(PDO::FETCH_ASSOC);
            $pembeli           = $this->conn->query("SELECT*FROM anggota WHERE nokta='$noktaPembeli'");
            $simapanPenjual    = $this->conn->query("SELECT*FROM simapan WHERE nokta='$nokta' AND `status`='aktif'");
            $rowSipenjual      = $simapanPenjual->fetch(PDO::FETCH_ASSOC);
            $simapanPembeli    = $this->conn->query("SELECT MAX(nokartu) AS nomor, simapan.* FROM simapan WHERE nokta='$noktaPembeli'")->fetch(PDO::FETCH_ASSOC);
            $hargaSimapan      = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan masadepan'")->fetch(PDO::FETCH_ASSOC);

            if ($penjual->rowCount() == 0) {
                echo
                    "<script>
                alert('NOKTA dan NO Kartu Penjual tidak ditemukan !!!');
                document.location.href='add_transaksi_simapan.php';
            </script>";
                die;
            }
            if ($pembeli->rowCount() == 0) {
                echo
                    "<script>
                alert('NOKTA Pembeli tidak ditemukan !!!');
                document.location.href='add_transaksi_simapan.php';
            </script>";
                die;
            }
            if ($nokta == $noktaPembeli) {
                echo
                    "<script>
                alert('NOKTA Pembeli tidak boleh sama dengan NOKTA Penjual');
                document.location.href='add_transaksi_simapan.php';
            </script>";
                die;
            }
            if ($simapanPenjual->rowCount() == 0) {
                echo
                    "<script>
                alert('NOKTA Pembeli tidak ditemukan !!!');
                document.location.href='add_transaksi_simapan.php';
            </script>";
                die;
            }

            if ($rowPenjual['nilai'] != $nominalSimapan) {
                echo
                    "<script>
                alert('saldo simapan tidak sesuai');
                document.location.href='add_transaksi_simapan.php';
            </script>";
                die;
            }
            $noSimapan = $simapanPembeli['nomor'];
            $noSimapan++;
            $idharga   = $hargaSimapan['idharga'];

            $query = $this->conn->query("INSERT INTO simapan
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$noktaPembeli'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'$noSimapan'
                                            ,'$nominalSimapan'
                                            ,'aktif'
                                            ,NOW()
                                            ,''
                                        );");

            if ($query->rowCount() > 0) {
                $nokartu = $rowSipenjual['nokartu'];
                $query = $this->conn->query("UPDATE simapan SET `status`='nonaktif', waktutransaksijual=NOW() WHERE nokta='$nokta' AND nokartu='$noKartu';");
                echo
                    "<script>
                document.location.href='add_transaksi_simapan.php?status=1&aksi=print&key=" . $control->hashMethod('encrypt', $nota) . "';
            </script>";
                die;
            } else {
                echo
                    "<script>
                document.location.href='add_transaksi_simapan.php?status=0&aksi=error';
            </script>";
                die;
            }

            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r("Koneksi atau query pada insert transaksi simapan bermasalah : " . $e->getMessage() . "<br>");
            die;
        }
    }
    // ========================== 7.SIMPAN PAYMENT ==========================
    public function insertPayment($data)
    {
        $control = new Control();
        try {
            $query = null;
            $string = "INSERT INTO payment VALUES ";
            $total = 0;
            $nokta = $_SESSION['key'];

            // ====================SELECT PETUGAS====================
            $queryPetugas = $this->conn->query("SELECT * FROM petugas LIMIT 0,1")->fetch(PDO::FETCH_ASSOC);
            // ====================SELECT ANGGOTA====================
            $queryAnggota = $this->conn->query("SELECT * FROM anggota WHERE nokta='$nokta'")->fetch(PDO::FETCH_ASSOC);

            // ====================Create NOTA====================
            $bulanTahun = date('dmy'); // 1.Select max dari 5 string terakhir
            $maxNota = null;
            $queryNota = $this->conn->query("SELECT MAX(SUBSTRING(notransaksi, 7, 11)) AS nota FROM payment WHERE notransaksi LIKE '$bulanTahun%'");
            $row = $queryNota->fetch(PDO::FETCH_ASSOC);

            // nota riwayat
            $queryNotaR = $this->conn->query("SELECT MAX(SUBSTRING(notransaksi, 7, 11)) AS nota FROM riwayattransaksi WHERE notransaksi LIKE '$bulanTahun%'");
            $rowR = $queryNotaR->fetch(PDO::FETCH_ASSOC);
            if ($row['nota'] > $rowR['nota']) {
                $maxNota = $row['nota'];
            } else {
                $maxNota = $rowR['nota'];
            }
            $maxNota++; // 2. pecah menjadi 2 bagian : 6 digit pertama bulan-tahun, 5 digit kedua nomor urut 
            $nota = $bulanTahun . sprintf("%05d", $maxNota); //sprintf adalah zero padding berfungsi menampilkan angka 0 didepan
            // $nota += 1;
            // $angsuranPokok = null;
            // $angsuranBunga = null;
            // $simpananSukarela = null;
            // $simpananWajib = null;
            // $simpananPokok = null;
            // $simpananMasadepan = null;

            foreach ($_POST as $val) {
                $data = explode("-", $val);
                if (!empty($val) && $data[0] != 'total') {
                    // $angsuranPokok .= ($data[0] == 'angsuranPokok' ? $data[1] : '');
                    // $angsuranBunga .= ($data[0] == 'angsuranBunga' ? $data[1] : '');
                    // $simpananSukarela .= ($data[0] == 'simpananSukarela' ? $data[1] : '');
                    // $simpananWajib .= ($data[0] == 'simpananWajib' ? $data[1] : '');
                    // $simpananPokok .= ($data[0] == 'simpananPokok' ? $data[1] : '');
                    // $simpananMasadepan .= ($data[0] == 'simpananMasadepan' ? $data[1] : '');
                    $string .= "('','$nokta','{$queryPetugas['idpetugas']}','$nota','{$data[0]}','{$data[1]}',NOW(),'PROCESS',''),";
                }
                if ($data[0] == 'total') {
                    $total = $data[1];
                }
            }

            $string = rtrim($string, ', ');

            // params middtrans
            $nota = $control->hashMethod('encrypt', $nota);
            $firstName = $control->hashMethod('encrypt', ucwords(explode(' ', $queryAnggota['nama'])[0]));
            $total = $control->hashMethod('encrypt', $total);

            // echo $nokta."<br>";
            // echo $angsuranPokok."<br>";
            // echo $angsuranBunga."<br>";
            // echo $simpananSukarela."<br>";
            // echo $simpananWajib."<br>";
            // echo $simpananPokok."<br>";
            // echo $simpananMasadepan."<br>";
            // var_dump($_POST); die;

            $query = $this->conn->query($string);

            if ($query->rowCount() > 0) {
                echo
                    "<script>
                    document.location.href = '../payments/midtrans.php?id=$nota&name=$firstName&value=$total';
                </script>";
                die;
            } else {
                echo
                    "<script>
                    document.location.href='../../index.php';
                </script>";
                die;
            }

            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r("Koneksi atau query pada insert payment bermasalah : " . $e->getMessage() . "<br>");
            die;
        }
    }
    // ========================== 8.UPDATE PAYMENT ==========================
    public function updatePaymentStatus($order_id, $status, $type)
    {
        $control = new Control();
        try {
            $query = null;
            // ====================SELECT TRANSAKSI SELECT TRANSAKSI====================
            $queryPayment = "SELECT * FROM payment WHERE notransaksi='$order_id'";

            $change = $this->conn->query($queryPayment);
            $rows = [];
            while ($row = $change->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }

            $angsuranPokok = null;
            $angsuranBunga = null;
            $simpananSukarela = null;
            $simpananWajib = null;
            $simpananPokok = null;
            $simpananMasadepan = null;
            $nota = null;
            $nokta = null;
            $idpetugas = null;

            foreach ($rows as $key) {
                if ($key['namatransaksi'] == "angsuranPokok") {
                    $angsuranPokok = $key['total'];
                }
                if ($key['namatransaksi'] == "angsuranBunga") {
                    $angsuranBunga = $key['total'];
                }
                if ($key['namatransaksi'] == "simpananSukarela") {
                    $simpananSukarela = $key['total'];
                }
                if ($key['namatransaksi'] == "simpananWajib") {
                    $simpananWajib = $key['total'];
                }
                if ($key['namatransaksi'] == "simpananPokok") {
                    $simpananPokok = $key['total'];
                }
                if ($key['namatransaksi'] == "simpananMasadepan") {
                    $simpananMasadepan = $key['total'];
                }
                $nota = $key['notransaksi'];
                $nokta = $key['nokta'];
                $idpetugas = $key['idpetugas'];
            }

            // die;
            $dataAnggota = $this->conn->query("SELECT*FROM anggota WHERE nokta='$nokta'")->fetch(PDO::FETCH_ASSOC);
            $noHp = $dataAnggota['notelepon'];
            $pesan = "";

            $query = $this->conn->query("UPDATE payment SET paymentstatus='$status', paymenttype='$type' WHERE notransaksi='$order_id'");

            if ($status == "SUCCESS") {
                $pesan = "Selamat, Transaksi anda no.$nota berhasil diproses ~Koperasi BKM Sinduadi~";
            }
            //  else {
            //     $pesan = "Kpd Pelanggan yth. Transaksi anda no.$nota gagal diproses. silahkan hubungi admin untuk proses selanjutnya. ~Koperasi BKM Sinduadi~";
            // }
            //if($status == "PENDING"){
            //$pesan = "Maaf, Transaksi anda no.$nota belum selesai diproses. silahkan segera melakukan pembayaran ~Koperasi BKM Sinduadi~";
            //}
            //if($status == "EXPIRE"){
            //$pesan = "Maaf, Transaksi anda no.$nota gagal diproses ~Koperasi BKM Sinduadi~";
            //}
            //if($status == "FAILED"){
            //$pesan = "Maaf, Transaksi anda no.$nota gagal diproses. silahkan menghubungi admin ~Koperasi BKM Sinduadi~";
            //}

            if ($status == "SUCCESS") {
                $this->sendMessageRegular($noHp, $pesan);
                $this->insertTransaksiDebitPayment($nota, $idpetugas, $nokta, $angsuranPokok, $angsuranBunga, $simpananSukarela, $simpananWajib, $simpananPokok, $simpananMasadepan);
            }
            // Error mode
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r("Koneksi atau query pada update status payment bermasalah : " . $e->getMessage() . "<br>");
            die;
        }
    }
    // ========================== 8.UPDATE PAYMENT ==========================
    public function insertTransaksiDebitPayment($nota, $idpetugas, $nokta, $angsuranPokok, $angsuranBunga, $simpananSukarela, $simpananWajib, $simpananPokok, $simpananMasadepan)
    {
        $control = new Control();
        try {
            $query = null;
            $saldoKredit = 0;
            // cek minimum harga
            $hargaSiwajib       = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan wajib'")->fetch(PDO::FETCH_ASSOC);
            $hargaSipokok       = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan pokok'")->fetch(PDO::FETCH_ASSOC);
            $hargaSisukarela    = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan sukarela'")->fetch(PDO::FETCH_ASSOC);
            $hargaSimapan       = $this->conn->query("SELECT*FROM masterharga WHERE nama='simpanan masadepan'")->fetch(PDO::FETCH_ASSOC);
            $hargaPinjaman      = $this->conn->query("SELECT*FROM pinjaman WHERE nokta='$nokta' AND `status`='aktif'")->fetch(PDO::FETCH_ASSOC);
            $hargaSianggota     = $this->conn->query("SELECT*FROM sianggota WHERE nokta='$nokta' AND `status`='aktif'")->fetch(PDO::FETCH_ASSOC);

            // cek saldo
            // =======================SISUKARELA=======================
            $sisukarela         = $this->conn->query("SELECT * FROM `sisukarela` WHERE nokta='$nokta' AND `status`='aktif' ORDER BY waktutransaksi DESC LIMIT 0,1");
            $rowSisukarela      = $sisukarela->fetch(PDO::FETCH_ASSOC);
            $saldoSisukarela    = null;
            if (!empty($simpananSukarela)) {
                $saldoSisukarela = $rowSisukarela['saldo'] + $simpananSukarela;
            }
            // =======================PINJAMAN=======================
            $pinjaman           = $this->conn->query("SELECT*FROM pinjaman WHERE nokta='$nokta' AND `status`='aktif'");
            $rowPinjaman        = $pinjaman->fetch(PDO::FETCH_ASSOC);
            // =======================ANGSURAN=======================
            $angsuran           = $this->conn->query("SELECT*FROM angsuran WHERE nokta='$nokta' AND `status`='aktif' ORDER BY idangsuran ASC");
            $rowAngsuran        = $angsuran->fetch(PDO::FETCH_ASSOC);
            $totalBunga         = null;
            $totalPokok         = null;
            $totalSetor         = null;
            $saldoKredit        = null;
            $statusAngsuran     = "aktif";
            // =======================REMINDER=======================
            $reminder           = $this->conn->query("SELECT MIN(noangsuran) AS noangsuran FROM `reminderpinjaman` WHERE nokta='$nokta' AND status='aktif'");
            $rowReminder        = $reminder->fetch(PDO::FETCH_ASSOC);

            if (!empty($angsuranPokok) && !empty($angsuranBunga)) {
                $totalBunga     = $rowAngsuran['totalbunga'] + $angsuranBunga;
                $totalPokok     = $rowAngsuran['totalpokok'] + $angsuranPokok;
                $totalSetor     = $angsuranBunga + $angsuranPokok;
                $saldoKredit    = $rowAngsuran['saldokredit'] - $totalSetor;
            }

            // =======================SIWAJIB=======================
            $siwajib            = $this->conn->query("SELECT SUM(subtotal) AS saldo FROM siwapo WHERE nokta='$nokta' AND `status`='aktif' AND keterangan='debit siwajib' ORDER BY idsiwapo ASC")->fetch(PDO::FETCH_ASSOC);
            $saldoSiwajib       = null;
            if (!empty($simpananWajib)) {
                $saldoSiwajib   = $siwajib['saldo'] + $simpananWajib;
            }
            // =======================SIPOKOK=======================
            $sipokok            = $this->conn->query("SELECT SUM(subtotal) AS saldo FROM siwapo WHERE nokta='$nokta' AND `status`='aktif' AND keterangan='debit sipokok' ORDER BY idsiwapo ASC")->fetch(PDO::FETCH_ASSOC);
            $saldoSipokok       = null;
            if (!empty($simpananPokok)) {
                $saldoSipokok   = $sipokok['saldo'] + $simpananPokok;
            }
            // ====================SIMAPAN====================
            $simapan            = $this->conn->query("SELECT MAX(nokartu) AS nomor, simapan.* FROM simapan WHERE nokta='$nokta'")->fetch(PDO::FETCH_ASSOC);

            // kondisi transaksi angsuran
            if ((!empty($angsuranPokok) && empty($angsuranBunga)) || (empty($angsuranPokok) && !empty($angsuranBunga))) {
                echo
                    "<script>
                alert('angsuran pokok dan angsuran bunga wajib dibayar bersamaan');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            if ((!empty($angsuranPokok) && !empty($angsuranBunga)) && ($pinjaman->rowCount() < 1)) {
                echo
                    "<script>
                alert('anda tidak memiliki pinjaman yang aktif');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            if (($pinjaman->rowCount() > 0) && (!empty($angsuranPokok) && !empty($angsuranBunga)) && (($angsuranPokok < $rowPinjaman['t_pokok']) || ($angsuranBunga < $rowPinjaman['t_bunga']))) {
                echo
                    "<script>
                alert('angsuran pokok anda minimal " . $control->rupiah($rowPinjaman['t_pokok']) . " dan angsuran bunga " . $control->rupiah($rowPinjaman['t_pokok']) . "');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            if (($pinjaman->rowCount() > 0) && ($angsuran->rowCount() > 0) && (!empty($angsuranPokok) && !empty($angsuranBunga)) && ($saldoKredit < 0)) {
                echo
                    "<script>
                alert('saldo kredit anda sisa " . $control->rupiah($rowAngsuran['saldokredit']) . " uang anda kebanyakan');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            // kondisi siwajib
            if ((!empty($simpananWajib) && ($simpananWajib < $hargaSiwajib['min']))
                || (!empty($simpananWajib) && ($simpananWajib > $hargaSiwajib['max']))
                || (!empty($simpananWajib) && ($saldoSiwajib > $hargaSiwajib['max']))
            ) {
                echo
                    "<script>
                alert('simpanan wajib minimum transaksi " . $control->rupiah($hargaSiwajib['min']) . " dan maximal " . $control->rupiah($hargaSiwajib['max']) . "');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            // kondisi sipokok
            if ((!empty($simpananPokok) && ($simpananPokok < $hargaSipokok['min']))
                || (!empty($simpananPokok) && ($simpananPokok > $hargaSipokok['max']))
                || (!empty($simpananPokok) && ($saldoSipokok > $hargaSipokok['max']))
            ) {
                echo
                    "<script>
                alert('simpanan pokok minimum transaksi " . $control->rupiah($hargaSipokok['min']) . " dan maximal " . $control->rupiah($hargaSipokok['max']) . "');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            // kondisi sisukarela
            if ((!empty($simpananSukarela) && (($sisukarela->rowCount() < 1) && ($simpananSukarela < $hargaSisukarela['min'])))
                || (!empty($simpananSukarela) && ($simpananSukarela > $hargaSisukarela['max']))
                || (!empty($simpananSukarela) && ($saldoSisukarela > $hargaSisukarela['max']))
            ) {
                echo
                    "<script>
                alert('pengguna baru simpanan sukarela minimum transaksi " . $control->rupiah($hargaSisukarela['min']) . " dan maximal " . $control->rupiah($hargaSisukarela['max']) . "');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            // kondisi simapan
            if ((!empty($simpananMasadepan) && ($simpananMasadepan < $hargaSimapan['min']))
                || (!empty($simpananMasadepan) && ($simpananMasadepan > $hargaSimapan['max']))
            ) {
                echo
                    "<script>
                alert('simpanan masa depan minimum transaksi " . $control->rupiah($hargaSimapan['min']) . " dan maximal " . $control->rupiah($hargaSimapan['max']) . "');
                document.location.href='add_transaksi_masuk.php?noKta=$nokta';
            </script>";
                die;
            }

            if (!empty($angsuranPokok) && !empty($angsuranBunga)) {
                if ($angsuran->rowCount() == 0) {
                    $saldoKredit = $rowPinjaman['totalpinjam'] - $totalSetor;
                }

                if ($saldoKredit == 0) {
                    $statusAngsuran = 'nonaktif';
                }

                $query = $this->conn->query("UPDATE reminderpinjaman SET `status`='nonaktif' WHERE noangsuran='{$rowReminder['noangsuran']}' AND nokta='$nokta'");

                $query = $this->conn->query("INSERT INTO angsuran
                                        VALUES
                                        (
                                            ''
                                            ,'" . $rowPinjaman['idpinjaman'] . "'
                                            ,'$nota'
                                            ,'$idpetugas'
                                            ,'$nokta'
                                            ,'$angsuranBunga'
                                            ,'$totalBunga'
                                            ,'$angsuranPokok'
                                            ,'$totalPokok'
                                            ,'$saldoKredit'
                                            ,'$statusAngsuran'
                                            ,NOW()
                                        );");
            }

            if ((!empty($angsuranPokok) && !empty($angsuranBunga)) && ($saldoKredit == 0)) {
                $idpinjaman = $rowPinjaman['idpinjaman'];
                $query = $this->conn->query("UPDATE angsuran SET `status`='$statusAngsuran' WHERE idpinjaman='$idpinjaman';");
                $query = $this->conn->query("UPDATE pinjaman SET `status`='$statusAngsuran' WHERE idpinjaman='$idpinjaman';");
                $query = $this->conn->query("UPDATE reminderpinjaman SET `status`='nonaktif' WHERE `status`='aktif' AND nokta='$nokta'");
            }

            if (!empty($simpananSukarela)) {
                $idharga = $hargaSisukarela['idharga'];
                $query = $this->conn->query("INSERT INTO sisukarela
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$nokta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'$simpananSukarela'
                                            ,'0'
                                            ,'$saldoSisukarela'
                                            ,'aktif'
                                            ,NOW()
                                        );");
            }

            if (!empty($simpananWajib)) {
                $idharga = $hargaSiwajib['idharga'];
                $query = $this->conn->query("INSERT INTO siwapo
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$nokta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'debit siwajib'
                                            ,'$simpananWajib'
                                            ,'$saldoSiwajib'
                                            ,'aktif'
                                            ,NOW()
                                        );");
            }

            if (!empty($simpananPokok)) {
                $idharga = $hargaSipokok['idharga'];
                $query   = $this->conn->query("INSERT INTO siwapo
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$nokta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'debit sipokok'
                                            ,'$simpananPokok'
                                            ,'$saldoSipokok'
                                            ,'aktif'
                                            ,NOW()
                                        );");
            }

            if (!empty($simpananMasadepan)) {
                $noSimapan = $simapan['nomor'];
                $noSimapan++;
                $idharga   = $hargaSimapan['idharga'];

                $query = $this->conn->query("INSERT INTO simapan
                                        VALUES
                                        (
                                            ''
                                            ,'$nota'
                                            ,'$nokta'
                                            ,'$idharga'
                                            ,'$idpetugas'
                                            ,'$noSimapan'
                                            ,'$simpananMasadepan'
                                            ,'aktif'
                                            ,NOW()
                                            ,''
                                        );");
            }

            if ($query->rowCount() > 0) {
                echo
                    "<script>
                document.location.href='add_transaksi_masuk.php?status=1&aksi=print&key=" . $control->hashMethod('encrypt', $nota) . "';
            </script>";
                die;
            } else {
                echo
                    "<script>
                document.location.href='add_transaksi_masuk.php?status=0&aksi=error';
            </script>";
                die;
            }

            // Error mode
            $query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r("Koneksi atau query pada insert transaksi debit bermasalah : " . $e->getMessage() . "<br>");
            die;
        }
    }
    // #END Section Transaksi

    // ================SEND MESSAGES FUNCTION======================
    public function sendMessage($noHp, $pesan)
    {
        $userkey = '2d481eeb6be6';
        $passkey = 'o3cazcwq6b';
        $telepon = "$noHp";
        $message = "$pesan";
        $url = 'https://gsm.zenziva.net/api/sendsms/';
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
            'userkey' => $userkey,
            'passkey' => $passkey,
            'nohp' => $telepon,
            'pesan' => $message
        ));
        $results = json_decode(curl_exec($curlHandle), true);

        if ($results['text'] == "Success") {
            echo "
            <script>
            alert('berhasil mengirimkan pesan kepada $noHp');
            document.location.href='../../index.php?action=sendmessage&message=1';
            </script>
            ";
            die;
        } else {
            echo "
            <script>
            alert('gagal mengirimkan pesan kepada $noHp');
            document.location.href='../../index.php?action=sendmessage&message=0';
            </script>
            ";
            die;
        }
        curl_close($curlHandle);
    }
    public function sendMessageWa($noHp, $pesan)
    {
        $userkey = '2d481eeb6be6';
        $passkey = 'o3cazcwq6b';
        $telepon = "$noHp";
        $message = "$pesan";
        $url = 'https://gsm.zenziva.net/api/sendWA/';
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
            'userkey' => $userkey,
            'passkey' => $passkey,
            'nohp' => $telepon,
            'pesan' => $message
        ));
        $results = json_decode(curl_exec($curlHandle), true);

        if ($results['text'] == "Success") {
            echo "
            <script>
            alert('berhasil mengirimkan pesan kepada $noHp');
            document.location.href='../../index.php?action=sendmessage&message=1';
            </script>
            ";
            die;
        } else {
            echo "
            <script>
            alert('gagal mengirimkan pesan kepada $noHp');
            document.location.href='../../index.php?action=sendmessage&message=0';
            </script>
            ";
            die;
        }
        curl_close($curlHandle);
    }
    public function sendMessageRegular($noHp, $pesan)
    {
        $userkey = '2d481eeb6be6';
        $passkey = 'o3cazcwq6b';
        $telepon = "$noHp";
        $message = "$pesan";
        $url = 'https://gsm.zenziva.net/api/sendsms/';
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
            'userkey' => $userkey,
            'passkey' => $passkey,
            'nohp' => $telepon,
            'pesan' => $message
        ));
        $results = json_decode(curl_exec($curlHandle), true);

        // if($results['text'] == "Success"){
        //     echo "
        //     <script>
        //     alert('berhasil mengirimkan pesan kepada $noHp');
        //     document.location.href='../../index.php?action=sendmessage&message=1';
        //     </script>
        //     ";
        //     die;
        // }else{
        //     echo "
        //     <script>
        //     alert('gagal mengirimkan pesan kepada $noHp');
        //     document.location.href='../../index.php?action=sendmessage&message=0';
        //     </script>
        //     ";
        //     die;
        // }
        curl_close($curlHandle);
    }
}
