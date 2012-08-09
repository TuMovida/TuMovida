<?php
if (isset($evento)){
	$page = "evento";
	$type = "e";
}elseif(isset($local)){
	$page = "local";
	$type = "l";
}elseif(isset($promo)){
	$page = "promo";
	$type = "p";
}elseif(isset($dj)){
	$page = "dj";
	$type = "dj";
}elseif(isset($lista)){
	$page = "lista";
	$type = "lt";
}
?>
<div id="comentariosPagina">
	<div class="nuevoComentario">
		<?php if(!isLogged()): ?>
		<div class="noLogged">
			<p><a href="#" class="registrarse button-blue">Regístrate</a>o<a href="#" class="entrar button-blue">Inicia sesión</a></p>
		</div>
		<? endif; ?>
		<textarea id="textComentario" rows="2" cols="100%" placeholder="Escribe tu comentario..."></textarea><br />
		<button id="enviarComentario" class="button">Enviar</button>
	</div>
	<script type="text/javascript">
	<?php if(!isLogged()): ?>
	$(".nuevoComentario").hover(function(){
		$(this).children(".noLogged").stop(true, true).fadeIn(500);
	}, function(){
		$(this).children(".noLogged").stop(true, true).fadeOut(500);
	});
	<?php endif; ?>
	$(".nuevoComentario textarea").focus(function(e){
		$(".nuevoComentario").addClass("nuevoComentarioFocus");
	}).blur(function(e){
		$(".nuevoComentario").removeClass("nuevoComentarioFocus");
	});
	$("#enviarComentario").click(function(e){
		$.post("ajax/<?=$page?>.php?id=<?=$id?>&newComment", "comentario=" + $("textarea#textComentario").val(), function(res){
			if (res !== "")
				alert(res);
			$(".showcomments").load("ajax/<?=$page?>.php?id=<?=$id?>"+" .showcomments");
			$(".nuevoComentario textarea").val("");
		});
	});
	</script>
	<div class="showcomments">
		<?php
		try{
			if($page == "dj"){
				$comentarios = new comentarios($type."_".$perfil->getID());
			}elseif($page == "lista"){
				$comentarios = new comentarios($type."_".$lista->getID());
			}else{
				$comentarios = new comentarios($type."_".$$page->pagina['id']);	
			}
			if ($comentarios){
				if($page == "dj" ){
					$res =  $comentarios->getComments($type."_".$perfil->getID());
				}elseif($page == "lista"){
					$res =  $comentarios->getComments($type."_".$lista->getID());
				}else{
					$res =  $comentarios->getComments($type."_".$$page->pagina['id']);
				}
				$comments = json_decode($res, true);
				if($page == "evento"){
					$asistencias = new Asistencias;
					$asis = $asistencias->getAsistencias($id);	
				}else{
					$asis = false;
				}
				if (is_array($asis)){
					$asisPlusComments = array_merge($comments, $asis);	
				}else{
					$asisPlusComments = $comments;
				}
				function orderByDate($a, $b){
					$aFecha = (isset($a['Fecha'])) ? $a['Fecha'] : $a['fecha'];
					$bFecha = (isset($b['Fecha'])) ? $b['Fecha'] : $b['fecha'];
					
					if (strtotime($aFecha) == strtotime($bFecha)){
						return 0;
					}
					if (strtotime($aFecha) > strtotime($bFecha)){
						return -1;
					}
					return 1;
				}
				usort($asisPlusComments, "orderByDate");
				
				foreach($asisPlusComments as $result){
					if(isset($result["texto"])):
						$comentario = $result;
						$usuario = new Usuario($comentario['id_usuario']);
						?>
						<div class="entradaComentario">
							<div class="usuarioComentario">
								<img src='images/user_profiles/default.png' alt='<?=$usuario->getNombre()?>' />
								<span><?=$usuario->getNombre()?></span>
							</div>
							<span class="textoComentario">
								<?=$comentario['texto']?>
								<span style="display: block;text-align: right;padding: 10px;">
									<?=$comentario['fecha']?>
								</span>
							</span>
						</div>
					<?php
					else:
						$asistencia = $result;
						$usuario = new Usuario($asistencia['idUsuario']);
						?>
						<div class="entradaComentario">
							<span class="asistenciaTexto">
								<a href='#!/usuario/<?=$usuario->getID()?>'><?=$usuario->getNombre()." ".$usuario->getApellido()?></a>
								marcó asistencia para este evento
								<span>
									<?=$asistencia['Fecha']?>
								</span>
							</span>
						</div>
						<?php
					endif;
				}
			}
		}catch (Exception $e){
			echo $e->getMessage();
		}
		?>
	</div>
</div>