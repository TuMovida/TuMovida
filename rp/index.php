<?php
	$dbhost = 'localhost';
	$dbname = 'tumovida_usuarios';
	$dbuser = 'tumovida_bd';
	$dbpass = '1123581321';
	
require_once('lib/nusoap.php') ;
$server = new nusoap_server; 		// Create server instance

$server->	configureWSDL("Consulta", "urn:consulta");

$server->	register(
					'Consulta_TM', 
					array ('Documento' =>  'xsd:string', 'Subagencia' => 'xsd:integer', 'Caja' => 'xsd:integer'), 
					array ('Documento' => 'xsd:string', 'Nombre' => 'xsd:string', 'Status' => 'xsd:integer', 'Mensaje' => 'xsd:string', 'Facturas' => 'SOAP-ENC:Array'),
					'urn:consulta',
					'urn:consulta#Consulta_TM',
					'rpc',
					'encoded',
					'Consulta de RedPagos'
					);
$server->	register(
					'Confirmacion_pago', 
					array ('Documento' => 'xsd:string', 'Factura_numero' => 'xsd:integer', 'Factura_moneda' => 'xsd:integer', 'Factura_importe' => 'xsd:integer', 'Subagencia' => 'xsd:integer', 'Caja' => 'xsd:integer', 'Movimiento' => 'xsd:integer'), 
					array ('Status' => 'xsd:integer', 'Mensaje' => 'xsd:string', 'NroOperacion_TM' => 'xsd:integer'),
					'urn:consulta',
					'urn:consulta#Confimacion_pago',
					'rpc',
					'encoded',
					'Confirmacion de pago'
					);
$server->	register(
					'Anulacion_Pago', 
					array ('Subagencia' => 'xsd:integer', 'Caja' => 'xsd:integer', 'Movimiento' => 'xsd:integer', 'NroOperacion_TM' => 'xsd:integer'), 
					array ('Status' => 'xsd:integer', 'Mensaje' => 'xsd:string', 'NroOperacion_TM' => 'xsd:integer'),
					'urn:consulta',
					'urn:consulta#Anulacion_Pago',
					'rpc',
					'encoded',
					'Confirmacion de pago'
					);					
		
function Consulta_TM($Documento, $Subagencia, $Caja){

	$dbhost = 'localhost';
	$dbname = 'tumovida_usuarios';
	$dbuser = 'tumovida_bd';
	$dbpass = '1123581321';
	$conn 	= mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_select_db($dbname, $conn);

	$Facturas= array();		//Crea el array.

	$query = "SELECT Nombre, Apellido FROM usuarios WHERE CI = '$Documento';";
		$Resultado = mysql_query($query);
			$userData 	= mysql_fetch_array($Resultado, MYSQL_ASSOC);
	if(mysql_num_rows($Resultado) < 1){	
		$Status = 15;
		return array('Documento' => $Documento, 'Nombre' => '', 'Status' => $Status, 'Mensaje' => 'CLIENTE INEXISTENTE');
	
	}else{
		$query2 = "SELECT * FROM compras WHERE CI = '$Documento' AND Estado = 0;";
			$Resultado2 = mysql_query($query2, $conn) or die(mysql_error());
				$totEmp = mysql_num_rows($Resultado2);
	
		if ($totEmp > 0) {
			while ( $rowEmp = mysql_fetch_assoc($Resultado2) ) {
				$numero			= $rowEmp['id'];
				$moneda			= $rowEmp['Moneda'];
				$importe		= $rowEmp['Importe'];
				$descripcion 	= $rowEmp['Descripcion'];
				$cantidad		= $rowEmp['Cantidad'];
				
				$Factura 	= array('Numero' => $numero, 'Moneda' => $moneda, 'Importe' => $importe, 'Descripcion' => $descripcion, 'Cantidad' => $cantidad);
				$Facturas[] = new soapval('Factura', 'xsd:string', $Factura);
			}
		}
		$Nombre 	= $userData['Nombre'].' '.$userData['Apellido']; 	//Nombre del adquirente
		$Status 	= 0;												//Status
		return array('Documento' => $Documento, 'Nombre' => $Nombre, 'Status' => $Status, 'Mensaje' => '','Facturas' => $Facturas);
	}
}

function Confirmacion_pago($Documento, $Factura_numero, $Factura_moneda, $Factura_importe, $Subagencia, $Caja, $Movimiento){
	$dbhost = 'localhost';
	$dbname = 'tumovida_usuarios';
	$dbuser = 'tumovida_bd';
	$dbpass = '1123581321';
	
	$Numero 	= $Factura_numero;
	$Moneda 	= $Factura_moneda;
	$Importe 	= $Factura_importe;
	
	$Documento;
	$Subagencia; 		//Subajencia
	$Caja;				//Caja
	$Movimiento;		//Movimiento
	//fwrite(fopen("logs/Pruebas.txt", "a+"), $Numero ."\n". $Moneda ."\n". $Importe ."\n". $Subagencia ."\n". $Caja ."\n". $Movimiento."\n");
	
	$conn 	= mysql_connect($dbhost, $dbuser, $dbpass);
	mysql_select_db($dbname, $conn);
	$query = "SELECT Estado FROM compras WHERE id = '$Numero';";
	$Resultado = mysql_query($query);
	$Estado	= mysql_fetch_array($Resultado, MYSQL_ASSOC);
	
	/* PRUEBA DE ERRORES */
	if ($Estado['Estado'] == 1){
		$YaComprada = true;
	}else{
		$YaComprada = false;
	}
	/* INSERTA OPERACION */
	$query = "INSERT INTO operaciones (Documento, NroFactura, Moneda, Importe, Subagencia, Caja, Movimiento) VALUES (".$Documento.", ".$Numero.", ".$Moneda.", ".$Importe.", ".$Subagencia.", ".$Caja.", ".$Movimiento.")";
	$Resultado = mysql_query($query, $conn);
	if (!$Resultado){
		//Hubo errores insertando la operacion
		fwrite(fopen("logs/errores.html", "a+"), "<hr>Error: ".mysql_error()."<br />\n".$query."\n");
		/* RETORNO DE ERROR GRAVE */
		$Status 		= 30;
		$Mensaje 		= "NO HAY CONEXION CON TM";
		$NroOpracion_TM = 0;
	}else{
		
		$query = "SELECT NroOperacion FROM operaciones WHERE Movimiento = $Movimiento";
		$query = mysql_query($query);
		$NroOpracion = mysql_fetch_array($query, MYSQL_ASSOC);
		fwrite(fopen("logs/Pruebas.txt", "a+"), "---------------\n".$NroOpracion['NroOperacion']."\n---------------\n");
		
		/* RETORNO */
		if ($YaComprada == true){
			/* ERROR (23) FACTURA YA PAGA */
			$Status 		= 23;
			$Mensaje 		= 'FACTURA YA PAGA';
			$NroOpracion_TM = 0;
		}else{
			$Status 		= 0;
			$Mensaje 		= '';	
			$NroOpracion_TM = $NroOpracion['NroOperacion'];
			$query = "UPDATE compras SET Estado=1, NroOperacion=".$NroOpracion_TM." WHERE id=".$Factura_numero;
			$query = mysql_query($query, $conn);
		}
	}
	
	
	
	return array('Status' => $Status, 'Mensaje' => $Mensaje, 'NroOperacion_TM' => $NroOpracion_TM);
}

function Anulacion_Pago($Subagencia, $Caja, $Movimiento, $NroOperacion_TM){
	$dbhost = 'localhost';
	$dbname = 'tumovida_usuarios';
	$dbuser = 'tumovida_bd';
	$dbpass = '1123581321';
	$conn 	= mysql_connect($dbhost, $dbuser, $dbpass);
	/* Verificar que la compra no este anulada ya */
	mysql_select_db($dbname, $conn);
	$query = "SELECT Estado FROM compras WHERE NroOperacion=".$NroOperacion_TM;
	$query = mysql_query($query);
	$Estado = mysql_fetch_array($query, MYSQL_ASSOC);
	if ($Estado['Estado'] == 3){
		$YaAnulada = true;
	}else{
		$YaAnulada = false;
	}
	
	if($YaAnulada == true){
		$Status = 17;
		$Mensaje = 'FACTURA YA ANULADA';
	}else{
		$query = "UPDATE compras SET Estado=3 WHERE NroOperacion=".$NroOperacion_TM;
		mysql_select_db($dbname, $conn);
		$query = mysql_query($query, $conn);
		if (!$query){
			$Status 	= 31;
			$Mensaje 	= "NO SE PUDO CONECTAR CON TM";
		}else{
			$Status = 0;
			$Mensaje = '';
		}
			
	}
	$NroOperacion_TM = $NroOperacion_TM;
	return array('Status'=>$Status, 'Mensaje'=>$Mensaje, 'NroOperacion_TM'=>$NroOperacion_TM);
}
// Use the request to (try to) invoke the service
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);    
?>
