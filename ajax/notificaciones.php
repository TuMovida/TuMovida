<?php 
include_once '../inc/conectar.php';
include_once '../inc/usuario.class.php';
date_default_timezone_set('America/Montevideo');
if (isset($_GET['getSince']) && !isset($_POST['iniTime']))
{
	$_POST['iniTime'] = $_GET['getSince'];
}

if (isset($_POST['iniTime']))
{
	set_time_limit(0);
	header("Edge-control: no-store");
	$iniTime 	= $_POST['iniTime'];
	if ($iniTime == 0){
		$iniTime = strtotime("NOW")-10;
	}
	$conn = new Conectar();
	if (!$conn)
		die('ERROR');
	$conn->TM();
	if (!$conn)
		die('ERROR: ' . mysql_error());
	$query = $conn->query("SELECT id, id_pagina, id_usuario, texto, fecha FROM comentarios WHERE fecha > FROM_UNIXTIME($iniTime) ORDER BY fecha ASC");
	if (!$query)
		die('ERROR: ' . mysql_error());
	$rows = mysql_num_rows($query);
	$ultimaFecha = 0;
	if ($rows <= 0)
		die(json_encode('NO_RESULTS'));
	while ($res = mysql_fetch_assoc($query)){
		switch ($res['id_pagina'][0]) {
			case 'e': $tipo = 'un evento'; break;
			case 'l': $tipo = 'un local'; break;
			case 'p': $tipo = 'una promo'; break;
		}
		$usuario = new Usuario($res['id_usuario']);
		$res["showText"] = ucfirst($usuario->getNombre())." ".ucfirst($usuario->getApellido())." acaba de comentar ".$tipo.".";
		$buff[] = $res;
		if ($res["fecha"] > $ultimaFecha) $ultimaFecha = $res["fecha"];
	}
	$buff[]['ultimaFecha'] =  strtotime($ultimaFecha);
	$json = json_encode($buff);
	/*********** Si hay exito ************/
	echo $json;
	ob_flush();
	flush();
	usleep(1000);
	exit();
}
?>