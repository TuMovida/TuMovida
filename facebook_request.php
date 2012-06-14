<?php
date_default_timezone_set('America/Montevideo');
header("Content-type: text/html; charset='utf8'");
include 'inc/conectar.php';
include 'inc/paginas.class.php';

$conn = new Conectar;
$conn->TM();
$type 	= mysql_real_escape_string($_GET['t']);
$id 	= mysql_real_escape_string($_GET['id']);
if ($type == "foto"){
	$pid = explode("-", $id);
	$idAlbum  = $pid[0];
	$idFoto = $pid[1];
	$id = $idFoto;
	$response = mysql_fetch_assoc($conn->query("SELECT * FROM eventos_fotos WHERE id=".$idAlbum));
	$fotores = json_decode($response["Fotos"]);
	$foto = $fotores[$idFoto];
	$page = "album/".$idAlbum."/foto";
	$response["Descripcion"] = "¡TuMovida! - Fotos de ".$response["Album"];
	$response["Nombre"] = $response["Album"];
	//$img = "http://img.tumovida.com.uy/thumb.php?w=300&h=300&zc=1&src=fotos/eventos/".$foto;
	$img = "http://img.tumovida.com.uy/fotos/eventos/".$foto;
}else{
	$response = mysql_fetch_assoc($conn->query("SELECT * FROM ".$type." WHERE id=".$id));
	$path = "http://www.tumovida.com.uy/images/".str_replace("promociones", "promos", $type)."/";
	$img = $path.$response['Imagen'];
	$t2page = array("promociones" => "promo", "locales" => "local", "eventos" => "evento", "eventos_fotos" => "album");
	$page = $t2page[$type];
}
if (mysql_error()) die(mysql_error());

if($page == "album")
{
	$response['Nombre'] = $response['Album'];
	$reponse['Descripcion'] = '';
	$a = json_decode($reponse['Fotos']);
	$img = "http://img.tumovida.com.uy/fotos/eventos/".$a[0];
}
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="og:url" content="http://www.tumovida.com.uy/facebook_request.php?t=<?=$type?>&id=<?=$_GET['id']?>" />
<meta property="og:title" content="<?=$response['Nombre']?>" />
<meta property="og:description" content="<?=strip_tags($response['Descripcion'])?>" />
<meta property="og:image" content="<?=$img?>" />
<title>¡TuMovida! - <?=$response['Nombre']?></title>
<script type="text/javascript">
	document.location.href = "http://www.tumovida.com.uy/#!/<?=$page?>/<?=$id?>";
</script>
</head>
<body>
<img src="<?=$img?>" alt=""/>
</body>
</html>