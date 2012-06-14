<?php
@session_start();
include '../inc/conectar.php';
include '../inc/paginas.class.php';
include '../inc/usuario.class.php';
include '../inc/comentarios.class.php';
include '../inc/fotos.class.php';


$imgPath = "images/fotos/eventos/";
$conn = new Conectar;
$conn->TM();
$idAlbum = $_GET['id'];
$idFoto = $_GET['foto'];
$nextID = $idFoto+1;
$prevID = $idFoto-1;
$pid = $idAlbum.'-'.$idFoto;
$fQuery = $conn->query("SELECT * FROM eventos_fotos WHERE id=".$idAlbum);
if ($fQuery):
	$album  = mysql_fetch_assoc($fQuery);
	if (mysql_error()) exit;
	$fotos = json_decode($album['Fotos']);
	echo "<div class='photoDisplay'>";
	if (isset($fotos[$prevID])):
	echo "<a id='photoDisplayPrev' href='#!/album/".$idAlbum."/foto/".$prevID."' class='photoDisplayPrev' title='Foto anterior'></a>";
	endif;
	echo "<img src='".$imgPath.$fotos[$idFoto]."' />";
	if (isset($fotos[$nextID])):
	echo "<a id='photoDisplayNext' href='#!/album/".$idAlbum."/foto/".$nextID."' class='photoDisplayNext' title='Siguiente foto'></a>";
	endif;
	echo "</div>";	
	echo "<div class='photoDisplayRight'>";
	?>
	<div class='comentariosFoto'>
		<div class='comentariosFotoMenuAcciones'>
			<button class='button button-red'>♥</button>
			<button class='button compartir'>Compartir</button>
			<button class='button' disabled='disabled'>Etiquetar en esta foto</button>
		</div>
		<div class='nuevoComentarioFoto'>
			<?php if (!isset($_SESSION['idusuario'])): ?>
			<textarea rows="2" id='textComentarioFoto' disabled='disabled' placeholder='Tienes que iniciar sesión para poder comentar'></textarea>
			<button class='button' id='enviarComentarioFoto' disabled='disabled'>Enviar</button>
			<?php else: ?>
			<textarea rows="2" id='textComentarioFoto'></textarea>
			<button class='button' id='enviarComentarioFoto'>Enviar</button>
			<?php endif; ?>
		</div>
		<div class="showcomments">
			<?php
			try{
				$comentarios = new comentarios("f_".$pid);
				if ($comentarios){
					$res =  $comentarios->getComments("f_".$pid);
					$comments = json_decode($res);
					foreach ($comments as $comentario){
						$usuario = new Usuario($comentario->id_usuario);
						?>
						<div class="entradaComentario">
							<div class="usuarioComentario">
								<img title='<?=$usuario->getNombre()?>' src='images/user_profiles/default.png' alt='<?=$usuario->getNombre()?>' />
								<!-- <a href='#!/usuario/<?=$usuario->getID()?>'><?=$usuario->getNombre()?></a> -->
							</div>
							<span class="textoComentario"><a href='#!/usuario/<?=$usuario->getID()?>'><?=$usuario->getNombre()?></a> <?=$comentario->texto?></span>
						</div>
						<?php
					}
				}
			}catch (Exception $e){
				echo $e->getMessage();
			}
			?>
		</div>
	</div>
	<?php
	echo "</div>";
endif;
?>
<script type="text/javascript">
	$(".nuevoComentarioFoto textarea").focus(function(e){
		$(".nuevoComentarioFoto").addClass("nuevoComentarioFotoFocus");
	}).blur(function(e){
		$(".nuevoComentarioFoto").removeClass("nuevoComentarioFotoFocus");
	});
	$("#enviarComentarioFoto").on("click", function(event){
		var pid = "<?php echo $pid ?>";
		$.ajax({
			url: "ajax/user.php?enviarComentarioFoto",
			type: "POST",
			data: "pid=f_" + pid + "&comentario=" + $("textarea#textComentarioFoto").val(),
			success: function(res){
				if (res != "")
					alert(res);
				$("textarea#textComentarioFoto").val("");
				$(".showcomments").load("ajax/user.php?getComentariosFotos&pid="+pid);
			},
			error: function(res){
				alert("Hubo un error enviando el comentario");
				$("textarea#textComentarioFoto").focus();
			}
		});
	});
	$(".compartir").on("click", function(e){
		var sharer = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent("http://www.tumovida.com.uy/facebook_request.php?t=foto&id=<?=$pid?>")+"&t="+encodeURIComponent(document.title);
		window.open(sharer, this.target, 'width=670,height=300');
	});
	if ($(".photoDisplay").is(":visible")){
		$(document).on("keydown", function(e){
			if(e.which == 37){
				$.address.value(($("#photoDisplayPrev").attr("href")).replace("#!/", ""));
			}
			if(e.which == 39){
				$.address.value(($("#photoDisplayNext").attr("href")).replace("#!/", ""));
			}
		});
		// if ($(".photoDisplay").height() > $(".photoDisplay img").height()){
		// 	$(".photoDisplay img").vAlign();
		// }
	}
</script>