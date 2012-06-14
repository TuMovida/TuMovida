<?php
/*		MIS MENSAJES		*/
@session_start();
include_once "../inc/conectar.php";
include_once "../inc/paginas.class.php"; 
include_once "../inc/usuario.class.php";
include_once "../inc/mail.class.php";
include_once "../inc/mensajes.class.php";

if (!isLogged()) die("Nececitas iniciar sesión para leer tus mensajes");
?>
<div class='misOpciones'>
	<h3>Mis mensajes</h3>
	<?php 
	if (isset($_GET["load"])):
		?>
		<div class='VistaMensaje'>
			<?php 
			$mensaje = new getMensajes();
			?>
		</div>
		<?php
		exit;
	endif;
	?>
	<?php
	try{
		$mensajes = new getMensajes();
		if ($mensajes){
			?>
			<div class='misMensajes'>
			<?php
			foreach ($mensajes->getList() as $mensaje)
			{
				$usuarioEmitor = new Usuario($mensaje['idEmisor']);
				?>
				<div class="mensaje <?php echo ($mensaje['Visto']) ? 'mensajeLeido' : NULL ?> leerMensaje" rel="<?php echo $mensaje['id']?>">
					<div class='leftSide'>
						<img src=''>
					</div>
					<div class='rightSide'>
						<span class='nombreEmitor'><?php echo $usuarioEmitor->getNombre()." ".$usuarioEmitor->getApellido(); ?></span>
						<span class='texto'><?php echo $mensaje['Texto']; ?></span>
					</div>
				</div>
				<?php
			}
			?>
			</div>
			<?php
		}
	}catch(Exception $e){
		echo $e;
	}
	?>
</div>
<script type="text/javascript">
$("div.misMensajes .leerMensaje").on("click", function(e){
	e.preventDefault();
	var msjID = $(this).attr("rel");
	$.ajax({
		url: "ajax/misMensajes.php?load=" + msjID,
		success: function(res){
			$(".misOpciones").html(res);
		}
	});
});
</script>