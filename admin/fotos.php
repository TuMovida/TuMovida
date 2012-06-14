<?php
    $sid = uniqid(4);
    require "inc/bd_conn.php";

if (isset($_POST['sendAlbum']) && isset($_POST['sid']))
{
    $sid = $_POST['sid'];
    $dir = "../images/fotos/eventos/".$sid."/";
    if (!file_exists($dir)) die("ERROR: No se pudo cargar el album");
    $archivos = scandir($dir);
    foreach($archivos as $a){
        if(preg_match("/\.$/", $a))
            continue;
        else
            $buff[] = (string) $sid."/".$a;
    }
    $fotos =  json_encode($buff);
    $album = isset($_POST['album']) ? $_POST['album'] : date();
    $idEvento =  isset($_POST['idEvento']) ? $_POST['idEvento'] : 0;
    $dataArray = array("idEvento" => $idEvento, "Album" => $album, "Fotos" => $fotos);
    $conn = new Conectar();
    $conn->TM();
    $success = $conn->insert("eventos_fotos", $dataArray);
    if (!$success){
        header("Location: ".$_SERVER['PHP_SELF']."?error=".mysql_error());
    }else{
        header("Location: ".$_SERVER['PHP_SELF']."?success=".$success["id"]);
    }
    exit;
}
?>
<?php include "inc/header.php"; ?>
<!-- Load Queue widget CSS and jQuery -->
<style type="text/css">@import url(js/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css);</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<!-- Third party script for BrowserPlus runtime (Google Gears included in Gears runtime now) -->
<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
<!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
<script type="text/javascript" src="js/plupload/plupload.full.js"></script>
<script type="text/javascript" src="js/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript">
$(document).on("ready", function(){
    $("#uploader").pluploadQueue({
        runtimes : 'html5,gears,flash,silverlight,browserplus,',
        url : 'inc/upload.foto.php?sid=<?=$sid?>',
        max_file_size : '10mb',
        chunk_size : '1mb',
        unique_names : true,
        multipart_params: {album: $("#albumName").val()},
        //resize : {width : 320, height : 240, quality : 90},
        filters : [
            {title : "Image files", extensions : "jpg,gif,png"},
            {title : "Zip files", extensions : "zip"}
        ],
        flash_swf_url : 'js/plupload/plupload.flash.swf',
        silverlight_xap_url : 'js/plupload/plupload.silverlight.xap'
    });
    $('#fotoQueue').submit(function(e) {
        var uploader = $('#uploader').pluploadQueue();
        if (uploader.files.length > 0) {
            uploader.bind('StateChanged', function() {
                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                    $('#fotoQueue')[0].submit();
                }
            });
            uploader.start();
        } else {
            alert('You must queue at least one file.');
        }
        return false;
    });
});
</script>

<div class="content container_12">
    <?php if (isset($_GET['success'])): ?>
    <div class="ad-notif-info grid_12"><p>Album cargado con exito</p></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
    <div class="ad-notif-error grid_12"><p>Error: <?php echo $_GET['error'] ?></p></div>
    <?php endif; ?>
        <div class="box grid_6">
            <div class="box-head"><h2>Datos del album</h2></div>
            <div class="box-content">
                <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                    <div class="form-row">
                        <p class="form-label">Album</p>
                        <div class="form-item">
                            <input type="text" name="album" id="albumName" placeholder="Nombre del album evento"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <p class="form-label">Evento</p>
                        <div class="form-item">
                            <input type="text" name="idEvento" placeholder="Nombre del evento"/>
                        </div>
                    </div>
                    <input type="hidden" value="<?=$sid?>" name="sid"/>
                    <div class="form-row">
                        <input type="submit" name="sendAlbum" value="Guardar" class="button big blue"/>
                    </div>
                </form>
                <p>Recordar primero subir las fotos y después guardar el album</p>
            </div>
        </div>
  <div class="box grid_6">
   <div class="box-head"><h2>Fotos</h2></div>
   <div class="box-content">
        <form action="" id="fotoQueue">
            <div id="uploader">
                <p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
            </div>
        </form>
     <div class="clear"></div>
   </div>
 </div>
</div>
<? include "inc/footer.php"; ?>