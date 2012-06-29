<?php 
ob_start("ob_gzhandler");
header('Content-Encoding: gzip');
header('Vary: Accept-Encoding');
date_default_timezone_set('America/Montevideo');
header("Content-type: text/html; charset='utf8'");
@session_start();
include "inc/conectar.php";
include "inc/usuario.class.php";
include "inc/paginas.class.php";
include "inc/compra.class.php";
include "inc/comentarios.class.php";
include "inc/destacados.php";
include "inc/lqsv.php";
include "inc/calendario.php";
include "inc/comenta.php";
if (isLogged()){
	$user = new Usuario($_SESSION['idusuario']);
}

?>
<!DOCTYPE html>
<html lang="es" class="no-js">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>¡TuMovida!</title>
<link rel="shortcut icon" href="favicon.ico">
<link href="css/compile.min.css" rel="stylesheet" type="text/css"/>
<link href="css/jquery.jscrollpane.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDcJm10cJjXQ6cZvCH4c25x5BhZCRbvY48&sensor=true"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/swapImg.js"></script>
<script type="text/javascript" src="js/setPos.js"></script>
<script type="text/javascript" src="js/destacados.js"></script>
<script type="text/javascript" src="js/qtip/jquery.qtip.min.js"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<script type="text/javascript" src="js/jquery.address-1.4.min.js"></script>
<link href="js/qtip/jquery.qtip.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="js/navegacion.js"></script>
<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>
<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="js/jquery.lazyloading.min.js"></script>
<script type="text/javascript" src="js/user.js"></script>
<script type="text/javascript" src="js/tips.js"></script>
<script type="text/javascript" src="js/notificaciones.js"></script>
<script type="text/javascript" src="js/photoDisplay.js"></script>
<script type="text/javascript" src="js/slider.js"></script>
<style>
.slide_sumate { position:relative; height:94px; width:90px;}
.slide_sumate img { border-radius: 10px; position:absolute; left:0; top:0; }
</style>
<script type="text/javascript">
$(function(){
	$('.slide_sumate img:gt(0)').hide();
	setInterval(function(){$('.slide_sumate :first-child').fadeOut().next('a').fadeIn().end().appendTo('.slide_sumate');}, 3600);
});
$(document).on("ready",function(){	
	//photoDisplay.open("ajax/photoWelcome.php?indexInterface");
	$("#formulario-comentarios").submit(function(e){e.preventDefault(); $(".botonEnviarComentarios").attr("disabled", "disabled"); $.post("ajax/enviarComentarioVisitas.php",$("#formulario-comentarios").serialize(),function(res){if (res == 1){$(".botonEnviarComentarios").attr("disabled", "false");	$("#comentarios").load("index.php #comentarios");}});}); 
	<? if(isLogged() && (!$user->getCI())): ?> dialog.show("<div class='formulario'><label>Aún no haz ingresado una CI.<br />Para realizar compras debes tener una. Puedes hacerlo ahora <a href='#!/editarPerfil'>editando tu perfil</a></label></div>");
<? endif; ?>
});
</script>
</head>
<body>
<div id="hMenu">
	<div id="contH">
	<div id="logo">
		<img style='display: none;' src="images/logo_tm_new.png" alt="tumovida"/>
	</div>
	<div id="navegacion">
		<ul>
			<li><a href="#" title="Inicio"><img src="images/bt1.png" alt="Inicio" name="Image1" width="41" height="37" border="0"></a></li>
			<li><a href="#!/eventos" title="Eventos"><img src="images/bt2.png" alt="Eventos" name="Image2" width="41" height="37" border="0"></a></li>
			<li><a href="#!/promos" title="Promociones"><img src="images/bt3.png" alt="Promociones" name="Image3" width="41" height="37" border="0"></a></li>
			<li title="Nueva sección de fotos"><a href="#!/fotos" title="Fotos"><img src="images/bt4.png" alt="Fotos" name="Image4" width="41" height="37" border="0"></a></li>
		</ul>
	</div>
	<div id="usuario">
		<ul>
			<?php if (!isLogged()): ?>
			<li class="entrar">Entrar</li>
			<li class="registrarse">Registrarse</li>
			<?php else: ?>
			<li class="usuarioNombre"><?=$user->getNombre()?></li>
			<ul data-role='drop-menu' rel='usuarioNombre' class='usuario-dropMenu'>
				<span class='userMenu'><a class='verPerfil' href="#!/usuario/<?=$_SESSION['idusuario']?>">Ver Perfil</a></span>
				<span class='userMenu'><a class='editarPerfil' href='#!/editarPerfil'>Editar Perfil</a></span>
				<span class='userMenu'><a class='misMensajes' href='#'>Mis Mensajes</a></span>
				<span class='userMenu'><a class='misInvitaciones' href='#'>Mis Invitaciones</a></span>
				<span class='userMenu'><a class='misCompras' href='#'>Mis Compras</a></span>
			</ul>
			<li class="logout">Desconectarse</li>
			<?php endif; ?>
		</ul>
	</div>
    </div>
</div>
<div id="destacados" class="index" style="display: none;">
	<?php 
	$destacados = new destacados();
	$json = json_decode($destacados->getJSON(), true);
	foreach ($json as $destacado):
	?>
	<div class="destacado">
		<div class="destacado_imagen" style="background: url('images/destacados/<?=$destacado['Imagen']?>');"></div>
		<div class="destacado_descripcion">
		<span class='destacado_titulo'><?=$destacado['Titulo']?></span>
		<span style='font-size: 12px;'><?=$destacado['Descripcion']?></span>
		<div style='text-align: right;'>
			<?php if( $destacado['Tipo'] == "Evento" ): ?>
			<div class="button button-red" onclick="$.address.path('evento/<?=$destacado['Enlace']?>')">Ver Evento</div>
			<?php elseif ( $destacado['Tipo'] == "Local" ): ?>
			<div class="button button-red" onclick="$.address.path('local/<?=$destacado['Enlace']?>')">Ver Local</div>
			<?php endif; 
			if ($destacado['Tipo'] == "Evento" && $destacado['Entradas']):
			?>
			<div class="button button-blue" onclick="$.address.path('promo/<?=$destacado['Entradas']?>')">Comprar Entradas</div>
			<?php endif; ?>
		</div>
		</div>
	</div>
	<?php 
	endforeach;
	?>
	<div id="logoTM">
		<img src="images/tumovida.png" alt="¡TuMovida!"/>
	</div>
</div>
<div id="container">
	<div id="left">
		<div class="slide_sumate">
			<a><img src="images/sumate_slider/rrpp.jpg" /></a>
			<a><img src="images/sumate_slider/show.jpg" /></a>
			<a><img src="images/sumate_slider/evento.jpg" /></a>
			<a><img src="images/sumate_slider/barra.jpg" /></a>
		</div>
		
		<div id="lqsv" class="moduloLeft">
			<h2>Lo que se viene <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
			<!--<div class="lqsv_entradas">
			<?php
				$lqsv = new lqsv();
				$eventos = json_decode($lqsv->getJSON(), true);
				foreach ($eventos as $e){
				?>
				<div class="lqsv_entrada" onclick="$.address.value('evento/<?=$e['id']?>')">
					<div class="lqsv_img">
						<img src="images/eventos/<?=$e['Imagen']?>" alt="<?=$e['Nombre']?>" />
					</div>
					<div class="lqsv_data">
						<h4><?=$e['Nombre']?></h4>
						<span class="lqsv_fecha"><?=$e['Fecha']?></span>
					</div>
				</div>
				<?
				}
			?>
			</div>-->
			<?php
				echo draw_calendar(date("m"), date("Y"));
			?>
		</div>
		<div id="comentarios" class="moduloLeft">
			<h2>Comentarios <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
			<div id="comenta_box">
				<?php
				$comenta = new comenta();
				$comentarios = json_decode($comenta->getJSON(), true);
				foreach ($comentarios as $coment){
					if ($coment['destacado'] == 1)
						$advanceClass = 'comenta_entradaDestacada';
					elseif ($coment['admin'] == 1)
						$advanceClass = 'comenta_entradaAdmin';
					else
						$advanceClass = '';
					?>
					<div class="comenta_entrada <?=$advanceClass?>">
						<span class="comenta_usuario"><?=$coment['Usuario']?>: </span>
						<span class="comenta_mensaje"><?=$coment['Mensaje']?></span>
					</div>
					<?
					}
				?>
			</div>
			<script>$(document).ready(function(){$("#comenta_box").jScrollPane({autoReinitialise: true, showArrows: true});});</script>
			<div id="comenta_formulario">
			<form id="formulario-comentarios">
				<input type="text" placeholder="nombre" name="usuario" required/>
				<input type="text" placeholder="mensaje" name="mensaje" required/>
				<input type="email" placeholder="email" name="mail" required/>
				<input type="submit" value="Enviar" class="botonEnviarComentarios button button-blue"/>
			</form>
			</div>
		</div>
	</div>
	<div id="right">
		<div id="main" class="moduloRight">
		</div>
	</div>
</div>
<?php include "inc/footer.inc"; ?>
<?php include "inc/piwik.inc"; ?>
</body>
</html>