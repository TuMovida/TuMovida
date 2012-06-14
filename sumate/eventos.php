<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" type="text/css" href="http://ga-api-javascript-samples.googlecode.com/svn-history/r7/trunk/src/explorer/css/custom-theme/jquery-ui-1.7.2.custom.css" />
<style type="text/css">
body {
	background-color: rgb(70,70,70);
	color: white; 
	font-size: 16px; 
	font-family: "Verdana", sans-serif;
	margin: 0;
	padding: 0;
	text-align: center;
}
#bandas{
	background-color: whiteSmoke;
	border: 0 1px solid #DDD;
	box-shadow: 0 0 120px rgb(40,40,40);
	color: rgb(40,40,40);
	display: block;
	width: 960px;
	margin: 0 auto;
	margin-bottom: 40px;
	padding-bottom: 20px;
	overflow: hidden;
}
.banner{
	background: url("img/tmbandas.jpg") no-repeat;
	box-shadow: 0px 0px 10px rgb(40,40,40);
	height: 375px;
	margin-bottom: 10px;
}
.row{
	border-bottom: 1px solid #EEE;
	display: block;
	padding: 14px;
}
.row span{
	color: #555;
	display: inline-block;
	font-size: 14px;
	padding-right: 10px;
	text-align: left;
	vertical-align: middle;
	width: 140px;
}
.row input, .row textarea{
	border: 1px solid #DDD;
	border-radius: 4px;
	box-shadow: 0 0 10px rgba(60,60,60,0.1);
	color: #777;
	display: inline-block;
	font-family: "Verdana", "Arial", sans-serif;
	margin: 0px;
	padding: 4px;
	vertical-align: middle;
	width: 400px;
}
.row input:focus, .row textarea:focus{
	box-shadow: 0 0 10px rgba(60,60,60,0.3);
	outline: none;
}
input.enviar{
	background: #EEE;
	border: 1px solid #DDD;
	border-radius: 4px;
	color: #333;
	font-size: 16px;
	margin-top: 10px;
	padding: 6px;
	width: 150px;
}
input.enviar:hover{
	background: #FFF;
	color: rgb(102,102, 200);
	cursor: pointer;
}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
   $("#fecha").datepicker();
});
jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});
</script>
</head>
<body>
<div id="bandas"> 
	<div class='banner'></div>
	<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
		<div class='row'>
			<span>Nombre de contacto</span><input type="text" name="nombre">
		</div>
		<div class='row'>
			<span>Telefono a contactar</span><input type="text" name="telefono">
		</div>
		<div class='row'>
			<span>Lugar del evento</span><input type="text" name="lugar">
		</div>
		<div class='row'>
			<span>Fecha del evento</span><input type="text" name="fecha" id="fecha" size="19"  />
		</div>
		<div class='row'>
			<span>Precio</span><input type="text" name="precio">
		</div>
		<div class='row'>
			<span>Descripción<br><i>(opcional)</i></span>
			<textarea cols="50" rows="4" name="descripcion" placeholder="Descripcion del evento..."></textarea>
		</div>
		<input class='enviar' type="submit" value="Enviar" >
	</form>
</div>
</body>
</html>

<?php
if ( isset($_POST['nombre'])){
	$nombre=$_POST['nombre'];
	$telcontacto=$_POST['telefono'];
	$lugarev=$_POST['lugar'];
	$fechaev=$_POST['fecha'];
	$descripcion=$_POST['descripcion'];
	$precio=$_POST['precio'];
	echo "Nombre de Contacto:".$nombre."<br>"; 
	echo "Telefono a contactar:".$telcontacto."<br>";
	echo "Lugar de evento:".$lugarev."<br>";
	echo "Fecha del evento:".$fechaev."<br>";
	echo "Descripcion:".$descripcion."<br>";
	echo "Precio:".$precio."<br>";
	echo "LA BANDA SE HA REGISTRADO CON EXITO<br>";
	exit;
}
?>