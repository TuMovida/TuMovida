<?php
class lqsv extends pagina
{
	private $q;
	function __construct(){
		$this->TM();
		$this->q = $this->query("SELECT id, Nombre, Fecha, Hora, Imagen FROM eventos WHERE Fecha>CURDATE() ORDER BY Fecha ASC LIMIT 0, 5");
	}
	public function getJSON(){
		if ($this->q == false)
			return false;
		$return = array();
		while ($evento = mysql_fetch_assoc($this->q)){
			$this->pagina['Fecha'] = $evento['Fecha'];
			$evento['Fecha'] = $this->fecha("lqsv");
			$return[] = $evento;
		}
		return json_encode($return);
	}
}

if (isset($_GET['json'])){
	$lqsv = new lqsv(); 
	echo $lqsv->getJSON();
}
?>