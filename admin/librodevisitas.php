<?php 
include "inc/bd_conn.php";
$conn = new Conectar();
$conn->TM();
if( isset($_GET['editar']) && is_numeric($_GET['editar']) ){
    $id = $_GET['editar'];
    $query = $conn->query("SELECT * FROM librodevisitas WHERE id=".$id);
}elseif(isset($_GET['borrar']) && is_numeric($_GET['borrar'])){
    $id = $_GET['borrar'];
    $query = $conn->query("UPDATE librodevisitas SET block=1 WHERE id=".$id);
    header("Location: ".$_SERVER['PHP_SELF']."?delSuccess");
}else{
    $query = $conn->query("SELECT * FROM librodevisitas WHERE block=0");
    while ($res = mysql_fetch_assoc($query))
       $buff[] = $res;
}
?>
<?php include "inc/header.php"; ?>
<div class="content container_12">
    <?php if(isset($_GET['delSuccess'])): ?>
        <div class="ad-notif-info grid_12"><p>Comentario eliminado</p></div>
    <?php endif;?>
    <?php if (isset($_GET['editar'])): ?>
    
    <?php else: ?>
    <div class="box grid_12">
        <div class="box-head"><h2>Lista de comentarios</h2></div>
        <div class="box-content no-pad">
            <table class="display" id="dt3">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Mensaje</th>
                        <th>Mail</th>
                        <th>Fecha</th>
                        <th><!--Editar--></th>
                        <th><!--Borrar--></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($buff as $user): ?>
                    <tr class="odd gradeX">
                        <td class="center"><?=$user['id']?></td>
                        <td><?=$user['Usuario']?></td>
                        <td><?=$user['Mensaje']?></td>
                        <td><?=$user['Mail']?></td>
                        <td><?if(isset($user['Fecha'])) echo $user['Fecha']?></td>
                        <td><a href='<?=$_SERVER['PHP_SELF']."?editar=".$user['id']?>' />Editar</a></td>
                        <td><a href='<?=$_SERVER['PHP_SELF']."?borrar=".$user['id']?>' />Borrar</a></td>
                    </tr>
                 <?php endforeach; ?>
                </tbody>
          </table>
        </div>
    </div>
    <?php endif;?>
</div>
<script type="text/javascript">
$('#dt3').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"   
    });
</script>
<? include "inc/footer.php"; ?>