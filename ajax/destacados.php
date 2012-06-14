<?php
include_once "../inc/conectar.php";
include_once "../inc/paginas.class.php"; 

$bd = new Conectar();
$bd->TM();
$query = $bd->query("SELECT * FROM destacados WHERE Activo=1");
while ($destacado = mysql_fetch_assoc($query)){
	if ($destacado['Tipo'] == "Evento"){
		$evento = new evento($destacado['Enlace']);
		if ($evento->getPromo())
			$destacado['Entradas'] = $evento->getPromo();
		else
			$destacado['Entradas'] = false;
	}else{
		$destacado['Entradas'] = false;
	}
	$destacados[] = $destacado;
}
echo json_encode($destacados);