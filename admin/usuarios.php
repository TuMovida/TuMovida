<?php 
include "inc/bd_conn.php";
$conn = new Conectar();
$conn->Usuarios();
$query = $conn->query("SELECT * FROM usuarios");

while ($res = mysql_fetch_assoc($query))
    $buff[] = $res;
?>
<?php include "inc/header.php"; ?>
<div class="content container_12">
    <?php if (isset($_GET['success'])): ?>
  <div class="ad-notif-info grid_12"><p>Album cargado con exito</p></div>
    <?php endif; ?>
    <div class="box grid_12">
        <div class="box-head"><h2>Lista de usuarios</h2></div>
        <div class="box-content no-pad">
            <table class="display" id="dt3">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>CI</th>
                        <th>Mail</th>
                        <th>Sexo</th>
                        <th>Nacimiento</td>
                        <th>Dirección</td>
                        <th>Teléfono</td>    
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($buff as $user): ?>
                    <tr class="odd gradeX">
                        <td><?=$user['id']?></td>
                        <td><?=$user['Nombre']?></td>
                        <td><?=$user['Apellido']?></td>
                        <td class="center"><?=$user['CI']?></td>
                        <td><?=$user['Mail']?></td>
                        <td class="center"><?=$user['Sexo']?></td>
                        <td class="center"><?=$user['Nacimiento']?></td>
                        <td><?=$user['Direccion']?></td>
                        <td class="center"><?=$user['Telefono']?></td>
                    </tr>
                 <?php endforeach; ?>
                </tbody>
          </table>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#dt3').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"   
    });
</script>
<? include "inc/footer.php"; ?>