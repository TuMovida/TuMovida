<?php 
include "inc/bd_conn.php";
$conn = new Conectar();
$conn->TM();
if (isset($_POST["Titulo"]))
{
    $go = $_POST;
    if (isset($_FILES["Imagen"])){
        $img = new imageUpload($_FILES["Imagen"], "destacados");
        $go["Imagen"] = $img->getFile();
    }
    foreach($go as $name=>$val){
        $val = str_replace("\"", "\\\"", $val);
        $val = str_replace("'", "\'", $val);
        if(!isset($set)){
            $set = $name."=\"".mysql_real_escape_string($val)."\"";
        }else{
            $set.= ", ".$name."=\"".mysql_real_escape_string($val)."\"";
        }
    }
    if(isset($go['id'])){
        $query = $conn->update("destacados", $go, "id=".$go['id']);
    }else{
        $query = $conn->insert("destacados", $go);
    }
    if (!$query || mysql_error()){
        echo mysql_error();
    }else{
        header("Location: destacados?success");
    }
}
if(isset($_GET['desactivar']) && is_numeric($_GET['desactivar'])){
    $query = $conn->query("UPDATE destacados SET Activo=0 WHERE id=".$_GET['desactivar']);
    if($query){
        header("Location: ".$_SERVER['PHP_SELF']);
    }
}
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $query = $conn->query("SELECT * FROM destacados WHERE id=".$_GET['id']);
}else{
    if(isset($_GET['activos'])){
        $query = $conn->query("SELECT * FROM destacados WHERE Activo=1 ORDER BY id DESC");
    }else{
        $query = $conn->query("SELECT * FROM destacados ORDER BY id DESC");    
    }
}
while ($res = mysql_fetch_assoc($query))
    $buff[] = $res;
?>
<?php include "inc/header.php"; ?>
<div class="content container_12">
    <?php if (isset($_GET['success'])): ?>
    <div class="ad-notif-info grid_12"><p>Destacado cargado con éxito</p></div>
    <?php endif; ?>
        <?php if(isset($_GET['nuevo'])): ?>
    <div class="box grid_6">
        <link href="css/redactor.css" rel="stylesheet" type="text/css" />
        <script src="js/redactor.min.js" type="text/javascript"></script>
        <script type="text/javascript">
        $(document).ready(function(){
            $("textarea").redactor({
                lang: 'es',
                buttons: ['html', '|', 'bold', 'italic', 'deleted', '|', 'alignleft', 'aligncenter', 'alignright', 
                '|', 'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
                'justify', '|', 'horizontalrule', 'fullscreen']
            });
        });
        </script>
        <div class="box-head"><h2>Nuevo destacado</h2></div>
        <div class="box-content">
            <form id="formularioDestacado" method="POST" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
                <div class="form-row">
                    <label class="form-label">Titulo</label>
                    <div class="form-item">
                        <input name="Titulo" type="text" placeholder="Titulo para la descripción"/>
                    </div>
                </div>
                <div class="form-row">
                    <label class="form-label">Tipo</label>
                    <div class="form-item">
                        <select name="Tipo">
                            <option value="Evento">Evento</option>
                            <option value="Local">Local</option>
                            <option value="Enlace">Enlace</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <label class="form-label" for="Descripcion">Descripción</label>
                    <div class="form-item">
                        <textarea name="Descripcion" rows="4"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <label class="form-label">Enlace</label>
                    <div class="form-item">
                        <input name="Enlace" type="text" placeholder="Enlace de la descripción (ejemplo ID de evento)"/>
                    </div>
                </div>
                <div class="form-row">
                    <label class="form-label">Imagen</label>
                    <div class="form-item file-upload">
                        <input />
                        <input name="Imagen" class="filebase" type="file" />
                        <span class="form-icon fugue-3 image-sunset"></span>
                    </div>
                </div>
                <div class="form-row">
                    <hr />
                    <input type="submit" value="Enviar" class="button big green"/>
                </div>
                <div class="clear"></div>
            </form>
        </div>
        <?php else: ?>
    <div class="box grid_12">
        <div class="box-head"><h2>Lista de destacados</h2></div>
        <div class="box-content no-pad">
            <?php if(!isset($buff)):
                echo "<span style='display: block; text-align: center; padding: 10px;'>No hay destacados</span>";
            else:
            ?> 
            <table class="display" id="dt4">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Tipo</th>
                        <th>Titulo</th>
                        <th>Descripción</th>
                        <th>Enlace</th>
                        <th>Imágen</th>
                        <th>Activa</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($buff as $p): ?>
                    <tr class="odd gradeX">
                        <td><?=$p['id']?></td>
                        <td><?=$p['Tipo']?></td>
                        <td><?=$p['Titulo']?></td>
                        <td><?=$p['Descripcion']?></td>
                        <td><?=$p['Enlace']?></td>
                        <td><a href="../images/destacados/<?=$p['Imagen']?>"><?=$p['Imagen']?></a></td>
                        <td><?php
                        if($p['Activo'] == 0){
                            echo "No";
                        }else{
                            echo "Si, <a href='".$_SERVER['PHP_SELF']."?desactivar=".$p['id']."'>Desactivar</a>";
                        }
                        ?></td>
                    </tr>
                 <?php endforeach; ?>
                </tbody>
          </table>
          <?php endif;?>
        </div>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
$('#dt3, #dt4').dataTable( {
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"   
    });
</script>
<? include "inc/footer.php"; ?>