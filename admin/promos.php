<?php
include "inc/bd_conn.php";
$title = "Promociones";
$conn = new Conectar();
$conn->TM();
if(isset($_GET['desactivar']) && is_numeric($_GET['desactivar'])){
    $query = $conn->query("UPDATE promociones SET Activo=0 WHERE id=".$_GET['desactivar']);
    if(!$query){
        die("Hubo un error desactivando la promociones");
    }else{
        header("Location: ".$_SERVER['PHP_SELF']."?listado=activas");
    }
}
if (isset($_POST["Nombre"]))
{
    $go = $_POST;
    if (isset($go['Condicion'])){
        foreach($go['Condicion'] as $condicion){
            if(!isset($condiciones)){
                $condiciones = $condicion;
            }else{
                $condiciones.= "*".$condicion;
            }
        }
    }
    unset($go['Condicion']);
    $go['Condiciones'] = $condiciones;
    if (isset($_FILES["Imagen"])){
        $img = new imageUpload($_FILES["Imagen"], "promos");
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
    echo $set."<br>";
    if(isset($go['id'])){
        $conn->update("promociones", $go, "id=".$go['id']);
    }else{
        $conn->insert("promociones", $go);
    }
    if (mysql_error()){
        echo mysql_error();
    }else{
        header("Location: promos?success");
    }
}
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $query = $conn->query("SELECT * FROM promociones WHERE id=".$_GET['id']);
    if($query){
        $dev = mysql_fetch_assoc($query);
    }
}
$lista = isset($_GET["listado"]);
if ($lista){
    if($_GET["listado"] == "activas"){
        $query = $conn->query("SELECT * FROM promociones WHERE Activo=1");
    }else{
        $query = $conn->query("SELECT * FROM promociones");
    }
    while ($res = mysql_fetch_assoc($query))
        $buff[] = $res;
}
include "inc/header.php"; 
?>
<link href="css/redactor.css" rel="stylesheet" type="text/css" />
<script src="js/redactor.min.js" type="text/javascript"></script>
<style>
#add_condicion{
    color: #086CFF;;
    display: block;
    font-size: 12px;
    margin-top: 4px;
    text-align: right;
    width: 100%;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
    $('#fecha_inicio, #fecha_finalizado').datepicker();
    $('#fecha_inicio, #fecha_finalizado').datepicker('option', 'dateFormat', 'yy-mm-dd');
    $('#add_condicion').click(function(e){
        e.preventDefault();
        $('.condiciones').append("<input type='text' name='Condicion[]' style='margin-top: 2px;' placeholder='Otra condición'/>");
    });
    $("textarea").redactor({
        lang: 'es',
        buttons: ['html', '|', 'bold', 'italic', 'deleted', '|', 'alignleft', 'aligncenter', 'alignright', 
        '|', 'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
        'justify', '|', 'horizontalrule', 'fullscreen']
    });
    <?php if(isset($dev)): ?>
    var dataJSON = <?php echo json_encode($dev) ?>;
    console.log(dataJSON);
    $("input[name='Nombre']").val(dataJSON.Nombre);
    $("textarea").setCode(dataJSON.Descripcion);
    $("input[name='FechaInicio']").val(dataJSON.FechaInicio);
    $("input[name='FechaFinalizado']").val(dataJSON.FechaFinalizado);
    $("input[name='Valor']").val(dataJSON.Valor);
    $.each(dataJSON.Condiciones.split("*"), function(index, value){
        if(index === 0){
            $(".condiciones input").val(value)
        }else{
            $('.condiciones').append("<input type='text' name='Condicion[]' style='margin-top: 2px;' value='"+value+"'/>");
        }
    });
    
    <?php endif; ?>
});
</script>
<div class="content container_12">
    <?php if (isset($_GET['success'])): ?>
  <div class="ad-notif-info grid_12"><p>Evento cargado con exito</p></div>
    <?php endif; ?>
    <?php if ($lista): ?> 
    <div class="box grid_12">
        <div class="box-head"><h2>Lista de eventos</h2></div>
        <div class="box-content no-pad">
            <table class="display" id="dt3">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th><!--Editar--></th>
                        <th><!--Desactivar--></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($buff as $promos): ?>
                    <tr class="odd gradeX">
                        <td><?=$promos['id']?></td>
                        <td><?=$promos['Nombre']?></td>
                        <td><a href="<?=$_SERVER['PHP_SELF'].'?id='.$promos['id']?>">Editar</a></td>
                        <td><a href="<?=$_SERVER['PHP_SELF'].'?desactivar='.$promos['id']?>">Desactivar</a></td>
                    </tr>
                 <?php endforeach; ?>
                </tbody>
          </table>
        </div>
    </div>
    <?php else: ?>
        <div class="box grid_6">
            <div class="box-head"><h2>Promoción</h2></div>
            <div class="box-content">
            <form id="formularioEvento" method="POST" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
                <?php if(isset($dev)): ?>
                <input type="hidden" name="id" value="<?php echo $dev['id']?>" />
                <?php endif;?>
                <div class="form-row">
                    <p class="form-label">Nombre</p>
                    <div class="form-item">
                        <input name="Nombre" type="text" placeholder="Nombre del evento"/>
                    </div>
                </div>

                <div class="form-row">
                  <p class="form-label">Descripcion</p>
                  <div class="form-item">
                    <textarea style="width: 100%;" rows="10" name="Descripcion" id="evento_descripcion" name="descripcion"></textarea>
                  </div>
                </div>

                <div class="form-row">
                <label class="form-label">Fecha de Inicio</label>
                  <div class="form-item">
                    <input name="FechaInicio" type="text" id="fecha_inicio" placeholder="Fecha de inicio"/>
                    <span class="form-icon fugue-2 calendar-day"></span>
                  </div>
                </div>

                <div class="form-row">
                <label class="form-label">Fecha de Finalizado</label>
                  <div class="form-item">
                    <input name="FechaFinalizado" type="text" id="fecha_finalizado" placeholder="Fecha de finalizado"/>
                    <span class="form-icon fugue-2 calendar-day"></span>
                  </div>
                </div>

                <div class="form-row">
                  <p class="form-label">Valor</p>
                  <div class="form-item">
                    <input name="Valor" type="text" placeholder="ejemplo: 100" />
                    <span class="form-icon fugue-1 alarm-clock"></span>
                  </div>
                </div>

                <?php if(!isset($dev)): ?>
                <div class="form-row">
                  <label class="form-label">Imagen</label>
                  <div class="form-item file-upload">
                    <input />
                    <input name="Imagen" class="filebase" type="file" />
                    <span class="form-icon fugue-3 image-sunset"></span>
                  </div>
                </div>
                <?php endif; ?>

                <div class="form-row">
                    <p class="form-label">Conciciones</p>
                    <div class="form-item condiciones">
                        <input name="Condicion[]" type="text" placeholder="Primera condición" />
                    </div>
                    <a id="add_condicion" href="#">Añadir otra condición</a>
                </div>

                <div class="form-row">
                  <hr />
                  <input type="submit" value="Enviar" class="button big green"/>
                </div>
            <div class="clear"></div>
            </form>
            </div>
    <?php endif; ?>
</div>
<script type="text/javascript">
$('#dt3').dataTable({
    "bJQueryUI": true,
    "sPaginationType": "full_numbers"   
});
</script>
<? include "inc/footer.php"; ?>