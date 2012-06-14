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
		<img src="images/locales/<?=$local->imagen();?>" />
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
		<span class="localLabel">Ubicación</span>
		<?=$local->ubicacion();?>
	</div>
	<script type="text/javascript">
		$(".descripcion").ready(function(){
			$(".descripcion").jScrollPane({
				autoReinitialise: true,
				showArrows: true
			});
		});
		<?php if ($local->mapa()):
			$del = array("{", "}", "[", "]", "\"");
			$mapa =  str_replace(",", "_", str_replace($del, "", $local->mapa()));
		?>
		$("#mostrarMapa").on("click", function(){
			dialog.open("ajax/mapCanvas.php?coords=<?=$mapa?>");
		});
		$("#mostrarMapa").qtip({
			content: '<b>Ver Mapa</b>',
			show: {
				event: false,
				ready: true
			},
			position: {
				at: "middle right",
				my: "middle left"
			},
			hide: false,
			style: {
				classes: 'ui-tooltip-shadow ui-tooltip-light'
			}
		});
		<?php endif; ?>
		$(".compartir").click(function(e){
			var sharer = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent("http://www.tumovida.com.uy/facebook_request.php?t=locales&id=<?=$id?>")+"&t="+encodeURIComponent(document.title);
			window.open(sharer, this.target, 'width=670,height=300');
		});
		/*$(".compartir").dropMenu('Compartir en facebook', function(res){
			var sharer = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent("http://local.tumovida.com.uy/<?=$id?>")+"&t="+encodeURIComponent(document.title);
			window.open(sharer, this.target, 'width=670,height=300');
		});*/
		</script>
	<?php include 'comentarios.php';?>
</div>
<?php
}
?>