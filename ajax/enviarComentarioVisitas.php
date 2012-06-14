<?php
include '../inc/conectar.php';

function enviarComentario($nombre, $mensaje, $email)
{
	$conn  = new Conectar;
	$conn->TM();
	if($nombre == ""){
		exit;
	}
	if($mensaje == ""){
		exit;
	}
	if($email == ""){
		exit;
	}
	$nombre = mysql_real_escape_string($nombre);
	$mensaje = mysql_real_escape_string($mensaje);
	$email = mysql_real_escape_string($email);
	$sql="INSERT INTO librodevisitas (Usuario, Mail, Mensaje) VALUES ('$nombre','$email', '$mensaje')";
	$res = $conn->query($sql);
	if (!$res) return mysql_error();
	return $res;
}
$res = enviarComentario($_POST['usuario'], $_POST['mensaje'], $_POST['mail']);
echo $res;