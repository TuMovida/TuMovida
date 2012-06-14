<?php
class comentario extends UsuarioActions
{
	public $pagina;
	private $comentario;
	public function __construct($comentario, $pagina)
	{
		if ($this->isLogged())
		{
			$this->pagina = $pagina;
			$this->comentario = $comentario;
			$this->insertComment();
		}else{
			throw new Exception($this->Error(0));
			return false;	
		}
	}
	private function Error($code){
		$error[0] = "Debes iniciar sesión para poder comentar";
		$error[1] = "Hubo un error conectandose con la base de datos";
		$error[2] = "Hubo un error ingresando el comentario";
		
		return $error[$code];
	}
	private function insertComment(){
		$comentario = nl2br(strip_tags($this->comentario));
		$userID = $this->propitaryID;
		$paginaID = $this->pagina;
		$conn = new Conectar();
		if (!$conn)
			throw new Exception($this->Error(1));
		$connTM = $conn->TM();
		$query = $conn->query("INSERT INTO comentarios (id_usuario, id_pagina, texto) VALUES (".$userID.", '".$paginaID."', '".$comentario."')");
		if(!$query)
			throw new Exception($this->Error(2) . mysql_error());
	}
}
class comentarios extends Conectar
{
	public $comentarios;
	private $id_pagina;
	public function __contruct($id_pagina)
	{
		$this->id_pagina = $id_pagina;
		try{
			$this->getComments($id_pagina);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	public function getComments($id_pagina)
	{
		$conn = new Conectar();
		$connTM = $conn->TM();
		if (!$conn){
			throw new Exception($this->Error(1), 1);
			die();
		}
		$query = $this->query("SELECT id_usuario, texto, fecha FROM comentarios WHERE id_pagina='".$id_pagina."' ORDER BY id DESC");
		if(!$query){
			throw new Exception($this->Error(2), 2);
			die();
		}
		while ($buff = mysql_fetch_assoc($query)){
			$c[] = $buff;
		}
		if (!isset($c))
			throw new Exception($this->Error(3), 3);
			
		$this->comentarios = $c;
		return json_encode($c);
	}
	private function Error($code){
		$error[0] = "";
		$error[1] = "Hubo un error conectandose con la base de datos";
		$error[2] = "Hubo un error leyendo los comentarios";
		$error[3] = "No hay comentarios.";
		return $error[$code];
	}
}