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
	<div class='misMensajes'>
	<?php 
	if (isset($_GET["load"]) && is_numeric($_GET["load"])){
		try{
			$mensaje = new getMensaje($_GET["load"]);
			$emisor = new usuario($mensaje->getEmisor());
		}catch(Exception $e){
			echo $e->getMessage();
			exit;
		}
		?>
		<h3><a class="retrocederMain" href="javascript:void(0)">Mis mensajes</a> › <?php echo $emisor->getNombre();?></h3>
		<div class='VistaMensaje'>
			<div class="VistaMensajeLista">
				<div class="mensaje"><?php echo $mensaje->getTexto(); ?></div>
			</div>
			<div class="enviarMensaje">
				<div style="background:white;border:1px solid rgb(207,207,207);border-radius:4px;padding:4px;">
					<textarea rows="2" placeholder="Escribe tu mensaje..." class="enviarMensajeTexto"></textarea>
					<button id="enviarMensaje" class="button" style="display:block;width:100%;">Enviar</button>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$("div.misMensajes .retrocederMain").on("click", function(e){
			e.preventDefault();
			$.ajax({
				url: "ajax/misMensajes.php",
				success: function(res){
					$(".dialog-content").html(res);
				}
			});
		});
		</script>
		<?php
		exit;
	}
	try{
		?>
		<h3>Mis mensajes</h3>
		<?php
		$mensajes = new getMensajes();
		if ($mensajes){
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
		echo $e->getMessage();
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
			$(".dialog-content").html(res);
		}
	});
});
</script>