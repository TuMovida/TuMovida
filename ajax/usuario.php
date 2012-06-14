<?php
include_once '../inc/conectar.php';
include_once '../inc/usuario.class.php';
	$id = $_GET['id'];
	if (!is_numeric($id)){
		exit("URL INVALIDA");
	}
	$usuario = new Usuario($id);
	if($usuario){
		$privacidad = new UsuarioPrivacidad($id);
		function getComentarios($id){
			$conn = new Conectar;
			$conn->TM();
			$user = mysql_real_escape_string($id);
			$q = $conn->query("SELECT * FROM comentarios WHERE id_usuario=".$user." ORDER BY Fecha DESC LIMIT 0, 10");
			if (mysql_num_rows($q) == 0)
				return false;
			while ($comentario = mysql_fetch_assoc($q)){
				$buff[] = $comentario;
			}
			return $buff;
		}
		function getAsistencias($id){
			$conn = new Conectar;
			$conn->TM();
			$user = mysql_real_escape_string($id);
			$q = $conn->query("SELECT * FROM asistencias WHERE idUsuario=".$user." AND block=0 ORDER BY id DESC");
			if (mysql_num_rows($q) == 0)
				return false;
			while ($asistencias = mysql_fetch_assoc($q)){
				$buff[] = $asistencias;
			}
			return $buff;
		}
	?>
	<h2><img height='20px' src='images/ico_usuarios.png'/><?=$usuario->getNombre(). " ". $usuario->getApellido();?> <strong class="slash b">/</strong><strong class="slash r">/</strong><strong class="slash y">/</strong></h2>
	<div id="usuario">
		<div class="left_side">
			<img class="profile_pic" src='images/user_profiles/default.png' alt='Esta es la imagen de perfil de <?=$usuario->getNombre()?>' />
		</div>
		<div class="right_side">
			<div class="user_information">
				<div class="user_information_subheader">
					<h6>Datos Personales</h6>
					<?php if ($usuario->getSexo() != NULL && $privacidad->getSexo()):?>
					<div class='user_information_row'>
						<?if($usuario->getSexo() == 'Mujer'):?>
						<span class='user_information_label'><img src='images/icons/female.png' title='Edad' alt='Edad'/></span>
						<?else:?>
						<span class="user_information_label"><img src='images/icons/male.png' title='Sexo' alt='Sexo'/></span>
						<?endif;?>
						<span class='user_information_dataDisplay'><?=$usuario->getSexo();?></span>
					</div>
					<?php endif;
					if ($usuario->getMail() != NULL && $privacidad->getMail()):?>
					<div class='user_information_row'>
						<span class='user_information_label'><img src='images/icons/email.png' title='Correo electrónico' alt='Correo electrónico' /></span>
						<span class='user_information_dataDisplay'><?=$usuario->getMail();?></span>
					</div>
					<? endif;
					if ($usuario->getDireccion() != NULL && $privacidad->getDireccion()):?>
					<div class='user_information_row'>
						<span class='user_information_label'><img src='images/icons/home.png' title='Dirección' alt='Dirección'/></span>
						<span class='user_information_dataDisplay'><?=$usuario->getDireccion();?></span>
					</div>
					<? endif;
					if ($usuario->getUbicacion() != NULL && $privacidad->getUbicacion()):?>
					<div class='user_information_row'>
						<span class='user_information_label'><img src='images/icons/building.png' title='Ubicación' alt='Ubicación'/></span>
						<span class='user_information_dataDisplay'><?=$usuario->getUbicacion();?></span>
					</div>
					<? endif;
					if ($usuario->getTelefono() != NULL && $privacidad->getTelefono()):?>
					<div class='user_information_row'>
						<span class='user_information_label'><img src='images/icons/phone.png' title='Teléfono' alt='Teléfono'/></span>
						<span class='user_information_dataDisplay'><?=$usuario->getTelefono();?></span>
					</div>
					<? endif;
					if ($usuario->getCI() != NULL && $privacidad->getCI()):?>
					<div class='user_information_row'>
						<span class='user_information_label'><img src='images/icons/vcard.png' title='Cédula de identidad' alt='Cédula de identidad'/></span>
						<span class='user_information_dataDisplay'><?=$usuario->getCI();?></span>
					</div>
					<?endif;
					if ($usuario->getNacimiento() != NULL && $privacidad->getNacimiento()):?> 
					<div class='user_information_row'>
						<span class='user_information_label'><img src='images/icons/cake.png' title='Cumpleaños' alt='Cumpleaños'/></span>
						<span class='user_information_dataDisplay'><?=$usuario->getNacimiento(true);?></span>
					</div>
					<?endif;
					if ($usuario->getEdad() != NULL && $privacidad->getEdad()):?>
					<div class='user_information_row'>
						<?if($usuario->getSexo() == 'Mujer'):?>
						<span class='user_information_label'><img src='images/icons/user_female.png' title='Edad' alt='Edad'/></span>
						<?else:?>
						<span class='user_information_label'><img src='images/icons/user.png' title='Edad' alt='Edad'/></span>
						<?endif;?>
						<span class='user_information_dataDisplay'><?=$usuario->getEdad();?> años</span>
					</div>
					<?endif;?>
				</div>
				<div class='user_information_subheader'>
					<h6>Últimos comentarios</h6>
					<div class='user_information_comentarios'>
						<? if(getComentarios($id)){
							$comentarios = getComentarios($id);
							echo "<ul>";
							$conn = new Conectar;
							$conn->TM();
							foreach($comentarios as $comentario){
								list($type, $id) = explode("_", $comentario['id_pagina']);
								if ($type == "f")
								{
									list($idAlbum, $idFoto) = explode("-", $id);
									$res = mysql_fetch_assoc($conn->query("SELECT id, Album FROM  eventos_fotos WHERE id=".$idAlbum));
									$nombrePagina = $res['Album'];
									$href = "#!/album/".$idAlbum."/foto/".$idFoto;
								}else{
									$a = array("e" => "eventos", "l" => "locales", "p" => "promociones");
									$b = array("e" => "evento", "l" => "local", "p" => "promo");
									$href = "#!/".$b[$type]."/";
									$type = $a[$type];
									$res = mysql_fetch_assoc($conn->query("SELECT id, Nombre FROM ".$type." WHERE id=".$id));
									$nombrePagina = $res['Nombre'];
									$href = $href.$res['id'];
								}
								if(strlen($nombrePagina) > 35)
									$nombrePagina = substr($nombrePagina, 0, 33)."...";
								$nombrePagina = "<a href='$href'>".$nombrePagina."</a>";
								$fecha =  time() - strtotime($comentario['fecha']);
								if ($fecha > 60){
									$fecha = round($fecha / 60);
									if ($fecha > 60){
										$fecha = round($fecha / 60);
										if ($fecha > 24){
											$fecha = round($fecha /24);
											($fecha > 1) ? $fecha = $fecha." días" : $fecha = $fecha." día";
										}else{
											$fecha = $fecha." minutos";
										}
									}else
										$fecha = $fecha. " segundos";
								}
								echo "<li class='comentario'><q>".$comentario['texto'].
								"</q><span class='nombrePagina'>Hace $fecha en ".$nombrePagina."</span></li>";
							}
							echo "</ul>";
						}else{
							if ($usuario->getSexo() == "Mujer"){
								echo "<span class='conjunto-vacio'>".$usuario->getNombre()." esta muy callada últimamente.</span>";
							}else{
								echo "<span class='conjunto-vacio'>".$usuario->getNombre()." esta muy callado últimamente.</span>";	
							}
						}
						?>
					</div>
				</div>
				<div class='user_information_subheader'>
					<h6>Últimas asistencias</h6>
					<div class='user_information_asistencias'>
					<? if(getAsistencias($_GET['id'])){
							$asistencias = getAsistencias($_GET['id']);
							echo "<ul>";
							$conn = new Conectar;
							$conn->TM();
							foreach($asistencias as $asistencia){
								$res = mysql_fetch_assoc($conn->query("SELECT id, Nombre, Imagen FROM eventos WHERE id=".$asistencia['idEvento']));
								$nombrePagina = $res['Nombre'];
								$nombrePagina = "<li class='asistenciaEvento'><img src='images/eventos/".$res['Imagen']."' /><a href='#!/evento/".$res['id']."'>".$nombrePagina."</a></li>";
								echo $nombrePagina;
							}
							echo "</ul>";
						}else{
							echo "<span class='conjunto-vacio'>".$usuario->getNombre()." no ha asistido a ningún evento aún =( <br/> ¡Invitalo! =D</span>";
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="user-footer">
			<h6>¿Te gustaría juntarte con <?=$usuario->getNombre()?>?</h6>
				<button class="button enviarMensaje">Envía un mensaje</button>
				<button class="button invitaEvento">Invitar a un evento</button>
		</div>
	</div>
	<script type="text/javascript">
	$(".enviarMensaje").click(function(e){
		dialog.open("ajax/enviarMensaje.php?para=<?=$usuario->getNombre()." ".$usuario->getApellido()?>&id=<?=$id?>");
	});
	$(".invitaEvento").click(function(e){
		//dialog.show("<div class='formulario'>Próximamente en ¡TuMovida!</div>");
		dialog.open("ajax/enviarInvitacion.php?id=<?=$id?>");
	});
	</script>
	<?php
	}
?>