<?php
session_start();
include_once "../inc/conectar.php";
include_once "../inc/paginas.class.php"; 
include_once "../inc/usuario.class.php";
include_once "../inc/mail.class.php";
include_once "../inc/mensajes.class.php";
include_once "../inc/comentarios.class.php";
if (isset($_GET['login']))
{
	if (!isset($_POST['mail']) || !isset($_POST['password']))
		exit("Error en la solicitud. Falta mail y/o contraseña");
	try{
		$usuario = new UsuarioLog($_POST['mail'], $_POST['password']);
	}catch (Exception $e){
		echo $e->getMessage();
	}
	if (isLogged()){
		$userID = $_SESSION['idusuario'];
		$user = new Usuario($userID);
		echo $user->getNombre();
	}
}
if (isset($_GET['logout']))
{
	$status = logout(); 
	if(!$status){
		echo "Hubo un fallo al desconectarse de su cuenta";
	}
}
if (isset($_GET['new']))
{
	$dataArray = $_POST;
	$newUser = new NuevoUsuario($dataArray);
	$newUser->setValues();
	$mail = new SendMail(mysql_insert_id());
	$mail->sendRegistro();
	try{
		$usuario = new UsuarioLog($dataArray['mail'], $dataArray['password']);
		if (isLogged()){
		$userID = $_SESSION['idusuario'];
		$user = new Usuario($userID);
		echo $user->getNombre();
	}
	}catch (Exception $e){}
}
if (isset($_GET['update']))
{
	$dataArray = $_POST;
	try{
		$updateUser = new UpdateUsuario($dataArray);
		$updateUser->setValues();
	}catch(Exception $e){
		echo $e->getMessage();
	}
}
if (isset($_GET['enviarMensaje']))
{
	if (isset($_POST['mensaje']) && isset($_POST['remitente'])){
		try{
			$mensaje = new Mensaje($_POST['mensaje'], $_POST['remitente']);
		}catch (Exception $e){}
	}else{
		die("Falta el destinatario y/o texto");
	}
}
if (isset($_GET['checkMail']))
{
	$mail = $_GET['checkMail'];
	$conn = new conectar;
	$conn->Usuarios();
	$check = mysql_num_rows($conn->query("SELECT id FROM usuarios WHERE Mail='".$mail."'"));
	if ($check > 0){
		echo "false";
		return false;
	}
	return true;
}
if (isset($_GET['getComentarios']))
{
	if (!isset($_GET['user']))
		die('Falta especificar el usuario');
	$user = $_GET['user'];
	$conn = new Conectar;
	$conn->TM();
	$q = $conn->query("SELECT * FROM comentarios WHERE id_usuario=".$user);
	if (mysql_num_rows($q) == 0)
		die ('No existen comentarios del usuario');
	while ($comentario = mysql_fetch_assoc($q)){
		$buff[] = $comentario;
	}
	echo json_encode($buff);
}
if (isset($_GET['getAsistencias']))
{
	if (!isset($_GET['user']))
		die('Falta especificar el usuario');
	$user = $_GET['user'];
	$conn = new Conectar;
	$conn->TM();
	$q = $conn->query("SELECT * FROM asistencias WHERE idUsuario=".$user);
	if (mysql_num_rows($q) == 0)
		die ('No existen asistencias del usuario');
	while ($comentario = mysql_fetch_assoc($q)){
		$buff[] = $comentario;
	}
	echo json_encode($buff);
}
if (isset($_GET['getList'])){
	(isset($_GET['term'])) ? $term = strtolower($_GET['term']) : $term = '';
	$conn  = new Conectar;
	$conn->Usuarios();	
	$query = $conn->query("SELECT id, Nombre, Apellido FROM usuarios WHERE LOWER(Nombre) LIKE '%" . $term . "%' OR LOWER(Apellido) LIKE '%".$term."%' ORDER BY Nombre");
	while ($r = mysql_fetch_assoc($query)){
		$buff[] = $r;
	}
	echo json_encode($buff);
	exit;
}
if (isset($_GET['enviarComentarioFoto'])){
	if(isset($_POST['pid']) && isset($_POST['comentario']))
	{
		try{
			$id 		= $_POST['pid'];
			$comentario = $_POST['comentario'];
			new comentario($comentario, $id);
		}catch(Exception $e){
			echo $e;
		}
	}
}
if (isset($_GET['getComentariosFotos']) && isset($_GET['pid']))
{
	$pid = $_GET['pid'];
	try{
		$comentarios = new comentarios("f_".$pid);
		if ($comentarios){
			echo "<div class=\"showcomments\">";
			$res =  $comentarios->getComments("f_".$pid);
			$comments = json_decode($res);
			foreach ($comments as $comentario){
				$usuario = new Usuario($comentario->id_usuario);
				?>
				<div class="entradaComentario">
					<div class="usuarioComentario">
						<img src='images/user_profiles/default.png' alt='<?=$usuario->getNombre()?>' />
						<a href='#!/usuario/<?=$usuario->getID()?>'><?=$usuario->getNombre()?></a>
					</div>
					<span class="textoComentario"><?=$comentario->texto?></span>
				</div>
				<?php
			}
			echo "</div>";
		}
	}catch (Exception $e){
		echo $e->getMessage();
	}	
}