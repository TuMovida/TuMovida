<?php
@session_start();
include "../inc/conectar.php";
include "../inc/usuario.class.php";
include "../inc/usuarioExtendido.class.php";
if(isset($_GET['mostrar']) && isset($_GET['id'])):
	$mostrar= $_GET['mostrar'];
	$id 	= $_GET['id'];
	$perfil = new Dj($id);
	if(!isLogged() || !$perfil->isAdmin()){
		die("Debes ser administrador de esta página para poder editarla");
	}
?>
	<div id="editarPerfilEx">
		<div class="editarPerfilExMenuIzquierda">
			<ul>
				<li rel="bio" <?php if($mostrar=="bio") echo "class='active'";?>>Biografía</li>
				<li rel="fotos" <?php if($mostrar=="fotos") echo "class='active'";?>>Fotos</li>
				<li rel="musica" <?php if($mostrar=="musica") echo "class='active'";?>>Música</li>
				<li rel="ubicacion" <?php if($mostrar=="ubicacion") echo "class='active'";?>>Ubicación</li>
			</ul>
		</div>
		<div class="editarPerfilExContent">
			<?php if($mostrar == "bio"):?>
			<form id='updateDjProfile'>
				<label>Biografía</label>
				<div class='editarPerfilExBackInput'>
					<textarea name='bio'><?php echo $perfil->getBio();?></textarea>
				</div>
				<input type='submit' class='button' value='Guardar'/>
			</form>
			<?php elseif($mostrar == "ubicacion"):?>
			<form id='updateDjProfile'>
				<label>Ubicación</label>
				<div class='editarPerfilExBackInput'>
					<input type="text" name="ubicacion" value="<?php echo $perfil->getUbicacion();?>"/>
				</div>
				<input type='submit' class='button' value='Guardar'/>
			</form>
			<?php elseif($mostrar == "musica"):?>
			<form id='updateDjProfile'>
				<label>Música</label>
				<?php if(!$perfil->getSoundCloud()):?>
				<img src="images/icons/btn-connect-sc-l.png" alt="Conectar con Soundcloud" width="242px" height="29px" />
				<?php else: ?>
				Esta función se incluirá proximamente
				<?php endif; ?>
			</form>
			<?php endif;?>
		</div>
	</div>
	<script type="text/javascript">
	$(".editarPerfilExMenuIzquierda li").on("click", function(){
		var rel = $(this).attr("rel");
		$("#editarPerfilEx").load("ajax/editarPerfilExtendido.php?mostrar="+rel+"&id=<?=$id?>");
	});
	$("#updateDjProfile").submit(function(e){
		e.preventDefault();
		var data = $(this).serialize();
		$.post("ajax/editarPerfilExtendido.php?dj=<?=$id?>", data, function(res){
			if(res === "Success"){
				dialog.destroy();
				createGrowl("¡Cambios realizados con éxito!", false);
				$("#main").load("ajax/dj.php?id=<?=$id?>");
			}else{
				createGrowl("Parece que hubo un error <br><b>=(</b>", false);
			}
		});
	});
	</script>
<?php
endif;
if(isset($_GET['dj']) && is_numeric($_GET['dj']))
{
	if(isLogged()){
		$dj = new Dj($_GET['dj']);
		if($dj->isAdmin()){
			if(isset($_POST)){
				$dataArray = $_POST;
				try{
					$update = new UpdateDj($dataArray, $_GET['dj']);
					if(!$update){ die("Hubo un error"); }
					else{ echo "Success"; exit; }
				}catch(Exception $e){
					echo $e->getMessage();
				}
			}
		}else{
			die("Es necesario ser administrador del perfil para continuar");
		}
	}else{
		die("Es necesario acceder como usuario para continuar");
	}
}
elseif(isset($_GET['banda']) && is_numeric($_GET['banda']))
{
	if(isLogged()){
		$banda = new Banda($_GET['dj']);
		if($banda->isAdmin()){
			//TODO
		}else{
			die("Es necesario ser administrador del perfil para continuar");
		}
	}else{
		die("Es necesario acceder como usuario para continuar");
	}
}
else{
	//die("No input");
}
?>