<?php
header('Content-Type: text/html; charset=UTF-8'); 
date_default_timezone_set("America/Montevideo"); 
@session_start();
include "inc/conectar.php";
include "inc/usuario.class.php";
if (isLogged()){
	$user = new Usuario($_SESSION['idusuario']);
}
?>
<!DOCTYPE>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>¡TuMovida! - Mapa</title>
	<link rel="shortcut icon" href="favicon.ico">
	<link href="css/main.css" rel="stylesheet" type="text/css" />
	<style>
	body{
		margin: 0px;
	}
	#mapCanvas{
		color: black;
		width: 100%;
		height: 100%;
	}
	.horariosInfo{
		border-top: 1px solid #DDD;
		padding: 4px;
		font-size: 12px;
	}
	#logoTM, #logo{
		margin-left: 22px;
	}
	#usuario{
		margin-right: 22px;
	}
	#controles{
		background: rgba(255, 255, 255, 0.8);
		border-radius: 0 10px 0 0;
		bottom: 0px;
		color: #DDD;
		display: none;
		height: 150px;
		left: 0px;
		position: absolute;
		width: 300px;
		z-index: 999;
	}
	</style>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDcJm10cJjXQ6cZvCH4c25x5BhZCRbvY48&sensor=true"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/modernizr.min.js"></script>
	<script type="text/javascript" src="js/swapImg.js"></script>
	<script type="text/javascript">
	$(document).on("ready", function(){
		function setPos(){
			var leftPosition = 22;
			var rightPosition= 22;
			$("#logoTM, #logo").css('margin-left', leftPosition);
			$("#usuario").css('margin-right', rightPosition);
			
		}
		$("#hMenu").slideDown(250);
		setPos();
		$("#mostrarAbiertos").click(function(e){
			e.preventDefault();

		});
		var latlng = new google.maps.LatLng(-34.397, 150.644);
		var myOptions = {
		  zoom: 12,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		  disableDefaultUI: true
		};
		var map = new google.maps.Map(document.getElementById("mapCanvas"),
		    myOptions);

		// Try W3C Geolocation (Preferred)
	  	if(navigator.geolocation) {
		    browserSupportFlag = true;
		    navigator.geolocation.getCurrentPosition(function(position) {
		      initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
		      map.setCenter(initialLocation);
		      google.maps.Marker;
		    });
		}
		google.maps.Marker.prototype.horario = new Array();
		<?php
		$conn = new Conectar;
		$conn->TM();
		$query = $conn->query("SELECT * FROM redpagos");
		while ($r = mysql_fetch_assoc($query)){
			$p = json_decode($r['Mapa']);
			$h = json_decode($r['Horario']);
			$dia = date("w");
			(isset($_GET['t'])) ? $horarioActual = strtotime($_GET['t']) : $horarioActual = time();	

			if ($h[$dia] == ":00" || $h[$dia] == "xx")
				continue;
			$inicia = strtotime($h[$dia]);
			$finaliza = strtotime($h[$dia+7]);
			
			if 	( ($inicia <= $horarioActual) && ($finaliza >= $horarioActual) ):
			
			$txt = "<div class=\'horariosInfo\'>";
			(($h[0] == ":00") || ($h[0] == "xx")) ? $txt.= "Cerrado los domingos<br />" : $txt.= "Domingos de $h[0] a $h[7] <br />";
			$txt.= "Lunes de $h[1] a $h[8]<br/>";
			$txt.= "Martes de $h[2] a $h[9]<br/>";
			$txt.= "Miércoles de $h[3] a $h[10]<br/>";
			$txt.= "Jueves de $h[4] a $h[11]<br/>";
			$txt.= "Viernes de $h[5] a $h[12]<br/>";
			(($h[6] == ":00") || ($h[6] == "xx")) ? $txt.= "Cerrado los sábados<br />" : $txt.= "Sábado de $h[6] a $h[13]<br/>";
			$txt.= "</div>";
			?>
			var marker<?=$r['id']?> = new google.maps.Marker({
				position: new google.maps.LatLng(<?=$p[0].",".$p[1]?>),
				map: map,
				horario: <?=$r['Horario']?>
			});
			google.maps.event.addListener(marker<?=$r['id']?>, "mouseover", function(){
				infoWindow.setContent('<?=$p[3].$txt?>');
				infoWindow.open(map, marker<?=$r['id']?>);
			});
			google.maps.event.addListener(marker<?=$r['id']?>, "mouseout", function(){
				infoWindow.close();
			});
			<?php
			endif;
		}
		?>
		var infoWindow = new google.maps.InfoWindow({
				content: 'AIzaSyDcJm10cJjXQ6cZvCH4c25x5BhZCRbvY48'
			});
	});
	</script>
</head>
<body>
<div id="hMenu">
	<div id="contH">
		<div id="logo">
			<img src="images/logo_tm_new.png" alt="tumovida"/>
		</div>
		<div id="navegacion">
			<ul>
				<li><a href="./#" title="Inicio"><img src="images/bt1.png" alt="Inicio" name="Image1" width="41" height="37" border="0"></a></li>
				<li><a href="./#!/eventos" title="Eventos"><img src="images/bt2.png" alt="Eventos" name="Image2" width="41" height="37" border="0"></a></li>
				<li><a href="./#!/promos" title="Promociones"><img src="images/bt3.png" alt="Promociones" name="Image3" width="41" height="37" border="0"></a></li>
			</ul>
		</div>
		<div id="usuario">
			<ul>
				<?php if (!isLogged()): ?>
				<li class="entrar">Entrar</li>
				<li class="registrarse">Registrarse</li>
				<?php else: ?>
				<li class=""><?=$user->getNombre()?></li>
				<li class="logout">Desconectarse</li>
				<?php endif; ?>
			</ul>
		</div>
    </div>
</div>

<div id="mapCanvas"></div>
<div id="controles">
	<a id='mostrarAbiertos'>Mostrar abiertos en este momento</a>
</div>
</body>
</html>	