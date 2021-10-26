<?php
class Control
{
    public function hashMethod($action, $data)
    {
        $chipherMethod = "AES-256-CBC";
        $secret_key = "12512235235254626426246426246426";
        $secret_iv = "235235234542524542542542524542524";
        $option = 0;
        $output = false;
        // $ivlen = openssl_cipher_iv_length($chipherMethod);
        // $iv = openssl_random_pseudo_bytes($ivlen);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $key = hash('sha256', $secret_key);

        if ($action === "encrypt") {
            $output = openssl_encrypt($data, $chipherMethod, $key, $option, $iv);
            $output = base64_encode($output);
        } else {
            if ($action === "decrypt") {
                $output = openssl_decrypt(base64_decode($data), $chipherMethod, $key, $option, $iv);
            }
        }

        return $output;
    }

    public function unRupiah($data)
    {
        $rupiah = preg_replace('/[Rp. ]/', '', $data);
        return $rupiah;
    }

    public function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }

    public function rupiahSecound($angka)
    {
        $hasil_rupiah = "Rp." . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }

    public function limitKata($text, $limit)
    {
        if (strlen($text) > $limit) {
            $word = mb_substr($text, 0, $limit - 3) . "...";
        } else {
            $word = $text;
        }
        return $word;
    }


    public function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->penyebut($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai / 10) . " puluh" . $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai / 100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai / 1000) . " ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai / 1000000) . " juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai / 1000000000) . " milyar" . $this->penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai / 1000000000000) . " trilyun" . $this->penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    public function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim($this->penyebut($nilai));
        } else {
            $hasil = trim($this->penyebut($nilai));
        }
        return $hasil;
    }


    function pembulatan($uang)
    {
        $uang = ceil($uang);
        $ratusan = substr($uang, -3);
        $akhir = null;
        if ($ratusan < 500) {
            $akhir = $uang - $ratusan;
        } else {
            $akhir = $uang + (1000 - $ratusan);
        }
        $uang = number_format($akhir, 0, '', '');
        return $uang;
    }

    function tanggalFormat($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return ucwords($this->terbilang($pecahkan[2])) . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . ucwords($this->terbilang($pecahkan[0]));
    }
}
