<?php 
include "inc/bd_conn.php";

$conn = new Conectar();
$conn->TM();
if (isset($_POST["Nombre"]))
{
    $go = $_POST;
    if (isset($_FILES["Imagen"])){
        $img = new imageUpload($_FILES["Imagen"], "eventos");
        $go["Imagen"] = $img->getFile();
    }
    $conn->insert("eventos", $go);
    //var_dump($go);
    if (mysql_error()){
        echo mysql_error();
    }else{
        header("Location: eventos.php?success");
    }
}

$lista = isset($_GET["listado"]);
if ($lista){
    $query = $conn->query("SELECT * FROM eventos");
    while ($res = mysql_fetch_assoc($query))
        $buff[] = $res;
}
if(isset($_GET['obtenerUbicacion'])){
    $q = $conn->query("SELECT Mapa, Ubicacion FROM locales WHERE id=".$_GET['obtenerUbicacion']);
    $q = mysql_fetch_assoc($q);
    if ($q['Mapa'] != NULL){
        $q['Mapa'] = json_decode($q['Mapa'], false);
        $q['Mapa'] = $q['Mapa'][0].", ".$q['Mapa'][1].", ".$q['Mapa'][2].", ".$q['Mapa'][3];
    }else
        $q['Mapa'] = "";
    echo json_encode($q);
    exit;
}
if (isset($_GET['facebookURL']) && $_GET['facebookURL'] != "")
{
    define("GRAPH_URL", "http://graph.facebook.com/");
    $res = file_get_contents(GRAPH_URL.$_GET['facebookURL']);
    $res = json_decode($res, true);
    $send["Nombre"] = $res["name"];
    list($send["Fecha"], $send["Hora"]) = explode("T", $res["start_time"]);
    $send["Descripcion"] = $res["description"];
    $send["Ubicacion"] = $res["location"];
    if(isset($res["venue"])){
        $m = $res["venue"];
        $send["Mapa"] = $m["latitude"] . ", " . $m["longitude"] . ", 7, 'Titulo'";
    }
    $send["Facebook"] = "http://www.facebook.com/".$_GET['facebookURL'];
    echo json_encode($send);
    exit;
}
if (isset($_GET['obtener']))
{
    $get = $conn->query("SELECT * FROM eventos WHERE id=".$_GET['obtener']);
    $evento = mysql_fetch_assoc($get);
    echo json_encode($evento);
    exit;
}
if (isset($_POST["Nombre"]))
{
    exit;
}
?>
<?php include "inc/header.php"; ?>

<link href="css/redactor.css" rel="stylesheet" type="text/css" />
<script src="js/redactor.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).on("ready", function(){
    $.ajax({
        url: "<?=$_SERVER['PHP_SELF']?>",
        <?php if (isset($_GET['editar'])): ?>
        data: "obtener=<?=$_GET['editar']?>",
        <?php endif; ?>
        <?php if (isset($_GET['facebook'])): ?>
        data: "facebookURL=<?=$_GET['facebook']?>",
        <?php endif; ?>
        dataType: "JSON",
        success: function(res)
        {
            $("#formularioEvento input[type='text']").each(function(i){
                var fieldName = $(this).attr("name");
                $(this).val(res[fieldName]);
            });
            $("#evento_descripcion").text(res["Descripcion"]);
        }
    });
    $('#evento_fecha').datepicker();
    $('#evento_fecha').datepicker('option', 'dateFormat', 'yy-mm-dd');
    $("#evento_local").change(function(){
        var dataString = "obtenerUbicacion=" + $("#evento_local").val();
        $.ajax({
            url: "eventos.php?" + dataString,
            type: "GET",
            dataType: "JSON",
            success: function(datos){
                $("#evento_ubicacion").val(datos.Ubicacion); 
                if (datos.Mapa != "")
                    $("#evento_mapa").val(datos.Mapa);
            }
        });
    });
    $("textarea").redactor({
        lang: 'es',
        buttons: ['html', '|', 'bold', 'italic', 'deleted', '|', 'alignleft', 'aligncenter', 'alignright', 
        '|', 'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
        'justify', '|', 'horizontalrule', 'fullscreen']
    });
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
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($buff as $evento): ?>
                    <tr class="odd gradeX">
                        <td><?=$evento['id']?></td>
                        <td><?=$evento['Nombre']?></td>
                    </tr>
                 <?php endforeach; ?>
                </tbody>
          </table>
        </div>
    </div>
    <?php else: ?>
        <div class="box grid_6">
            <div class="box-head"><h2>Eventos</h2></div>
            <div class="box-content">
            <form id="formularioEvento" method="POST" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
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
                <label class="form-label">Fecha</label>
                  <div class="form-item">
                    <input name="Fecha" type="text" id="evento_fecha" placeholder="Fecha del evento"/>
                    <span class="form-icon fugue-2 calendar-day"></span>
                  </div>
                </div>

                <div class="form-row">
                  <p class="form-label">Hora del evento</p>
                  <div class="form-item">
                    <input name="Hora" type="text" placeholder="HH:MM" />
                    <span class="form-icon fugue-1 alarm-clock"></span>
                  </div>
                </div>

                <div class="form-row">
                    <p class="form-label">Edad</p>
                    <div class="form-item">
                        <input name="Edad" type="text" placeholder="Edad para asistir" />
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
                  <p class="form-label">Promo</p>
                  <div class="form-item">
                    <input name="id_promo" type="text" placeholder="ID de la promo asociada" />
                    <span class="form-icon fugue-4 marker"></span>
                  </div>
                </div>

                <div class="form-row">
                  <p class="form-label">Local</p>
                  <div class="form-item">
                    <input name="Local" id="evento_local" type="text" placeholder="ID del local" />
                    <span class="form-icon fugue-4 marker"></span>
                  </div>
                </div>

                <div class="form-row">
                    <p class="form-label">Ubicación</p>
                    <div class="form-item">
                        <input name="Ubicacion" type="text" id="evento_ubicacion" placeholder="Ubicación del evento" />
                    </div>
                </div>

                <div class="form-row">
                  <p class="form-label">Mapa</p>
                  <div class="form-item">
                    <input name="Mapa" type="text" id="evento_mapa" placeholder="{}" />
                    <div id="mapCanvas" style="width: 100%; height: 250px;"></div>
                  </div>
                </div>

                <div class="form-row">
                  <p class="form-label">Facebook</p>
                  <div class="form-item">
                    <input type="text" placeholder="Facebook URL" name="Facebook" />
                    <span class="form-icon fugue-1 balloon-facebook-left"></span>
                  </div>
                </div>

                <div class="form-row">
                  <p class="form-label">Destacado</p>
                  <input checked type="checkbox" id="iphone-check" />
                </div>

                <div class="form-row">
                  <hr />
                  <button class="button big black">Importar formulario</button>
                  <button class="button big grey">Exportar formulario</button>
                  <button id="cargarDatosDesdeFb" class="button big blue">Cargar datos desde Facebook</button>
                  <input type="submit" value="Enviar" class="button big green"/>
                </div>
            <div class="clear"></div>
            </form>
            </div>
    <?php endif; ?>
</div>
<div id="desdeFbDialog">
    <form action="<?=$_SERVER['PHP_SELF']?>" method="get">
        <div class="form-row">
            <p class="form-label">Facebook ID</p>
            <div class="form-item">
                <input style="font-size: 10px;" name="facebook" type="text" placeholder="ej. 216227871819743" />
                <span class="form-icon fugue-1 balloon-facebook-left"></span>
            </div>
            <div style="text-align: center; margin-top: 10px;">
                <input type="submit" value="Enviar" class="button green"/>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
$('#dt3').dataTable({
    "bJQueryUI": true,
    "sPaginationType": "full_numbers"   
});
$("#desdeFbDialog").dialog({
    autoOpen: false,
    modal: true
});
$("#cargarDatosDesdeFb").click(function(e){
    e.preventDefault();
    $("#desdeFbDialog").dialog("open");
});
</script>
<? include "inc/footer.php"; ?>