<?php
class destacados extends pagina
{
	private $q;
	function __construct(){
		$this->TM();
		$this->q = $this->query("SELECT * FROM destacados WHERE Activo=1");
	}
	public function getJSON(){
		if ($this->q == false)
			return false;
		$return = array();
		while ($destacado = mysql_fetch_assoc($this->q)){
			$return[] = $destacado;
		}
		return json_encode($return);
	}
}

if (isset($_GET['json'])){
	$destacados = new destacados();
	echo $destacados->getJSON();
}
?>