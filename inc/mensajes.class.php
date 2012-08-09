<?php
class Mensaje extends UsuarioActions
{
	private $mensaje, $remitente;
	public function __construct($mensaje, $remitente)
	{
		if ($this->isLogged())
		{
			$this->remitente = $remitente;
			$this->mensaje = $mensaje;
			$this->enviarMensaje();
		}else{
			throw new Exception($this->Error(0));
			return false;	
		}
	}
	private function Error($code){
		$error[0] = "Debes iniciar sesión para poder enviar mensajes";
		$error[1] = "Hubo un error conectandose con la base de datos";
		$error[2] = "Hubo un error enviando el mensaje";
		
		return $error[$code];
	}
	private function enviarMensaje(){
		$mensaje = nl2br(strip_tags($this->mensaje));
		$userID = $this->propitaryID;
		$idRemitente = $this->remitente;
		$conn = new Conectar();
		if (!$conn)
			throw new Exception($this->Error(1));
		$connTM = $conn->Usuarios();
		$query = $conn->query("INSERT INTO mensajes (idEmisor, idRemitente, Texto) VALUES (".$userID.", '".$idRemitente."', '".$mensaje."')");
		if(!$query)
			throw new Exception($this->Error(2) . mysql_error());
	}
}
class getMensajes extends UsuarioActions
{
	private $conn;
	public function __construct()
	{
		if ($this->isLogged()){
			$conn = new Conectar();
			if (!$conn)
				throw new Exception($this->Error(1));
			$this->conn = $conn;
			$connTM = $conn->Usuarios();
			return $this->getList();			
		}else{
			throw new Exception($this->Error(0));
			return false;
		}
	}
	public function getList()
	{
		$conn = $this->conn;
		if (!isset($this->propitaryID)) die("No hay usuario remitente definido");
		$userID = $this->propitaryID;
		$query = $conn->query("SELECT id, idEmisor, idRemitente, Texto, Visto FROM mensajes WHERE idRemitente=".$userID);
		if(!$query)
			throw new Exception($this->Error(2) ." ".mysql_error());
		if (mysql_num_rows($query) < 1)
			throw new Exception($this->Error(3));
		while($res = mysql_fetch_assoc($query)){
			$buff[] = $res;
		}	
		if (isset($returnJSON) && $returnJSON)
			return json_encode($buff);
		return $buff;
	}
	private function Error($code){
		$error[0] = "Debes iniciar sesión para poder enviar mensajes";
		$error[1] = "Hubo un error conectandose con la base de datos";
		$error[2] = "Hubo un error leyendo los mensaje";
		$error[3] = "No hay mensajes";
		return $error[$code];
	}
}
class getMensaje extends UsuarioActions
{
	private $conn, $mensaje;
	public function __construct($msjID)
	{
		if ($this->isLogged()){
			$conn = new Conectar();
			if (!$conn)
				throw new Exception($this->Error(1));
			$this->conn = $conn;
			$connTM = $conn->Usuarios();
			return $this->getMsj($msjID);
		}else{
			throw new Exception($this->Error(0));
			return false;
		}
	}
	public function getMsj($msjID)
	{
		$conn = $this->conn;
		if (!isset($this->propitaryID)) die("No hay usuario remitente definido");
		$userID = $this->propitaryID;
		$query = $conn->query("SELECT idEmisor, idRemitente, Texto, Visto FROM mensajes WHERE idRemitente=".$userID." AND id=".$msjID);
		if(!$query)
			throw new Exception($this->Error(4) ." ".mysql_error());
		if (mysql_num_rows($query) < 1)
			throw new Exception($this->Error(5));
		$res = mysql_fetch_assoc($query);
		$this->mensaje = $res;
		if (isset($returnJSON) && $returnJSON)
			return json_encode($res);
		return $res;
	}
	private function Error($code){
		$error[0] = "Debes iniciar sesión para poder enviar mensajes";
		$error[1] = "Hubo un error conectandose con la base de datos";
		$error[2] = "Hubo un error leyendo los mensaje";
		$error[3] = "No hay mensajes";
		$error[4] = "Hubo un error leyendo el mensaje";
		$error[5] = "No hay mensaje";
		return $error[$code];
	}
	public function getTexto()
	{
		return $this->mensaje["Texto"];
	}
	public function getEmisor()
	{
		return $this->mensaje["idEmisor"];
	}
	public function getRemitente()
	{
		return $this->mensaje["idRemitente"];
	}
	public function getVisto()
	{
		return $this->mensaje["Visto"];
	}
}
?>