<?php
include_once "paginas.class.php";
include_once "conectar.php";
class sendMail extends Conectar
{
	private $connTM, $sendTo, $asunto, $mensaje, $Nombre, $Apellido, $CI;
	public function __construct($IDusuario)
	{
		$this->Conexion();
		$this->connTM = $this->TM();
		$usuario = new Usuario($IDusuario);
		$this->sendTo = $usuario->getMail();
		$this->Nombre = $usuario->getNombre();
		$this->Apellido = $usuario->getApellido();
		$this->CI = $usuario->getCI();
	}
	public function sendCompra($IDarticulo, $Cantidad)
	{
		$this->Conexion();
		$this->TM();
		$articuloData = mysql_fetch_assoc($this->query("SELECT Nombre, Valor FROM promociones WHERE id=".$IDarticulo));
		$asunto 	= 	"Compra en TuMovida";
		$mensaje 	= 	"<div style='font-family:'Trebuchet MS', sans-serif;'>
						 ¡Tu compra ha sido realizada con éxito!<br />	
						 A continuación se hará muestra de sus datos: <br />
						 Nombre: <b>".$this->Nombre."</b><br />
						 Apellido: <b>".$this->Apellido."</b><br />
						 Cédula de Identidad: <b>".$this->CI."</b><br />
						 <br />
						 <u>Datos del artículo</u><br />
						 Nombre: <b>".$articuloData['Nombre']."</b><br />
						 Cantidad: <b>".$Cantidad."</b><br />
						 Importe: <b>$".$Cantidad*$articuloData['Valor']."</b><br />
						 <br />
						 <i>¡Gracias por preferirnos!</i>
						 </div>";

	 	$this->asunto = $asunto;
	 	$this->mensaje = $mensaje;

	 	$this->sendIt();
	}
	public function sendRegistro()
	{
		$asunto 	= 	"Registro en TuMovida";
		$mensaje 	= 	"<div style='font-family:'Trebuchet MS', sans-serif;'>
						 ¡Tu registro en TuMovida se ha realizado con éxito!<br />	
						 A continuación se hará muestra de sus datos: <br />
						 Nombre: <b>".$this->Nombre."</b><br />
						 Apellido: <b>".$this->Apellido."</b><br />
						 <br />
						 Prueba <a href='http://tumovida.com.uy/#!/editarPerfil'>editar tu perfil</a>, así podrás realizar compras y disfrutar de todos los beneficios que <b>TuMovida</b> tiene para vos.
						 <br />
						 <i>¡Esperamos disfrutes de tu experiencia en el sitio!, <br /> Atte. El equipo de TuMovida.</i>
						 </div>";
		$this->asunto = $asunto;
	 	$this->mensaje = $mensaje;
	 	$this->sendIt();					 		
	}
	public function sendPass()
	{

	}
	public function mensajePrivado($emitor, $mensaje)
	{
		$de = new Usuario($emitor); 
		$deTXT = "<a href='http://www.tumovida.com.uy/#!/usuario/".$de->getID()." />".$de->getNombre()." ".$de->getApellido."</a>";
		
		$this->asunto  = 	"Nuevo mensaje privado en TuMovida";
		$this->mensaje = 	"<div style='font-family:'Trebuchet MS', sans-serif;'>
								Acaba de llegarte un mensaje privado de ".$deTXT.". <br />
								<div style='display: block; background: whiteSmoke; '>".$mensaje."</div>
								Para responderle ingresa a <a href='http://www.tumovida.com.uy'>¡TuMovida!</a>.
							</div>";
	}
	public function sendIt()
	{
		$cabeceras  = 	"MIME-Version: 1.0" . "\r\n";
		$cabeceras .= 	"Content-type: text/html; charset=UTF-8" . "\r\n";
		$cabeceras .= 	"From: TuMovida <info@tumovida.com.uy>" . "\r\n";

		mail($this->sendTo, $this->asunto, $this->mensaje, $cabeceras);
	}
}
?>