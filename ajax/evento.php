<?php
ob_start("ob_gzhandler");
session_start();
require_once '../inc/conectar.php';
require_once '../inc/paginas.class.php';
require_once '../inc/usuario.class.php';
require_once '../inc/comentarios.class.php';

$id = $_GET['id'];

if(!is_numeric($id))
{
	exit("URL INVALIDA");
}

$c = new Conectar();
$c->TM();
$eventoArray = $c->query("SELECT * FROM eventos WHERE id=".$id);
$eventoArray = mysql_fetch_assoc($eventoArray);
$evento = new pagina;
$evento = new evento;
$evento->setPagina($eventoArray);
$userAction = new UsuarioActions();
if ($eventoArray)
{
	if (isset($_GET['newComment']) && isset($_POST['comentario'])){
		try{
			$newComment = new comentario($_POST['comentario'], "e_".$evento->pagina['id']);
		}catch(Exception $e){
			echo $e->getMessage();
		}
		exit;
	}
	if(isset($_GET['setAsistir'])){
		if ($userAction && isLogged()){  
				$userAction->setAsistir($evento->pagina['id'], $_SESSION['idusuario']);
		}else{
			echo "Hubo un error, probablemente no hayas iniciado sesión";
		}	
		exit;
	}
	if ($userAction && isLogged()){ 
		$asistencia = $userAction->getAsistir($evento->pagina['id'], $_SESSION['idusuario']);
		if ($asistencia){
			$asistir = "button button-blue";
			$isBlocked = mysql_fetch_row($c->query("SELECT block FROM asistencias WHERE id=".$asistencia));
			if ($isBlocked[0] == 1)
				$asistir = "button button-red";
		}else{
			$asistir = "button button-red";
		}
	}else{
		$asistir = "button button-red";
	}
	if(isset($_GET['asistirRequestClass'])){
		echo $asistir;
		exit;
	}
	/* setTitle */
	echo "<script>$.address.title('¡TuMovida! - ".str_replace("*", "", $evento->nombre(TRUE))."');</script>";	
	if (isset($_GET['json'])){
		echo json_encode($eventoArray);
		exit;
	}
	$evento->setVisita();
?>
<h2><img src="images/ico_eventos.png"/><?=$evento->nombre();?> <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
<div id="evento">
	<div class="evento-leftSide">
		<img src="images/eventos/<?=$evento->imagen();?>" />
	</div>
	<div class="evento-rightSide">
		<div class="evento-menuAcciones">
			<button id="asistire" class="<?=$asistir?>">Asistiré</button>
			<?php if ($evento->getPromo()):?>
			<button id="comprar" class="button button-red">Comprar</button>
			<?php endif; ?>
			<button class="compartir button button-red">Compartir</button>
			<?php if($evento->mapa()): ?>
			<a class='mostrarMapa' id='mostrarMapa'></a>
			<?php endif; ?>
		</div>
		<span class="eventoLabel">Descripción</span>
		<div class="descripcion">
			<div class="descripcionContent">
				<?=$evento->descripcion()?>
			</div>
		</div>
		<?php if($evento->ubicacion() || $evento->mapa()): ?>
		<span class="eventoLabel">Ubicación</span>
		<?=$evento->ubicacion();?>
		<?php if ($evento->mapa()): ?>
		<img alt="Mapa del evento" src="" class='staticMap mostrarMapa'/>
		<?php endif; ?>
		<?php endif;?>
		<div style='display: block;'>
			<div style='overflow: hidden; display: inline-block; width: 150px;'>
				<span class="eventoLabel">Fecha</span>
				<?=$evento->fecha("completa");?>
			</div>
			<div style='overflow: hidden; display: inline-block; width: 150px;'>
				<?php if ($evento->edad()): ?>
				<span class="eventoLabel">Edad</span>
				<?=$evento->edad();?>
				<?php endif; ?>
			</div>
			<div style='display: inline-block;'>
				<?php if ($evento->facebook()): ?>
				<span class="eventoLabel">Facebook</span>
				<a target="_blank" href="<?=$evento->facebook();?>">Ir a la página</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		<?php if ($evento->getPromo()): ?>
		$("#comprar").qtip($.extend({}, tipsAbajo, {
			content: 'Ir a la promoción'
		}));
		$("#comprar").click(function(e){
			$.address.path("promo/<?=$evento->getPromo();?>");
		});
		<?php endif; ?>
		$("#asistire").qtip({
			content: function(){
				if ($(this).attr("class") == "button")
					return "<b>Marcar asistencia</b><br />Se mostrarán fotos del evento la próxima vez que inicies sesión si estan disponibles.";
				return "<b>Desmarcar asistencia</b><br />No se mostrarán fotos pasada la fecha del evento";
			},
			position: {
				my: 'top center',
				at: 'bottom center'
			},
			style: 'ui-tooltip-light'
		});
		$("#asistire").on("click", function(e){
			var idEvento = <?=$evento->pagina['id']?>;
			$.ajax({
				url: 'ajax/evento.php?setAsistir&id='+idEvento,
				success: function(res){
					if (res != ""){
						alert(res);
						return false;
					}
					$.ajax({
						url: "ajax/evento.php?id="+idEvento+"&asistirRequestClass",
						success: function(dataClass){
							$("#asistire").attr("class", dataClass);
						}
					});
				}
			});
		});
		$(".descripcion").ready(function(){
			$(".descripcion").jScrollPane({
				autoReinitialise: true,
				showArrows: true
			});
		});
		<?php if ($evento->mapa()):
			$del = array("{", "}", "[", "]", "\"", "\\");
			$mapa =  str_replace(",", "_", str_replace($del, "", $evento->mapa()));
			$map =  explode(",", str_replace($del, "", $evento->mapa()));
		?>
		$("img.staticMap").attr("src", "http://maps.googleapis.com/maps/api/staticmap?center=<?=$map[0].",".$map[1]?>&zoom=<?=$map[2]?>&size="+$(".evento-rightSide").width()+"x150&maptype=roadmap&markers=color:blue%7C<?=$map[0].",".$map[1]?>&sensor=false");
		$(".mostrarMapa").on("click", function(){
			dialog.open("ajax/mapCanvas.php?coords=<?=$mapa?>");
		});
		$("#mostrarMapa").qtip($.extend({}, tipsAbajo, {
			content: 'Ver Mapa'
		}));
		<?php endif; ?>
		$(".compartir").click(function(e){
			var sharer = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent("http://www.tumovida.com.uy/facebook_request.php?t=eventos&id=<?=$id?>")+"&t="+encodeURIComponent(document.title);
			window.open(sharer, this.target, 'width=670,height=300');
		});
		</script>
	<?php include 'comentarios.php';?>
</div>
<?
}else{
?>
	<h2><img src="images/ico_eventos.png"/>404 - Evento no válido <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
	<i>No se puede encontrar un evento para la URL especificada</i>
<?php
}	
?>