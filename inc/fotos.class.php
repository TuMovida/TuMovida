<?php
include_once "conectar.php";
include_once "paginas.class.php";
include_once "usuario.class.php";

interface IFotos
{
	public function setID($id);
	public function getAlbum();
	public function getAlbumLikes();
	public function getAlbumComments();
	public function getFoto();
	public function getFotoLikes();
	public function getFotoComments();
}
class Fotos extends Conectar
{
	public function __construct()
	{
		$this->Conectar;
		$this->TM();
	}
	public function getFotosEventos($eventID)
	{
		return new FotosEventos($eventoID);
	}
	public function getLocalesFotos($localID)
	{
		return new FotosLocales($localID);
	}
	public function getUserFotos($userID)
	{
		return new FotosUsuarios($userID);
	}
	public function getBandasFotos($bandaID)
	{
		return new FotosBandas($bandaID);
	}
	public function getDjFotos($djID)
	{
		return new FotosDJs($djID);
	}
	public function rango($tipoPagina)
	{
		$toSet = array("evento" => "e", "eventos" => "e", "local" => "l", "locales" => "l",
			"usuario" => "u", "usuarios" => "u", "dj" => "d", "djs" => "d", "banda" => "b",
			"bandas" => "b");
		if(isset($toSet[$tipoPagina])){
			return $toSet[$tipoPagina];
		}
		return $tipoPagina;
	}
}
class FotosEventos extends Fotos implements IFotos
{
	public $id;
	const table = "eventos_fotos";
	public function setID($id)
	{
		$this->id = $id;
	}
	#TODO
	public function getAlbum()
	{
	
	}
	#TODO
	public function getAlbumLikes()
	{

	}
	#TODO
	public function getAlbumComments()
	{

	}
	public function getFoto()
	{
		$q = $this->query("SELECT id, idEvento, Album, Fotos
			FROM eventos_fotos WHERE idEvento=".$this->id);
		if (mysql_num_rows($q) == 0) 
			return false;
		return mysql_fetch_assoc($q);
	}
	#TODO
	public function getFotoLikes()
	{
		#Crear nuevo objeto FotoLikes
	}
	#TODO
	public function getFotoComments()
	{

	}
}
#TODO: Para implementar fotos a los locales en un futuro.
class FotosLocales extends Fotos implements IFotos
{
	public $id;
	const table = "eventos_fotos";
	public function __construct($localID)
	{
		$this->setID($localID);
		#Esta clase no fue implementada aún.
		return false;
	}
	public function setID($id)
	{
		$this->id = $id;
	}
	#TODO
	public function getAlbum()
	{
	
	}
	#TODO
	public function getAlbumLikes()
	{

	}
	#TODO
	public function getAlbumComments()
	{

	}
	public function getFoto()
	{
		$q = $this->query("SELECT id, idEvento, Album, Fotos
			FROM ".this::table." WHERE idEvento=".$this->id);
		if (mysql_num_rows($q) == 0) 
			return false;
		return mysql_fetch_assoc($q);
	}
	#TODO
	public function getFotoLikes()
	{
		#Crear nuevo objeto FotoLikes
	}
	#TODO
	public function getFotoComments()
	{

	}
}
#TODO: Para implementar fotos en las promociones a futuro.
class FotosPromociones extends Fotos implements IFotos
{
	public $id;
	const table = "eventos_fotos";
	public function setID($id)
	{
		$this->id = $id;
	}
	#TODO
	public function getAlbum()
	{
	
	}
	#TODO
	public function getAlbumLikes()
	{

	}
	#TODO
	public function getAlbumComments()
	{

	}
	public function getFoto()
	{
		$q = $this->query("SELECT id, idEvento, Album, Fotos
			FROM ".this::table." WHERE idEvento=".$this->id);
		if (mysql_num_rows($q) == 0) 
			return false;
		return mysql_fetch_assoc($q);
	}
	#TODO
	public function getFotoLikes()
	{
		#Crear nuevo objeto FotoLikes
	}
	#TODO
	public function getFotoComments()
	{

	}
}
#TODO: Para implementar fotos a los usuarios en un futuro.
class FotosUsuarios extends Fotos implements IFotos
{
	public $id;
	const table = "eventos_fotos";
	public function setID($id)
	{
		$this->id = $id;
	}
	#TODO
	public function getAlbum()
	{
	
	}
	#TODO
	public function getAlbumLikes()
	{

	}
	#TODO
	public function getAlbumComments()
	{

	}
	public function getFoto()
	{
		$q = $this->query("SELECT id, idEvento, Album, Fotos
			FROM ".this::table." WHERE idEvento=".$this->id);
		if (mysql_num_rows($q) == 0) 
			return false;
		return mysql_fetch_assoc($q);
	}
	#TODO
	public function getFotoLikes()
	{
		#Crear nuevo objeto FotoLikes
	}
	#TODO
	public function getFotoComments()
	{

	}
}
#TODO: Para implementar fotos a los DJ's próximamente.
class FotosDjs extends Fotos implements IFotos
{
	public $id;
	const table = "eventos_fotos";
	public function setID($id)
	{
		$this->id = $id;
	}
	#TODO
	public function getAlbum()
	{
	
	}
	#TODO
	public function getAlbumLikes()
	{

	}
	#TODO
	public function getAlbumComments()
	{

	}
	public function getFoto()
	{
		$q = $this->query("SELECT id, idEvento, Album, Fotos
			FROM ".this::table." WHERE idEvento=".$this->id);
		if (mysql_num_rows($q) == 0) 
			return false;
		return mysql_fetch_assoc($q);
	}
	#TODO
	public function getFotoLikes()
	{
		#Crear nuevo objeto FotoLikes
	}
	#TODO
	public function getFotoComments()
	{

	}
}
#TODO: Para implementar fotos a las Bandas próximamente.
class FotosBandas extends Fotos implements IFotos
{
	public $id;
	const table = "eventos_fotos";
	public function setID($id)
	{
		$this->id = $id;
	}
	#TODO
	public function getAlbum()
	{
	
	}
	#TODO
	public function getAlbumLikes()
	{

	}
	#TODO
	public function getAlbumComments()
	{

	}
	public function getFoto()
	{
		$q = $this->query("SELECT id, idEvento, Album, Fotos
			FROM ".this::table." WHERE idEvento=".$this->id);
		if (mysql_num_rows($q) == 0) 
			return false;
		return mysql_fetch_assoc($q);
	}
	#TODO
	public function getFotoLikes()
	{
		#Crear nuevo objeto FotoLikes
	}
	#TODO
	public function getFotoComments()
	{

	}
}
class FotoLike extends Fotos
{
	const likes_table = "likes_fotos";
	private $res, $fotoID, $idPagina;
	public function __construct($fotoID, $idPagina)
	{
		$this->Conectar();
		$this->TM();
		$this->fotoID = $fotoID;
		$this->idPagina = $idPagina;
	}
	public function setLike($idUsuario)
	{
		return new setLike($idFoto, $idUsuario);
	}
	public function getLikes()
	{
		//Fotos::rango($this->idPagina);
		$this->res = $this->query("SELECT * FROM ".FotoLike::likes_table." WHERE idPagina=".$idPagina." AND id=".$fotoID);
	}
}
class setLike extends UsuarioActions
{
	# Usamos el mismo constructor

	public function getLike($idFoto, $idUsuario)
	{
		$this->Conexion();
		$this->TM();
		$query = $this->query("SELECT id FROM ".Fotos::likes_table." WHERE idFoto=".$idFoto." AND idUsuario=".$idUsuario);
		if (mysql_affected_rows() == 0)
			return false;
		$idLike = mysql_fetch_row($query);
		return $idAsistencia[0];	
	}
	public function setLike($idFoto, $idUsuario)
	{
		$this->Conexion();
		$this->TM();
		$exist = $this->getLike($idEvento, $idUsuario);
		if (!$exist)
			$query = $this->query("INSERT INTO ".Fotos::likes_table." (idFoto, idUsuario) VALUES (".$idFoto.", ".$idUsuario.")");
		else
			$query = $this->toggleLike($exist);
		return $query;
	}
	private function toggleLike($idLike)
	{
		$this->Conexion();
		$this->TM();
		$isBlocked = mysql_fetch_row($this->query("SELECT block FROM asistencias WHERE id=".$idLike));
		if ($isBlocked[0] == 1){
			return $this->query("UPDATE ".Fotos::likes_table." SET block=0 WHERE id=".$idLike);
		}else{
			return $this->query("UPDATE ".Fotos::likes_table." SET block=1 WHERE id=".$idLike);
		}
	}
}
?>