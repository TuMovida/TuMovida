<?php
/*		MIS COMPRAS		*/
@session_start();
include_once "../inc/conectar.php";
include_once "../inc/paginas.class.php"; 
include_once "../inc/usuario.class.php";
include_once "../inc/mail.class.php";
include_once "../inc/mensajes.class.php";

if (!isLogged()) die("Nececitas iniciar sesión para leer tus mensajes");
?>
<div class='misOpciones'>
	<h3>Mis compras</h3>
	<?php
	$user = new Usuario($_SESSION['idusuario']);
	$compras = $user->getCompras();
	if($compras){
	?>
	<div class='misCompras'>
		<?php
		foreach($compras as $compra){
			?>
			<div class='compra'>
				<div class='estadoCompra'>
				<?php switch ($compra['Estado']){
						case 0: echo "NO";
						break;
						case 1: echo "SI";
						break;
				}?>
				</div>
				<div class='infoCompra'>	
					<span class='nombreCompra'><?php echo $compra['Descripcion'] ?></span>
					<span class='cantidadCompra'>Cantidad: <?php echo $compra['Cantidad'] ?></span>
					<span class='importeCompra'>Importe: $<?php echo $compra['Importe'] ?></span>
				</div>
			</div>
			<?php
		}
		?>
	</div>	
	<?php
	}else{
		echo "No hiciste compras todavía";
	}
	?>
</div>