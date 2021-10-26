<?php
require_once '../../assets/library/function.php';
require_once '../../assets/library/function_control.php';

$keyword = $_GET['key'];
$library = new Library();
$control = new Control();

$query = $library->conn->query("SELECT*FROM riwayatanggota 
                                WHERE 
                                nama LIKE '%$keyword%' 
                                OR
                                nokta LIKE '%$keyword%'
                                OR
                                keterangan LIKE '%$keyword%'
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
        <td><?=$row['waktu']?></td>
        <td><?=$row['keterangan']?></td>
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