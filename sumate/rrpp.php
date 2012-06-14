<!DOCTYPE html>
<html>
<head>
<title>¡TuMovida! - ¿Queres ser RRPP?</title>
<link rel="stylesheet" type="text/css" href="http://ga-api-javascript-samples.googlecode.com/svn-history/r7/trunk/src/explorer/css/custom-theme/jquery-ui-1.7.2.custom.css" />
<link rel="stylesheet" type="text/css" href="css/formularios.css" />
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
	<div class='banner' style="background: url('img/rrpp.jpg')"></div>
	<form action="<?=$_SERVER['PHP_SELF']?>?send" method="POST">
		<div class='row'>
			<span>Nombre y apellido</span><input type="text" name="nombre">
		</div>
		<div class='row'>
			<span>Telefono o celular</span><input type="text" name="telefono">
		</div>
		<div class='row'>
			<span>Correo electrónico</span><input type="text" name="mail" />
		<div class='row'>
			<span>Comentario<br><i>(opcional)</i></span>
			<textarea cols="50" rows="4" name="descripcion" placeholder="Descripcion del evento..."></textarea>
		</div>
		<input class='enviar' type="submit" value="Enviar" >
	</form>
</div>
</body>
</html>

<?php
if (isset($_GET["send"])){
	function data2File($dataArray){
		$nopermitdo = array(" ", "@", "ñ", "í", "á", "Á","ä", "´", "'", "/", "&" ,"\\" ,"\"", "ú", "Ú", "¨" ,"ü", "Ü");
		if (!file_exists("completeForms/"))
			mkdir("completeForms/");
		if (isset($dataArray["nombre"])){
			$fileName = str_replace($nopermitdo, "_", $dataArray["nombre"]).uniqid(2).".txt";
		}else{
			$fileName = uniqid(5).".txt";
		}
		while(file_exists("completeForms/".$fileName))
		{
			if (isset($dataArray["nombre"])){
				$fileName = str_replace($nopermitdo, "_", $dataArray["nombre"]).uniqid(2).".txt";
			}else{
				$fileName = uniqid(5).".txt";
			}	
		}
		$file = fopen("completeForms/".$fileName, "a+");
		
		$data2Send = "";
		foreach($dataArray as $k => $v){
			$data2Send .= 	ucfirst($k).": \t\t'" .$v."' \n\r"; 
		}
		fwrite($file, $data2Send);
		fclose($file);
	}
	function data2Mail($dataArray, $mail){
		$asunto = "Solicitud RRPP";
		$asuntoR= "¡TuMovida! - Respuesta automatica";
		$tmMail = "info@tumovida.com.uy";
		$remitente = $dataArray["mail"];
		$data2Send = "";
		foreach($dataArray as $k => $v){
			$data2Send .= 	ucfirst($k).": \t\t'" .$v."' \n\r"; 
		}
		mail($tmMail, $asunto, $data2Send);
		mail($remitente, $asuntoR, "Hemos recibido su correo. En breve nos contactaremos con usted. Adjuntamos sus datos para que los revise:\n".$data2Send);
	}
	function error($handle){

	}
	$data = array("nombre", "telefono", "lugar", "fecha", "descripcion");
	foreach($data as $a){
		$$a = (isset($_POST[$a])) ? $_POST[$a] : "";
		foreach($_POST as $key => $val){
			if ($val == ""){
				unset($_POST[$key]);
			}
		}
	}
	$dataArray = $_POST;
	$check = data2File($dataArray);
	if (!$check){
		error("file");
	}
	$check = data2Mail($dataArray);
	if (!$check){
		error("mail");
	}
}
?>
