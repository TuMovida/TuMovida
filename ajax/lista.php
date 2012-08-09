<?php
ob_start("ob_gzhandler");
session_start();
require_once '../inc/conectar.php';
require_once '../inc/paginas.class.php';
require_once '../inc/usuario.class.php';
require_once '../inc/comentarios.class.php';

if(isset($_GET['id']) && is_numeric($_GET['id'])){
	$id = $_GET['id'];
}else{
	die("Formato de URL incorrecto");
}
// $c = new Conectar();
// $c->TM();
// $promoArray = $c->query("SELECT * FROM listas WHERE id=".$id);
// $promoArray = mysql_fetch_assoc($promoArray);
// $promo = new promocion;
// $promo->setPagina($promoArray);
try{
	$lista = new iLista($id);
}catch(Exception $e){
	echo $e->getMessage();
}
$userAction = new UsuarioActions();
if (isLogged())
	$user = new Usuario($_SESSION['idusuario']);
if ($lista)
{
	if (isset($_GET['newComment']) && isset($_POST['comentario'])){
		try{
			$newComment = new comentario($_POST['comentario'], "lt_".$lista->getID());
		}catch(Exception $e){
			echo $e->getMessage();
		}
		exit;
	}
	echo "<script>$.address.title('¡TuMovida! - ".$lista->getNombre()."');</script>";	
	if (isset($_GET['json'])){
		echo json_encode($lista->pagArray);
		exit;
	}
?>
<h2><img src="images/ico_promos.png"/><?=$lista->getNombre();?> <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
<div id="promo">
	<div class="promo-leftSide">
		<img src="images/promos/<?=$lista->getImagen();?>" />
	</div>
	<div class="promo-rightSide">
		<div class="promo-menuAcciones">
			<?php if(isLogged()):?>
			<button id="comprar" class="button button-blue">Anotar</button>
			<?php else: ?>
			<button id="comprar" class="button button-blue" disabled="disabled">Anotar</button>
			<?php endif; ?>
			<button class="compartir button button-red">Compartir</button>
			<span><?php echo $lista->getRestantes(); ?></span>
		</div>
		<span class="promoLabel">Descripción</span>
		<div class="descripcion">
			<div class="descripcionContent">
				<?=$lista->getDescripcion()?>
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
		$(".compartir").click(function(e){
			var sharer = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent("http://www.tumovida.com.uy/facebook_request.php?t=promociones&id=<?=$id?>")+"&t="+encodeURIComponent(document.title);
			window.open(sharer, this.target, 'width=670,height=300');
		});
	</script>
	<?php include 'comentarios.php';?>
</div>
<?php
}//endif($promoArray)
?>