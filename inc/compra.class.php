<?php
include_once "paginas.class.php";
include_once "conectar.php";
@session_start();
date_default_timezone_set("America/Montevideo");
class Compra extends Conectar
{
	private $usuario, $compra, $promo, $importe;
	public function __construct($idUsuario, $idArticulo, $cantidad, $rrpp)
	{
		$conn = $this->Conexion();
		$conn = $this->TM();	
		$promoArray = mysql_fetch_assoc($this->query("SELECT * FROM promociones WHERE id=".$idArticulo));
		if (!$promoArray)
			throw new Exception("3. Error Processing Request", 1);
			
		$promo = new promocion;
		$promo->setPagina($promoArray);
		if (!$promo)
			throw new Exception("Parce que la promocion no existe", 2);
		$this->promo = $promo;
		$importe 		= $cantidad * $promoArray['Valor'];
		$this->importe = $importe;
		$descripcion 	= $promo->nombre();
		$usuario = new Usuario($idUsuario);
		if (!$usuario)
			throw new Exception("Error Processing Request", 1);
			
		$this->usuario = $usuario;
		$query = $this->query("INSERT INTO compras (idArticulo, CI, Cantidad, Importe, Descripcion, RRPP, Fecha) 
						VALUES ($idArticulo, ".$usuario->getCI().", $cantidad, '$importe', '".$promo->nombre()."', '$rrpp','".date('Y-m-d H:i:s')."')");
		
		$this->setLog();
		return $query;
	}
	private function setLog()
	{
		$usuario = $this->usuario;
		$promo = $this->promo;
		$archivo = fopen("../logs/".date("d-m-Y").".html", "a+");
		$string = "[".date("H:i:s")."]<b>".$usuario->getNombre()." ".$usuario->getApellido()."</b> (".$usuario->getCI().") compró <b>". $promo->Nombre(). "</b> con un valor total de <b>$" . $this->importe ."</b><br />\n";
		fwrite($archivo, $string);
	}
}
?>