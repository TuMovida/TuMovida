<?php 
include "inc/bd_conn.php";
$conn = new Conectar();
$conn->Usuarios();
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $query = $conn->query("SELECT * FROM compras WHERE idArticulo=".$_GET['id']);
}else{
    $query = $conn->query("SELECT * FROM compras ORDER BY id DESC");
}
while ($res = mysql_fetch_assoc($query))
    $buff[] = $res;
?>
<?php include "inc/header.php"; ?>
<div class="content container_12">
    <?php if (!isset($_GET['id'])):
    $conn->TM();
    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $q = $conn->query("SELECT * FROM promociones WHERE id=".$_GET['id']);
    }else{
        $q = $conn->query("SELECT * FROM promociones ORDER BY id DESC");    
    }
    while ($r = mysql_fetch_assoc($q))
        $b[] = $r;
    ?>
    <div class="box grid_12">
        <div class="box-head"><h2>Lista de promociones</h2></div>
        <div class="box-content no-pad">
            <table class="display" id="dt3">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($b as $p): ?>
                    <tr class="odd gradeX">
                        <td><?=$p['id']?></td>
                        <td><a href='<?php echo $_SERVER['PHP_SELF']."?id=".$p['id']?>'><?=$p['Nombre']?></a></td>
                    </tr>
                 <?php endforeach; ?>
                </tbody>
          </table>
        </div>
    </div>
    <?php else: ?>
    <div class="box grid_12">
        <div class="box-head"><h2>Lista de compras</h2></div>
        <div class="box-content no-pad">
            <?php if(!isset($buff)):
                echo "<span style='display: block; text-align: center; padding: 10px;'>No hay compras</span>";
            else:
            ?> 
            <table class="display" id="dt4">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>idArticulo</th>   
                        <th>Descripcion</th>                     
                        <th>CI</th>
                        <th>Cantidad</th>
                        <th>Importe</th>
                        <th>Estado</td>
                        <th>RRPP</td>
                        <th>Fecha</td>    
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($buff as $user): ?>
                    <tr class="odd gradeX">
                        <td><?=$user['id']?></td>
                        <td><?=$user['idArticulo']?></td>   
                        <td><?=$user['Descripcion']?></td>                     
                        <td><a href='usuario.php?ci=<?=$user['CI']?>'><?=$user['CI']?></a></td>
                        <td><?=$user['Cantidad']?></td>
                        <td>$ <?=$user['Importe']?></td>
                        <td><?php
                        switch($user['Estado']){
                            case 0: echo "No paga"; break;
                            case 1: echo "Paga"; break;
                            case 2: echo "Anulada"; break;
                        }
                        ?></td>
                        <td><?=$user['RRPP']?></td>
                        <td><?=$user['Fecha']?></td>
                    </tr>
                 <?php endforeach; ?>
                </tbody>
          </table>
          <?php endif;?>
        </div>
    </div>
    <?php endif; ?>
</div>
<script type="text/javascript">
$('#dt3, #dt4').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"   
    });
</script>
<? include "inc/footer.php"; ?>