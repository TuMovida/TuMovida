<?php
include_once 'conectar.php';

class pagina extends Conectar{
	private $retorno;
	public $pagina;
	
	function __construct(){
		$this->pagina = array();
	}
	/*
	 * SETTER
	 */
	public function setPagina($pagina){
		$this->pagina = $pagina;
	}
	/*
	 * Retorna el NOMBRE de la pagina
	 */
	function nombre($sinFormato = TRUE){
		$nombre = $this->pagina['Nombre'];
		if ($sinFormato){
			return $nombre; 
		}
		return "<h2>".$nombre."</h2>";	
	}
	/*
	 * Descripción
	 */
	function descripcion($sinLimpiar = FALSE){
		$descripcion = $this->pagina['Descripcion'];
		if ($sinLimpiar){
			return $descripcion; 
		}
		
		$HTMLnoPermitido = array("<p>", "</span>");
		$descripcion = str_replace("</p>", "<br />", $descripcion);
		$descripcion = preg_replace("/<span[^>]*>(.*?)/", "", str_replace($HTMLnoPermitido, '', $descripcion));
		
		return nl2br($descripcion);
		
	}
	function imagen(){
		return $this->pagina['Imagen'];
	}
	/*
	 * Fecha
	 */
	function fecha($formato){
		$fecha = $this->pagina['Fecha'];
		
		$hoy = date("Y-m-d");
		
		//Semama:
		$l = date("l", strtotime($fecha));
		//Traduccion a inglés:
		$l_E = array("Sunday"=>"Domingo", "Monday"=>"Lunes", "Tuesday"=>"Martes", "Wednesday"=>"Miércoles", 
							"Thursday"=>"Jueves", "Friday"=>"Viernes", "Saturday"=>"Sábado");
		//Fecha
		$j = date("j", strtotime($fecha));
		$mes = date("F", strtotime($fecha));
		$mes_E = array("January"=>"Enero", "February"=>"Febrero", "March"=>"Marzo", "April"=>"Abril", 
							"May"=>"Mayo", "June"=>"Junio", "July"=>"Julio", "September"=>"Setiembre", 
							"August"=>"Agosto", "October"=>"Octubre", "November"=>"Noviembre", "December"=>"Diciembre");
		
		$año = date("Y", strtotime($fecha));
		$FechaEvento = $l_E[$l]." ".$j." de ".$mes_E[$mes]." del ".$año;
		$FechaLQSV = $FechaEvento = $l_E[$l]." ".$j." de ".$mes_E[$mes];
		
		//$hora = explode(":", $rowEmp['Hora']);
		
		$retorno = array("Dia"=>$l_E[$l], "Fecha"=>$j, "Mes"=>$mes_E[$mes], "Año"=>$año, "Completa" => $FechaEvento, "lqsv" => $FechaLQSV);
		
		if(!isset($formato))
			$formato = null;
		if ($formato == "dia"){
			return $retorno['Dia'];
		}
		if ($formato == "mes"){
			return $retorno['Mes'];
		}
		if ($formato == "año"){
			return $retorno['Año'];
		}
		if ($formato == "fecha"){
			return $retorno['Fecha'];
		}
		if ($formato == "completa"){
			return $retorno['Completa'];
		}
		if ($formato == "lqsv"){
			return $retorno['lqsv'];
		}
		return $retorno;
	}
	/*
	 * Facebook
	 */
	function facebook(){
		if(!isset($this->pagina['Facebook']) || $this->pagina['Facebook'] == ""){
			return FALSE;
		}else{
			$facebook = $this->pagina['Facebook'];
			$facebook = str_replace("http://", "", $facebook);
			$facebook = str_replace("https://", "", $facebook);
			$facebook = str_replace("www.", "", $facebook);
			$facebook = str_replace("facebook.com/", "", $facebook);		
			$retorno = "http://www.facebook.com/".$facebook;
			return $retorno;
		}
	}
	function ubicacion(){
		return $this->pagina['Ubicacion'];
	}
	function mapa(){
		if ($this->pagina['Mapa'] == null)
			return false;
		return $this->pagina['Mapa'];
	}
}

/*
 * Evento
 */
class evento extends pagina{
	function destacado(){
		if ($this->pagina['Destacado'] == 1)
			return TRUE;
		return FALSE;
	}
	function local(){
		if (!isset($this->pagina['Local']) || $this->pagina['Local'] == null)
			return false;
		$local = $this->pagina['Local'];
		
		$query = mysql_query("SELECT Nombre, Descripcion, Imagen FROM locales WHERE id=".$local);
		if ($query)
			$local = mysql_fetch_assoc($query);
			
		$borrar = array("<br>", "<br />", "<p>", "</p>");
		$descripcionLocal = "<p>".htmlentities(str_replace($borrar, " ", $local['Descripcion']), null, 'UTF-8')."</p>";
		echo $descripcionLocal;
		$hovercard = "<a id='eventoLocal' href='#'>".$local['Nombre']."</a>";
		$hovercard.= "
		<script> 
		$(document).ready(function(){
			var hoverHTMLDemoBasic = '".$descripcionLocal."'; 
			$('#eventoLocal').hovercard({
				detailsHTML: hoverHTMLDemoBasic, 
				width: 400,
				cardImgSrc: 'http://www.tumovida.com.uy/img/locales/".$local['Imagen']."' 
			}); 
		}); 
		</script>
		";
		return $hovercard;
	}
	function edad(){
		if ($this->pagina['Edad'] == 0)	
			return false;
		return 	"+".$this->pagina['Edad'];
	}
	public function getPromo()
	{
		if (isset($this->pagina['id_promo']) && $this->pagina['id_promo'] != null)
			return $this->pagina['id_promo'];
		else return false;
	}
	public function getVisitas()
	{
		if(!isset($this->pagina['Visitas']))
			return false;
		return $this->pagina['Visitas'];
	}
	public function setVisita()
	{
		$visitas = $this->getVisitas();
		$visitas++;
		mysql_query("UPDATE eventos SET Visitas=".$visitas." WHERE id=".$this->pagina['id']);
	}
}
/*
 * Local
 */
class local extends pagina{
	public $id, $eventos, $eventosFuturos;
	private $coneccion;
	
	private function existenFotos($id, $conexion){
		$llamado = mysql_query("SELECT id FROM locales_fotos WHERE idLocal=".$id, $conexion) or die(mysql_error());
		if ( mysql_num_rows($llamado) > 0)
			return true;
		return false;
	}
	function galeria($id){
		if (!isset($id))
			$id = $this->pagina['id'];
			
		if ($this->existenFotos($id))
		{
			$llamarFotos = "SELECT Fotos FROM locales_fotos WHERE idLocal=".$id;
			$llamarFotos = mysql_query($llamarFotos);
			if ($llamarFotos){
				$var = 0;
				while ($f =  mysql_fetch_array($llamarFotos, MYSQL_ASSOC)){
					$var++;
					$fotos = $f['Fotos'];
					
					$foto = explode(",", $fotos);
					
					foreach ($foto as $url){
						$url = str_replace("{", "", $url);
						$url = str_replace("[", "", $url);
						$url = str_replace("]", "", $url);
						$url = str_replace("}", "", $url);
						$url = str_replace(" ", "", $url);
						
						$retorno = "<div class='galeriaFotoLocal'>";
						$retorno.= "<a class='fancyFoto' id='fotogaleria".$var."' rel='galeria_fotos' href='img/locales/".id."/".$url."'><img alt='' src='img/locales/".$id."/".$url."' /></a>";
						$retorno.= "</div>";
					}
					$retorno.="<div style='clear: both;'></div>";
				}
				return $retorno;
			}
			return false;
		}
		else
		{
			return false;
		}
		
	}
	public function getEventos(){
		$localID = $this->pagina['id'];
		$query = $this->query("SELECT id, Nombre, Fecha FROM eventos WHERE idLocal=".$localID);
		if(!$query || mysql_num_rows($query) < 1){
			return false;
		}
		$this->eventos = mysql_fetch_assoc($query);
		return $this->eventos;
	}
	public function getEventosFuturos(){
		if(isset($this->eventos) && $this->eventos != ""){
			foreach($this->eventos as $evento){
				if(date("Y-m-d h:i:s", strtotime($evento['Fecha'])) > date("Y-m-d h:i:s")){
					$eventosFuturos[] = $evento;
				}
			}
		}else{
			$localID = $this->pagina['id'];
			$query = $this->query("SELECT id, Nombre, Fecha FROM eventos WHERE idLocal=".$localID." AND Fecha > NOW()");
			if(!$query || mysql_num_rows($query) < 1){
				return false;
			}
			$this->eventosFuturos = mysql_fetch_assoc($query);
			return $this->eventosFuturos;
		}
		if(isset($eventosFuturos)){
			return $eventosFuturos;
		}
		return false;
	}
	public function getEventosPasados(){
		if(isset($this->eventos) && $this->eventos != ""){
			foreach($this->eventos as $evento){
				if(date("Y-m-d h:i:s", strtotime($evento['Fecha'])) < date("Y-m-d h:i:s")){
					$eventosPasados[] = $evento;
				}
			}
		}else{
			$localID = $this->pagina['id'];
			$query = $this->query("SELECT id, Nombre, Fecha FROM eventos WHERE idLocal=".$localID." AND Fecha < NOW()");
			if(!$query || mysql_num_rows($query) < 1){
				return false;
			}
			$this->eventosFuturos = mysql_fetch_assoc($query);
			return $this->eventosFuturos;	
		}
		if(isset($eventosPasados)){
			return $eventosPasados;
		}
		return false;
	}
}

/*
 * Promociones
 */
class promocion extends pagina{
	function condiciones($sinLista = false){
		if (!isset($sinLista) && $sinLista == null)
			$sinLista = FALSE;
		if (!isset($this->pagina['Condiciones']) || !($this->pagina['Condiciones'] <> ""))
			return false; //No hay lista	
			
		$condiciones = $this->pagina['Condiciones'];
			
		if (!$sinLista){
			return $condiciones;
		}
		else
		{
			$retorno = "<ul style='color: black; text-shadow: none;'>";
			$retorno.= str_replace("*", "<li>", $condiciones);
			$retorno.= "</ul>";
			
			return $retorno;
		}
	}
	function valor($conSigno=false)
	{
		if ($conSigno == true)
			return "$".$this->pagina['Valor'];
		return $this->pagina['Valor'];
	}
}
#################################
### VERSION: 24 / 05 / 2012  ####
#################################
###					         ###
###					      ###
###				       ###						
###					###
###				###
###          ###
###      ### ###							   #############
###   ###       ###							###				###
######             ###						###             ###
###                   ###					###				###
###                    ###					###				###
###                     ###					###				###
###                      ###  				###				###
###                       ###                  #############
class iPag extends Conectar
{
	public $id, $pagArray;
	
	public function __construct($id)
	{
		$this->id = $id;
	}
	public function getData($iPag)
	{
		$id = $this->id;
		$this->Conexion();
		$this->TM();
		$query = $this->query("SELECT * FROM ".$iPag." WHERE id=".$id);
		if (!$query){
			throw new Exception("Error Processing Request", 1);
			return false;
		}
		$array = mysql_fetch_assoc($query);
		if(!is_array($array)){
			throw new Exception("Error obteniendo datos", 2);
		}
		$this->pagArray = $array;
		return $array;
	}
	public function getID()
	{
		return $this->pagArray["id"];
	}
	public function getNombre()
	{
		return $this->pagArray["Nombre"];
	}
	public function getImagen()
	{
		if ($this->pagArray["Imagen"] == "")
			return false;
		return $this->pagArray["Imagen"];
	}
	public function getFecha()
	{
		if ($this->pagArray["Fecha"] == "")
			return false;
		return $this->pagArray["Fecha"];
	}
	public function getUbicacion()
	{
		if ($this->pagArray["Ubicacion"] == "")
			return false;
		return $this->pagArray["Ubicacion"];
	}
	public function getDescripcion()
	{
		if ($this->pagArray["Descripcion"] == "")
			return false;
		return $this->pagArray["Descripcion"];
	}
	public function getMapa()
	{
		if (!isset($this->pagArray["Mapa"]) || ($this->pagArray["Mapa"] == ""))
			return false;
		return $this->pagArray["Mapa"];
	}
	public function getEdad()
	{
		if (!isset($this->pagArray["Edad"]) || $this->pagArray["Edad"] == "")
			return false;
		return $this->pagArray["Edad"];
	}
}
class iEvento extends iPag
{
	public function __construct($id)
	{
		$this->id = $id;
		$getData = $this->getData("eventos");
		if (!$getData)
			return false;
	}
	public function getHora()
	{
		if (!isset($this->pagArray["Hora"]) || $this->pagArray["Hora"] == "")
			return false;
		return $this->pagArray["Hora"];
	}
	public function getAsistencias()
	{
		return $this->pagArray["Asistencias"];
	}
	public function getPromo()
	{
		if (!isset($this->pagArray["id_promo"]) || ($this->pagArray["id_promo"] == ""))
			return false;
		return new iPromo($this->pagArray["id_promo"]);
	}
}
class iLocal extends iPag
{
	public function getTelefono()
	{
		if (!isset($this->pagArray["Telefono"]) || $this->pagArray["Telefono"] == "")
			return false;
		return $this->pagArray["Telefono"];
	}
	public function getMail()
	{
		if (!isset($this->pagArray["Telefono"]) || $this->pagArray["Telefono"] == "")
			return false;
		return $this->pagArray["Mail"];
	}
	public function getWeb()
	{
		if (!isset($this->pagArray["Telefono"]) || $this->pagArray["Telefono"] == "")
			return false;
		return $this->pagArray["Web"];
	}
	public function getDiasAbiertos()
	{

	}
}
class iPromo extends iPag
{
	public function getValor()
	{

	}
	public function getFechaInicio()
	{

	}
	public function getFechaFinalizado()
	{

	}
	public function getActivo()
	{

	}
}
class iLista extends iPag
{
	public function __construct($id)
	{
		$this->id = $id;
		return $this->getData("listas");
	}
	public function getCapacidad()
	{
		return $this->pagArray["Disponibles"];
	}
	public function getRestantes()
	{
		return $this->pagArray["Disponibles"];
	}
}
class Asistencias extends Conectar
{
	public $dataArray;
	public function __construct()
	{
		$this->Conexion();
		$this->TM();
	}
	public function getAsistencias($idEvento)
	{
		$query = $this->query("SELECT id, idUsuario, Fecha FROM asistencias WHERE idEvento=".$idEvento. " AND block=0");	
		if (mysql_affected_rows() == 0)
			return false;
		while($res = mysql_fetch_assoc($query)){
			$buff[] = $res;
		}
		$this->dataArray = $buff;
		return $buff;
	}
	public function getUsuario()
	{
		if (isset($this->dataArray) && $this->dataArray != ""){
			return $this->dataArray['idUsuario'];	
		}
		return false;
	}
	public function getFecha()
	{
		if (isset($this->dataArray) && $this->dataArray != ""){
			return $this->dataArray['Fecha'];	
		}
		return false;
	}
}
?>