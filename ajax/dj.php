<?php
ob_start("ob_gzhandler");
@session_start();
include_once '../inc/conectar.php';
include_once '../inc/usuario.class.php';
include_once '../inc/usuarioExtendido.class.php';
include_once '../inc/comentarios.class.php';
	if (!isset($_GET['id'])){
		//header("HTTP/1.0 404 Not Found");
		//header("Status: 404 Not Found");
		die;
	}
	$id = $_GET['id'];
	if (is_numeric($id)){
		$perfil = new Dj($id);
		if (!$perfil){
			echo "404";
			exit;
		}
	}else{
		die("Hay algo raro en la URL. Revisela!");
	}
	if ($perfil){
		if (isset($_GET['newComment']) && isset($_POST['comentario'])){
			try{
				$newComment = new comentario($_POST['comentario'], "dj_".$id);
			}catch(Exception $e){
				echo $e->getMessage();
			}
			exit;
		}
		$dj = true;
	?>
	<h2><img height='20px' src='images/ico_usuarios.png'/><?=$perfil->getNombre()?> <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
	<div id="usuario">
		<div class="left_side">
			<?php if ($perfil->getFoto()): ?>
			<img class="profile_pic" src='<?php $perfil->getFoto()?>' alt='Esta es la imagen de perfil de <?=$perfil->getNombre()?>' />
			<?php endif; ?>
			<img class="profile_pic" src='images/user_profiles/default.png' alt='Esta es la imagen de perfil de <?=$perfil->getNombre()?>' />
			<div class="user-footer">
				<h6 style="font-size: 16px; margin-bottom: 10px;">¿Te gustaría ver a <?=$perfil->getNombre()?> en vivo?</h6>
				<button class="button enviarMensaje">Envía un mensaje</button>
				<button class="button invitaEvento">Invitar a un evento</button>
			</div>
		</div>
		<div class="right_side">
			<div class="user_information">
				<div class="user_information_subheader">
					<h6 class="contenidoEditable" data-rel="musica">Música</h6>
				</div>
				<?php if($perfil->getSoundCloud()): ?>
				<iframe width="100%" height="200px" frameborder="no" id="tmPlayer" 
					src="http://w.soundcloud.com/player/?url=http://api.soundcloud.com/users/4036539" />
				<?php else: 
					if ($perfil->isAdmin()):?>
					<span class="conjunto-vacio">Aún no haz agregado tu set</span>
					<?php else:?> 
					<span class="conjunto-vacio">Esperemos que <?php echo $perfil->getNombre() ?> suba sus temas pronto</span>
					<?php endif; ?>
				<?php endif;?>
				<div class="user_information_subheader">
					<h6 class="contenidoEditable" data-rel="fotos">Fotos</h6>
				</div>
				<div class="usuarios_albums">
					<div class="usuario_albums_borde">
						<div class="usuario_albums_album">
							<img class="usuario_albums_album_imagen" src="images/fotos/eventos/test2.jpg" />
							<span class="usuario_albums_album_nombre">Prueba 1</span>
						</div>
					</div>
					<div class="usuario_albums_borde">
						<div class="usuario_albums_album">
							<img class="usuario_albums_album_imagen" src="images/fotos/eventos/test3.jpg" />
							<span class="usuario_albums_album_nombre">Prueba 2</span>
						</div>
					</div>
				</div>
				<div class="user_information_subheader">
					<h6 class="contenidoEditable" data-rel="bio">Biografía</h6>
					<?php if ($perfil->getBio()){
						echo "<span class='usuarioEx_contentInformation'>".nl2br($perfil->getBio())."</span>";
					}else{
						if($perfil->isAdmin()){
							echo "<span class='usuarioEx_contentInformation'>Añade una descripción</span>";
						}else{
							echo "<span class='usuarioEx_contentInformation'>".$perfil->getNombre() . " aún no escribió su biografía. ¿Deseas <a>enviarle sugerencias</a>?</span>";
						}
					}
					?>
				</div>
			</div>
			<div class="user_information">
				<?php if ($perfil->getUbicacion()):?>
				<div class="user_information_subheader">
					<h6 class="contenidoEditable" data-rel="ubicacion">Ubicación</h6>
					<?php echo "<span class='usuarioEx_contentInformation'>".$perfil->getUbicacion()."</span>"; ?>
				</div>
				<?php endif;?>
			</div>
		</div>
		<div class="user-footer comentariosPerfilEx" style="text-align: left;">
			<h6>Comentarios</h6>
			<?php include "comentarios.php"; ?>
		</div>
	</div>
	<?php if ($perfil->isAdmin()): ?>
	<div class='editorSeñalador' style='border-radius:4px; position: absolute; display: none; background: rgba(6, 187, 255, 0.8); z-index: 6;'></div>
	<script type="text/javascript">
	$(".contenidoEditable").hover(function(){
		var position= $(this).position();
		var width 	= $(this).width()+4;
		var	height 	= $(this).height()+4;
		var margin 	= $(this).css("margin");
		var padding	= $(this).css("padding");
		var zindex 	= $(this).css("z-index");
		$(".editorSeñalador")
			.width(width)
			.height(height)
			.css('top', position.top-2)
			.css('left', position.left-2)
			.css('margin', margin)
			.css('padding', padding)
			.css('z-index', zindex-1)
			.fadeIn('250');
	}, function(){
		$(".editorSeñalador").mouseout(function(){
			$(".editorSeñalador").fadeOut('250');
		});
	});
	$(".contenidoEditable").on("click", function(e){
		var dataRel= $(this).attr("data-rel");
		switch(dataRel){
			case "bio": dialog.open("ajax/editarPerfilExtendido.php?mostrar=bio&id=<?=$id?>"); break;
			case "fotos": dialog.open("ajax/editarPerfilExtendido.php?mostrar=fotos&id=<?=$id?>"); break;
			case "musica": dialog.open("ajax/editarPerfilExtendido.php?mostrar=musica&id=<?=$id?>"); break;
			case "ubicacion": dialog.open("ajax/editarPerfilExtendido.php?mostrar=ubicacion&id=<?=$id?>"); break;
		}
	});
	</script>
	<?php endif; ?>	
	<?php
	}else{
		die("El DJ al que usted esta intentando acceder no existe");
	}
?>