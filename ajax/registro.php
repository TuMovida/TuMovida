<div style="display: inline-bock; width: 100px;"></div>
<div style="display: inline-block;">
	<form class="formulario" id="formulario-registro">
	<fieldset>
		<label>Nombre</label>
		<input type="text" name="nombre" title="Ingrese su nombre real"/>
		<label>Apellido</label>
		<input type="text" name="apellido" title="Ingrese su apellido.<br/>Recuerde que TM proteje sus datos de forma segura."/>
		<label>Tu correo electrónico</label>
		<input id='registro_mail' type="text" name="mail" title="Con este email usted ingresará al sitio." />
		<label>Tu contraseña</label>
		<input type="password" title="Escribe tu contraseña" name="password"/>
		<label>Cédula</label>
		<input type="text" name="ci" title="Cédula de identidad" />
		<label>Sexo</label>
		<select name='sexo' title="Escoja su genero">
			<option>Mujer</option>
			<option>Hombre</option>
		</select>
		<label>Fecha de nacimiento</label>
			<select name='day' title="Día en el que nació">
				<?php
				for($i=1; $i<32; $i++)
					echo "<option value='$i'>" . $i . "</option>";
				?>
			</select>
			<select name='month' title="Mes en el que nació">
				<?php
				$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
					"Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
				for($i=0; $i<12; $i++)
					echo "<option value='$i'>" . $meses[$i] . "</option>";
				?>
			</select>
			<select name='year' title="Año de nacimiento">
				<?php
				for($i=2012; $i>1929; $i--)
					echo "<option value='$i'>" . $i . "</option>";
				?>
			</select>
		<input type="submit" value="Entrar" id="registro-enviar" class="button"/>
	</fieldset>
	</form>
</div>
<script type="text/javascript">
$("form#formulario-registro #registro-enviar").click(function(e){
	e.preventDefault();
	$.ajax({
		url: 'ajax/user.php?checkMail='+$("form#formulario-registro #registro_mail").val(),
		success: function(res){
			if (res === "false"){
				createGrowl("El mail seleccionado ya esta registrado", false);
				e.stopInmediatePropagation();
			}
		}
	});
	var data = $("form#formulario-registro").serialize();
	$("form#formulario-registro input").each(function(index){
		if ($(this).val() == ""){
			createGrowl("Hay formularios en blanco", false);
			e.stopInmediatePropagation();
		}
	});
	$.post("ajax/user.php?new", data, function(res){
		dialog.destroy();
		createGrowl("¡Te has registrado con exito!<br />Ya podes iniciar sesión", false);
		$("#usuario").load("index.php #usuario");
	});
});
</script>