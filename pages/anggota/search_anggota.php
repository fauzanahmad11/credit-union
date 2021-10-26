<?php
require_once '../../assets/library/function.php';
require_once '../../assets/library/function_control.php';

$keyword = $_GET['key'];
$library = new Library();
$control = new Control();

$query = $library->conn->query("SELECT*FROM anggota 
                                WHERE 
                                nama LIKE '%$keyword%' 
                                OR
                                nokta LIKE '%$keyword%'
                                OR
                                pekerjaan LIKE '%$keyword%'
                                OR
                                alamat LIKE '%$keyword%'
                                OR
                                `status` LIKE '%$keyword%'
                                LIMIT 0,10
                                ");

    if($query->rowCount() > 0){
?>
    <?php
        $no = 1;
        while($row = $query->fetch(PDO::FETCH_ASSOC)):
    ?>
    <tr>
        <th><?=$no++?></th>
        <td><?=$row['nokta']?></td>
        <td><?=$row['nama']?></td>
        <td><?=$row['jenkel']?></td>
        <td><?=$row['alamat']?></td>
        <td><?=$row['notelepon']?></td>
        <td><?=$row['pekerjaan']?></td>
        <td>
        <?php
            if($row['status'] === 'aktif'){
                echo "Aktif";
            }else{
                echo "Non Aktif";
            }
        ?>
        </td>
        <td style="display: flex; justify-content:center;">
            <a href="#" value="<?=$control->hashMethod('encrypt',$row['nokta'])?>" class="icon icon-detail" title="lihat detail data">
                <img src="../../assets/img/icon/icon-detail2.svg"
                    class="icon-action" alt="icon-detail">
            </a>
            <a href="update_anggota.php?data=<?=$control->hashMethod('encrypt',$row['nokta'])?>" class="icon icon-update" title="ubah data">
                <img src="../../assets/img/icon/icon-update2.svg"
                    class="icon-action" alt="icon-update">
            </a>
            <a href="delete_anggota.php?data=<?=$control->hashMethod('encrypt',$row['nokta'])?>" class="icon icon-delete" title="hapus data">
                <img src="../../assets/img/icon/icon-delete2.svg"
                    class="icon-action" alt="icon-delete">
            </a>
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