<?php
session_start();
include_once '../inc/mapa.php';
require_once '../inc/conectar.php';
require_once '../inc/paginas.class.php';
require_once '../inc/usuario.class.php';
require_once '../inc/comentarios.class.php';

$id = $_GET['id'];

$c = new Conectar();
$c->TM();
$localArray = $c->query("SELECT * FROM locales WHERE id=".$id);
$localArray = mysql_fetch_assoc($localArray);
$local = new local;
$local->setPagina($localArray);
$userAction = new UsuarioActions();

if ($localArray)
{
	if (isset($_GET['newComment']) && isset($_POST['comentario'])){
		try{
			$newComment = new comentario($_POST['comentario'], "l_".$local->pagina['id']);
		}catch(Exception $e){
			echo $e->getMessage();
		}
		exit;
	}
	echo "<script>$.address.title('¡TuMovida! - ".$local->nombre(TRUE)."');</script>";	
	if (isset($_GET['json'])){
		echo json_encode($localArray);
		exit;
	}
?>	
<h2><img src="images/ico_locales.png"/><?=$local->nombre();?> <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
<div id="local">
	<div class="local-leftSide">
		<img src="images/locales/<?=$local->imagen();?>" class="imagenPerfil"/>
	</div>
	<div class="local-rightSide">
		<div class="local-menuAcciones">
			<button class="compartir button button-red">Compartir</button>
			<?php if ($local->mapa()): ?>
			<a id='mostrarMapa'></a>
			<?php endif; ?>
		</div>
		<span class="localLabel">Descripción</span>
		<div class="descripcion">
			<div class="descripcionContent">
				<?=$local->descripcion()?>
			</div>
		</div>
		<?php if($local->ubicacion() || $local->mapa()): ?>
		<span class="localLabel">Ubicación</span>
			<?=$local->ubicacion();?>
			<?php if ($local->mapa()): ?>
			<img alt="Mapa del local" src="" class='staticMap mostrarMapa'/>
			<?php endif; ?>
		<?php endif;?>
		<div style='display: block;'>
			<?php $eventosFuturos = $local->getEventosFuturos();
			if($eventosFuturos): ?>
			<div style='overflow: hidden; display: inline-block; width: 150px;'>
				<span class="localLabel">Próximo evento</span>
				<ul>
					<?php foreach($eventosFuturos as $evento):?>
					<li><?=$evento['Nombre']?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif;?>
			<?php $eventosPasados = $local->getEventosPasados();
			if($eventosPasados): ?>
			<div style='overflow: hidden; display: inline-block; width: 150px;'>
				<span class="localLabel">Eventos pasados</span>
				<ul>
					<?php foreach($eventosPasados as $evento):?>
					<li><?=$evento['Nombre']?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif;?>
			<div style='display: inline-block; width: 150px;'>
				<?php if ($local->facebook()): ?>
				<span class="eventoLabel">Facebook</span>
				<a target="_blank" href="<?=$local->facebook();?>">Ir a la página</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(".descripcion").ready(function(){
			$(".descripcion").jScrollPane({
				autoReinitialise: true,
				showArrows: true
			});
		});
		<?php if ($local->mapa()):
			$del = array("{", "}", "[", "]", "\"", "\\");
			$mapa =  str_replace(",", "_", str_replace($del, "", $local->mapa()));
			$map =  explode(",", str_replace($del, "", $local->mapa()));
		?>
		$("img.staticMap").attr("src", "http://maps.googleapis.com/maps/api/staticmap?center=<?=$map[0].",".$map[1]?>&zoom=<?=$map[2]?>&size="+$(".local-rightSide").width()+"x150&maptype=roadmap&markers=color:blue%7C<?=$map[0].",".$map[1]?>&sensor=false");
		$(".mostrarMapa").on("click", function(){
			dialog.open("ajax/mapCanvas.php?coords=<?=$mapa?>");
		});
		$("#mostrarMapa").qtip($.extend({}, tipsAbajo, {
			content: 'Ver Mapa',
			position: {
				at: "middle right",
				my: "middle left"
			}
		}));
		<?php endif; ?>
		$(".compartir").click(function(e){
			var sharer = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent("http://www.tumovida.com.uy/facebook_request.php?t=locales&id=<?=$id?>")+"&t="+encodeURIComponent(document.title);
			window.open(sharer, this.target, 'width=670,height=300');
		});
		/*$(".compartir").dropMenu('Compartir en facebook', function(res){
			var sharer = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent("http://local.tumovida.com.uy/<?=$id?>")+"&t="+encodeURIComponent(document.title);
			window.open(sharer, this.target, 'width=670,height=300');
		});*/
		$(".imagenPerfil").click(function(e){
			var maxHeight = $(window).height() - 40+"px";
			var maxWidth  = $(window).width() - 40+"px";
			dialog.show("<img src='images/locales/<?=$local->imagen();?>' style='padding: 4px;padding-bottom:0px;max-height:"+maxHeight+";max-width:"+maxWidth+";'/>", true);
		});
		</script>
	<?php include 'comentarios.php';?>
</div>
<?php
}
?>