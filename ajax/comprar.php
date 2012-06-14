<?php
@session_start();
require_once '../inc/conectar.php';
require_once '../inc/paginas.class.php';
require_once '../inc/usuario.class.php';
require_once '../inc/compra.class.php';
include_once '../inc/mail.class.php';

if (!isLogged()){
	
	echo "<div class='formulario'><label>¡Debes iniciar sesión para realizar compras!</label></div>";
	echo "<script type='text/javascript'> setTimeout(function(){dialog.destroy(function(){dialog.open('ajax/login-registro.php'); }); }, 500); </script>";
	exit;
}
if (!isset($_POST['compra']) && !isset($_GET['id']))
	exit("<div class='formulario'><label>Parece que hubo un error procesando su solicitud</label></div>");

if (isset($_POST['compra'])){
	if (isset($_POST['idA']) && isset($_POST['cant']))
	try{
		$usuario 		= $_SESSION['idusuario'];
		$idArticulo 	= $_POST['idA'];
		$cantidad 		= $_POST['cant'];
		(!isset($_POST['rrpp'])) ? $rrpp = '' : $rrpp = $_POST['rrpp'];
		$compra = new Compra($usuario, $idArticulo, $cantidad, $rrpp);
		echo "La compra se ha realizado con éxito";
		$mail = new sendMail($usuario);
		$mail->sendCompra($idArticulo, $cantidad);
	}catch(Exception $e){
		echo $e->getMessage();
	}
	exit;
}

$promo = new promocion;
$c = new Conectar;
$c->TM();
$promoArray = mysql_fetch_assoc($c->query("SELECT id, Valor FROM promociones WHERE id=".$_GET['id']));
$promo->setPagina($promoArray);
?>
<div class="formulario" id="formulario-compra"> 
<form id="formulario-compra">
	<label for="cantidad">Cantidad</label>
	<select style='width: 100%;' id='cantidad' title='¿Cuantas quieres?'>
		<?php for($i=1; $i<21; $i++): ?>
		<option><?=$i?></option>
		<?php endfor; ?>
	</select>
	<label for="rrpp">RRPP</label>
	<input id="rrpp" title="<b>¿Algún vendedor asistió tu compra en TM?</b><br />En ese caso ingresa su nombre aquí. De lo contrario deja el campo vacío"/>
	<label>Importe a pagar</label>
	<div id="importe" style='font-weight: bold;'>$</div>
	<input type="hidden" id="rrppID" />
	<hr style='border: 0 0 1px 0 solid rgb(200,200,200);'/>
	<input type="submit" value="¡ Comprar !"/>
</form>
</div>
<script type="text/javascript">
var valor = <?=$promo->valor()?>;
var importe = valor;

$("#importe").text("$"+importe);
$("#cantidad").on("change", function(){
	importe = valor * $(this).val();
	$("#importe").text("$"+importe);
});
var cache = {}, lastXhr;
$("#rrpp").autocomplete({
	minLenght: 2,
	source: function(request, response){
		$.ajax({
			url: "ajax/rrpp.php?getList",
			dataType: "json",
			data: "term=" + request.term,
			success: function( data ){
				response($.map(data, function(item){
					return{ 
						label: item.Nombre + ' ' + item.Apellido, 
						valor: item.id
						};
				}));
			}
		});
	},
	select: function( event, ui ){
		$('#rrpp').val(ui.item.label);
		$('#rrppID').val(ui.item.valor);
		return false;
	}
}).data( "autocomplete" )._renderItem = function( ul, item ) {
	return $( "<li></li>" )
	.data( "item.autocomplete", item )
	.append( "<a>" + item.label + "</a>" )
	.appendTo( ul );
};
$("form#formulario-compra").submit(function(e){
	e.preventDefault();
	var idA 	= <?=$promoArray['id']?>;
	var rrpp 	= $("#rrppID").val();
	var cant 	= $("#cantidad").val();
	var dataString = "compra=<?=time()?>&idA="+idA+"&rrpp="+rrpp+"&cant="+cant;
	$.post("ajax/comprar.php", dataString, function(res){
		//alert(res);
		if (res !== "ERROR")
		{
			dialog.destroy(function(){
				dialog.show("<div class='formulario' style='font-size:12px; color: rgb(50,50,50);'><label><b>¡La compra se ha realizado con éxito!</b></label>Ahora solo hace falta ir a pagar a RedPagos.<br/>Encuentre el local abierto más cercano haciendo <a class='button' target='_blank' href='mapa.php'>click aqui</a></div>");
			});
		}
		/*
		piwikTracker.trackEcommerceOrder(
		idA+"-xxx", // (required) Unique Order ID
		importe, // (required) Order Revenue grand total (includes tax, shipping, and subtracted discount)
		30, // (optional) Order sub total (excludes shipping)
		5.5, // (optional) Tax amount
		4.5, // (optional) Shipping amount
		false // (optional) Discount offered (set to false for unspecified parameter)
		);
		piwikTracker.trackPageView();
		*/
	});
});
</script>