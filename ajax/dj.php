<?php
@session_start();
include_once '../inc/conectar.php';
include_once '../inc/usuario.class.php';
include_once '../inc/usuarioExtendido.class.php';
	$id = $_GET['id'];
	if (is_numeric($id)){
		$perfil = new Dj($id);
	}else{
		die("Hay algo raro en la URL. Revisela!");
	}
	if ($perfil){
	?>
	<h2><img height='20px' src='images/ico_usuarios.png'/><?=$perfil->getNombre()?> <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
	<div id="usuario">
		<?php 
		if ($perfil->isAdmin()){
			echo "Yo soy administrador";
		}
		?>
		<div class="left_side">
			<?php if ($perfil->getFoto()): ?>
			<img class="profile_pic" src='<?php $perfil->getFoto()?>' alt='Esta es la imagen de perfil de <?=$perfil->getNombre()?>' />
			<?php endif; ?>
			<img class="profile_pic" src='images/user_profiles/default.png' alt='Esta es la imagen de perfil de <?=$perfil->getNombre()?>' />
		</div>
		<div class="right_side">
			<div class="user_information">
				<div class="user_information_subheader">
					<h6>Biografía</h6>
					<?php if ($perfil->getBio()){
						echo $perfil->getBio();
					}else{
						echo $perfil->getNombre() . " aún no escribió su biografía. ¿Deseas <a>enviarle sugerencias</a>?";
					}
					?>
				</div>
			</div>
			<div class="user_information">
				<?php if ($perfil->getUbicacion()):?>
				<div class="user_information_subheader">
					<h6>Ubicación</h6>
					<?php echo $perfil->getUbicacion(); ?>
				</div>
				<?php endif;?>
			</div>
		</div>
		<div class="user-footer">
			<h6>¿Te gustaría ver a <?=$perfil->getNombre()?> en vivo?</h6>
				<button class="button enviarMensaje">Envía un mensaje</button>
				<button class="button invitaEvento">Invitar a un evento</button>
		</div>
	</div>
	<?php
	}else{
		die("El DJ al que usted esta intentando acceder no existe");
	}
?>