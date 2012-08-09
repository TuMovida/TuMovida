<?php
include_once "../apis/Services/Soundcloud.php";
define("CLIENT_ID", "6c514ee40652de55d0ba1d05e7ac9a08");
define("CLIENT_SECRET", "701ba99091720de2a4e6800994b85b2c");
define("REDIRECT_URL", "http://www.tumovida.com.uy/apis/sc_conectar.php");

interface IUserExtended{
	//GETTER
	public function getID();
	public function getNombre();
	public function getUbicacion();
	public function getBio();
	public function getFoto();
	public function getAdmin();
	public function getCreador();
	public function getSoundCloud();
	//SETTER
	public function setNombre($Nombre);
	public function setUbicacion($Ubicacion);
	public function setBio($Bio);
	public function setFoto($Foto);
	public function setAdmin($Admin);
	public function setCreador($Creador);
	public function setSoundCloud($accessToken, $refreshToken);
}
class Banda extends Conectar implements IUserExtended
{
	protected $userData;
	public $userID;
	function __construct($userID){
		//Conectar DB:
		$this->Usuarios();
		$this->userID = $userID;
		return $this->getData();
	}
	private function getData(){
		$query = $this->query("SELECT * FROM bandas WHERE id=".$this->userID);
		$this->userData = mysql_fetch_assoc($query);
		return $query;
	}
	public function getID(){
		return $this->userData['id'];
	}
	public function getNombre(){
		return ucwords($this->userData['Nombre']);
	}
	public function getUbicacion(){
		return $this->userData['Ubicacion'];
	}
	public function getBio(){
		return $this->userData['Bio'];
	}
	public function getFoto(){
		return $this->userData['Foto'];
	}
	public function getAdmin(){
		$admin = $this->userData['idAdmin'];
		if(is_numeric($admin)){
			return $admin;
		}else{
			return explode(",", $admin);
		}
	}
	public function getCreador(){

	}
	public function getSoundCloud(){

	}
	public function setNombre($Nombre){
		$this->userData['Nombre'] = $Nombre;
	}
	public function setUbicacion($Ubicacion){
		$this->userData['Ubicacion'] = $Ubicacion;
	}
	public function setBio($Bio){
		$this->userData['Bio'] = $Bio;
	}
	public function setFoto($Foto){
		$this->userData['Foto'] = $Foto;
	}
	public function setAdmin($Admin){
		if (is_array($Admin)){
			$Admin = implode($Admin);
		}
		$this->userData['idAdmin'] = $Admin;
	}
	public function setSoundCloud($accessToken, $refreshToken){

	}
	public function isAdmin(){
		if(isset($_SESSION['valid']) && $_SESSION['valid']){
			if (isset($_SESSION['idusuario'])){
				$idAdmin = $this->getAdmin();
				if ((is_array($idAdmin) && in_array($_SESSION['idusuario'], $idAdmin)) || ($idAdmin = $_SESSION['idusuario'])){
					return true;
				}
			}
		}
		return false;
	}
	public function setCreador($Creador)
	{

	}
}
class NuevaBanda extends Banda
{
	private $dataArray;
	public function __construct($dataArray){
		$this->dataArray = $dataArray;
		$this->cleanForm();
	}
	private function cleanForm(){
		$dataArray = $this->dataArray;
		foreach($dataArray as $data){
			$data = mysql_real_escape_string($data);
		}
	}
	public function setValues(){
		foreach ($this->dataArray as $name=>$val)
		{
			switch ($name) {
				case 'nombre': $this->setNombre($val);break;
				case 'ubicacion': $this->setUbicacion($val);break;
				case 'foto': $this->setFoto($val);break;
				case 'admin': $this->setAdmin($val);break;
				default:
					;
				break;
			}
		}
		$columns = "";
		$values = "";
		foreach ($this->userData as $name=>$val)
		{
			if ($name != "" && $val != ""){
				if ($columns == "")
					$columns = $name;
				else 
					$columns = $columns . ", " . $name;
				if ($values == "")
					$values = "'".$val."'";
				else 
					$values = $values . ", " . "'".$val."'";
			}
		}
		if ($columns != "" && $values != ""){
			$doQuery = "INSERT INTO bandas (".$columns.") VALUES (".$values.")";
			$this->Conexion();
			$this->Usuarios();
			$query = $this->query($doQuery);
		}
	}
}
class UpdateBanda extends Banda
{
	private $dataArray;
	public function __construct($dataArray){
		$this->dataArray = $dataArray;
		$this->cleanForm();
	}
	private function cleanForm(){
		$this->Conexion();
		$this->Usuarios();
		$dataArray = $this->dataArray;
		foreach($dataArray as $data){
			$data = mysql_real_escape_string($data);
		}
	}
	public function setValues(){
		foreach ($this->dataArray as $name=>$val)
		{
			switch ($name) {
				case 'mail': $this->setMail($val);break;
				case 'nombre': $this->setNombre($val);break;
				case 'ubicacion': $this->setUbicacion($val);break;
				case 'admin': $this->setAdmin($val);break;
				default:
					;
				break;
			}
		}
		foreach ($this->userData as $name=>$val)
		{
			if (!isset($toUPDATE))
				$toUPDATE = $name . "='" . $val."'";
			else
				$toUPDATE = $toUPDATE . ", " . $name . "='" . $val."'";
		}
		if ($toUPDATE != NULL){
			$doQuery = "UPDATE bandas SET ".$toUPDATE." WHERE id=".$_SESSION['idusuario'];
			$this->Conexion();
			$this->Usuarios();
			$query = $this->query($doQuery);
			if (!$query){
				throw new Exception("Error Processing Request: ".mysql_error(), 1);
			}
		}
	}
}
/*
 * D	 
 * J
 * s
 */ 
class Dj extends Conectar implements IUserExtended
{
	public $userID, $userData;
	public function __construct($userID){
		//Conectar DB:
		$this->Usuarios();
		$this->userID = $userID;
		return $this->getData();
	}
	private function getData(){
		$query = $this->query("SELECT * FROM djs WHERE id=".$this->userID);
		if (mysql_num_rows($query) < 1)
			return false;
		$this->userData = mysql_fetch_assoc($query);
		return $query;
	}
	public function getID(){
		return $this->userData['id'];
	}
	public function getNombre(){
		return ucwords($this->userData['Nombre']);
	}
	public function getUbicacion(){
		return $this->userData['Ubicacion'];
	}
	public function getBio(){
		return $this->userData['Bio'];
	}
	public function getFoto(){
		return $this->userData['Foto'];
	}
	public function getAdmin(){
		$admin = $this->userData['idAdmin'];
		if(is_numeric($admin)){
			return $admin;
		}else{
			return explode(",", $admin);
		}
	}
	public function getCreador(){
		return $this->userData['Creador'];
	}
	public function getSoundCloud(){
		if($this->userData['sc_accessToken'] != NULL){
			if($this->userData['sc_refreshToken'] != NULL){
				$this->setSoundCloud($this->userData['sc_accessToken'], $this->userData['sc_refreshToken']);
			}else{
				$this->setSoundCloud($this->userData['sc_accessToken'], NULL);
			}
			return $this->userData['sc'];
		}
		return false;
	}
	public function getSoundCloudSets(){
		$sc = (isset($this->userData['sc'])) ? $this->userData['sc'] : $this->getSoundCloud();
		try{
			$scClient = new Services_Soundcloud(CLIENT_ID, CLIENT_SECRET);
			$scClient->setAccessToken($sc['accessToken']);
			$playlist = json_decode($scClient->get('me/playlists'));	
		}catch(Exception $e){
			echo $e->getMessage();
		}
		return $playlist;
	}
	public function setNombre($Nombre){
		$this->userData['Nombre'] = $Nombre;
	}
	public function setUbicacion($Ubicacion){
		$this->userData['Ubicacion'] = $Ubicacion;
	}
	public function setBio($Bio){
		$this->userData['Bio'] = $Bio;
	}
	public function setFoto($Foto){
		$this->userData['Foto'] = $Foto;
	}
	public function setAdmin($Admin){
		//TODO: Mejorar función para agregar adimnistradores.
		if (is_array($Admin)){
			$Admin = implode($Admin);
		}
		$this->userData['idAdmin'] = $Admin;
	}
	public function setCreador($Creador){
		$this->userData['idCreador'] = $Creador;
	}
	public function setSoundCloud($accessToken, $refreshToken){
		$this->userData['sc'] = array("accessToken" => $accessToken, "refreshToken" => $refreshToken);
	}
	public function isAdmin(){
		if(isset($_SESSION['valid']) && $_SESSION['valid']){
			if (isset($_SESSION['idusuario'])){
				$idAdmin = $this->getAdmin();
				if ((is_array($idAdmin) && in_array($_SESSION['idusuario'], $idAdmin)) || ($idAdmin = $_SESSION['idusuario'])){
					return true;
				}
			}
		}
		return false;
	}
}
class NuevoDj extends Dj
{
	private $dataArray, $insertID;
	public function __construct($dataArray){
		$this->dataArray = $dataArray;
		$this->cleanForm();
		$this->setValues();
	}
	public function __tostring(){
		return $this->insertID;
	}
	private function cleanForm(){
		$dataArray = $this->dataArray;
		foreach($dataArray as $key => $data){
			$data = mysql_real_escape_string($data);
			$newArray[$key] = $data;
		}
		$this->dataArray = $newArray;
	}
	public function setValues(){
		foreach ($this->dataArray as $name=>$val)
		{
			switch ($name) {
				case 'nombre': $this->setNombre($val);break;
				case 'ubicacion': $this->setUbicacion($val);break;
				case 'foto': $this->setFoto($val);break;
				case 'admin': $this->setAdmin($val);break;
				case 'creador': $this->setCreador($val);break;
				case 'bio': $this->setBio($val);break;
				default:
					;
				break;
			}
		}
		$columns = "";
		$values = "";
		foreach ($this->userData as $name=>$val)
		{
			if ($name != "" && $val != ""){
				if ($columns == "")
					$columns = $name;
				else 
					$columns = $columns . ", " . $name;
				if ($values == "")
					$values = "'".$val."'";
				else 
					$values = $values . ", " . "'".$val."'";
			}
		}
		if ($columns != "" && $values != ""){
			$doQuery = "INSERT INTO djs (".$columns.") VALUES (".$values.")";
			$this->Conexion();
			$this->Usuarios();
			$query = $this->query($doQuery);
			if(!$query){
				$this->insertID = "ERROR";
				return false;
			}else{
				$this->insertID = (string) mysql_insert_id();
				return $this->insertID;
			}
		}
	}
}
class UpdateDj extends Dj
{
	private $dataArray, $djID;
	public function __construct($dataArray, $djID){
		$this->dataArray= $dataArray;
		$this->djID 	= $djID;
		//$this->cleanForm();
		$this->setValues();	
	}
	private function cleanForm(){
		$this->Conexion();
		$this->Usuarios();
		$dataArray = $this->dataArray;
		foreach($dataArray as $data){
			$data = mysql_real_escape_string($data);
		}
	}
	public function setValues(){
		foreach ($this->dataArray as $name=>$val)
		{
			switch ($name) {
				case 'mail': $this->setMail($val);break;
				case 'nombre': $this->setNombre($val);break;
				case 'ubicacion': $this->setUbicacion($val);break;
				case 'admin': $this->setAdmin($val);break;
				default:
					;
				break;
			}
		}
		foreach ($this->dataArray as $name=>$val)
		{
			if (!isset($toUPDATE))
				$toUPDATE = $name . "='" . $val."'";
			else
				$toUPDATE = $toUPDATE . ", " . $name . "='" . $val."'";
		}
		if ($toUPDATE != NULL){
			$doQuery = "UPDATE djs SET ".$toUPDATE." WHERE id=".$this->djID;
			$this->Conexion();
			$this->Usuarios();
			$query = $this->query($doQuery);
			if (!$query){
				throw new Exception("Error Processing Request: ".mysql_error(), 1);
			}
		}
	}
}