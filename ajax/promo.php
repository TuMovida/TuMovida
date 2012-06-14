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
$promoArray = $c->query("SELECT * FROM promociones WHERE id=".$id);
$promoArray = mysql_fetch_assoc($promoArray);
$promo = new promocion;
$promo->setPagina($promoArray);
$userAction = new UsuarioActions();
if (isLogged())
	$user = new Usuario($_SESSION['idusuario']);
if ($promoArray)
{
	if (isset($_GET['newComment']) && isset($_POST['comentario'])){
		try{
			$newComment = new comentario($_POST['comentario'], "p_".$promo->pagina['id']);
		}catch(Exception $e){
			echo $e->getMessage();
		}
		exit;
	}
	echo "<script>$.address.title('¡TuMovida! - ".$promo->nombre(TRUE)."');</script>";	
	if (isset($_GET['json'])){
		echo json_encode($promoArray);
		exit;
	}
?>	
<h2><img src="images/ico_promos.png"/><?=$promo->nombre();?> <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
<div id="promo">
	<div class="promo-leftSide">
		<img src="images/promos/<?=$promo->imagen();?>" />
	</div>
	<div class="promo-rightSide">
		<div class="promo-menuAcciones">
			<button id="comprar" class="button button-blue">Comprar</button>
			<button class="compartir button button-red">Compartir</button>
		</div>
		<span class="promoLabel">Descripción</span>
		<div class="descripcion">
			<div class="descripcionContent">
				<?=$promo->descripcion()?>
			</div>
		</div>
		<? if ($promo->condiciones()):?>
		<span class="promoLabel">Condiciones</span>
		<ul>
			<?php
			$p = explode("*", $promo->condiciones());
			foreach ($p as $cond){
				echo "<li class='promoCondicion'>".str_replace("*", "", $cond);
			}
			?>
		</ul>
		<?endif;?>
		<span class="promoLabel">Valor</span>
		<div class="promoValor">
			<div class="precioValorImg">
				<span><?=$promo->valor(true)?></span>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$("#comprar").qtip($.extend({}, tipsAbajo, {
		<?php if (!isLogged()){ ?>
			content: "Debes <b>iniciar sesión</b> para poder realizar compras"
		<?php }elseif(!$user->getCI()){ ?>
			content: "Para relizar compras es necesario introducir tu <b>Cécula de Identidad</b><br />Por favor <a href='#!/editarPerfil'>edita tu perfil</a>"
		<?php }else{ ?>
			content: "Haz click aquí para selecionar la cantidad"
		<?php }?>
		}));
		$("#comprar").click(function(e){
			dialog.open("ajax/comprar.php?id=<?php echo $promoArray['id']; ?>");
		});
		$(".descripcion").ready(function(){
			$(".descripcion").jScrollPane({
				autoReinitialise: true,
				showArrows: true
			});
		});
		$(".compartir").click(function(e){
			var sharer = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent("http://www.tumovida.com.uy/facebook_request.php?t=promociones&id=<?=$id?>")+"&t="+encodeURIComponent(document.title);
			window.open(sharer, this.target, 'width=670,height=300');
		});
		piwikTracker.addEcommerceItem(
		"<?php echo $promoArray['id']; ?>",
		"<?=$promo->nombre()?>",
		"",
		<?=$promo->pagina['Valor']?>,
		1
		);
		piwikTracker.setEcommerceView(
		"<?php echo $promoArray['id']; ?>",
		"<?=$promo->nombre()?>",
		"",
		<?=$promo->pagina['Valor']?>
		);
		piwikTracker.trackPageView();
	</script>
	<?php include 'comentarios.php';?>
</div>
<?php
}
?>