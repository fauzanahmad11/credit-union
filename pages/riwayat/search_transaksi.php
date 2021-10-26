<?php
require_once '../../assets/library/function.php';
require_once '../../assets/library/function_control.php';

$keyword = $_GET['key'];
$library = new Library();
$control = new Control();

$query = $library->conn->query("SELECT riwayattransaksi.*, SUM(total) AS totaltransaksi FROM `riwayattransaksi`
                                WHERE 
                                namatransaksi LIKE '%$keyword%' 
                                OR
                                nokta LIKE '%$keyword%'
                                OR
                                notransaksi LIKE '%$keyword%'
                                GROUP BY notransaksi
                                ");

    if($query->rowCount() > 0){
?>
    <?php
        $no = 1;
        while($row = $query->fetch(PDO::FETCH_ASSOC)):
    ?>
    <tr>
        <th><?=$no++?></th>
        <td><?=$row['waktu']?></td>
        <td><?=$row['nokta']?></td>
        <td><?=$row['notransaksi']?></td>
        <td><?=$row['namatransaksi']?></td>
        <td><?=$row['keterangan']?></td>
        <td><?=$control->rupiah($row['totaltransaksi'])?></td>
        <td style="display: flex; justify-content:center;">
        <?php 
        $keterangan = explode(" ",$row['keterangan']);
        if($keterangan[0] == 'debit'){
            if($row['namatransaksi'] == "sianggota"){
                echo "
                    <a class='icon icon-cetak' target='_blank' href='../cetak/cetak_sianggota.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                        <i class='fas fa-print'></i>
                    </a>
                ";
            }else{
                echo "
                    <a class='icon icon-cetak' target='_blank' href='../cetak/cetak_debit.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                        <i class='fas fa-print'></i>
                    </a>
                ";
            }
            ?>
        <?php }else { 
            if($row['namatransaksi'] == "pengajuan pinjaman"){
                echo "
                    <a class='icon icon-cetak' target='_blank' href='../cetak/cetak_pinjaman.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                        <i class='fas fa-print'></i>
                    </a>
                ";
            }else{
                echo "
                    <a class='icon icon-cetak' target='_blank' href='../cetak/cetak_kredit.php?key={$control->hashMethod('encrypt',$row['notransaksi'])}' title='Cetak data'>
                        <i class='fas fa-print'></i>
                    </a>
                ";
            }
            ?>
        <?php }?>
        </td>
    </tr>
    <?php
        endwhile;
    ?>
<?php
    }else{
?>
    <tr>
        <td colspan="8" style="color:#ddd;"><center><h2>Not Found</h2></center></td>
    </tr>
<?php
    }
?>