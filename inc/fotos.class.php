<?php
include_once "conectar.php";
include_once "paginas.class.php";

class Fotos extends Conectar
{
	public function __construct()
	{
		$this->Conectar;
		$this->TM();
	}
	public function getFotos($eventID)
	{
		$q = $this->query("SELECT id, idEvento, Album, Fotos FROM eventos_fotos WHERE idEvento=".$eventID);
		if (mysql_num_rows($q) == 0) 
			return false;
		return mysql_fetch_assoc($q);
	}
	public function getLocalesFotos($localID)
	{

	}
	public function getUserFotos($userID)
	{
		
	}
	public function getBandasFotos($bandaID)
	{

	}
	public function getDjFotos($djID){

	}
}
class FotoLike extends Fotos
{
	const likes_table = "likes_fotos";

	private $res;

	public function __construct($fotoID, $idPagina)
	{
		$this->Conectar();
		$this->TM();
		$this->res = $this->query("SELECT * FROM ".FotoLike::likes_table." WHERE idPagina=".$idPagina." AND id=".$fotoID);
	}
}
?>