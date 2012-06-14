<?php
	(isset($_GET['para'])) ? $to = "value='".$_GET['para']."'" : $to = '';

?>
<div class='formulario dialogo-enviarMensaje'>
	<label>Para</label>
	<input <?=$to?> type='text' class='enviarMensajeForm-Para'/>
	<input type='hidden' id='idRemitente'/>
	<label>Mensaje</label>
	<textarea id="enviarMensaje-texto" class='enviarMensajeForm-Mensaje'></textarea>
	<div style='padding-top: 10px; text-align: right;'>
		<button onclick='dialog.destroy()' class='button'>Cancelar</button>
		<button id='enviarMensaje-btnEnviar' class='button'>Enviar</button>
	</div>
</div>
<script type="text/javascript">
var cache = {}, lastXhr;
$(".enviarMensajeForm-Para").autocomplete({
	minLenght: 2,
	source: function(request, response){
		$.ajax({
			url: "ajax/user.php?getList",
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
		$('.enviarMensajeForm-Para').val(ui.item.label);
		$('#idRemitente').val(ui.item.valor);
		return false;
	}
}).data( "autocomplete" )._renderItem = function( ul, item ) {
	return $( "<li></li>" )
	.data( "item.autocomplete", item )
	.append( "<a>" + item.label + "</a>" )
	.appendTo( ul );
};
$(".enviarMensajeForm-Mensaje").focus();
$("#enviarMensaje-btnEnviar").click(function(e){
	e.preventDefault();
	var data = 	"remitente=" + $(".dialogo-enviarMensaje #idRemitente").val() + 
				"&mensaje=" + $(".dialogo-enviarMensaje #enviarMensaje-texto").val();
	$.post("ajax/user.php?enviarMensaje", data, function(res){
		alert(res);
	});
});
</script>