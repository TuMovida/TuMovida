$(document).on("ready", function(){
	$("#usuario").on("click", "li.entrar", function(e){
		dialog.open("ajax/login.php");
	});
	/*
	$("#usuario").on("click", "li.registrarse", function(e){
		dialog.open("ajax/registro.php");
	});
	*/
	$("#usuario").on("click", "li.logout", function(e){
		$.get("ajax/user.php", "logout", function(res){
			if (res != "")
				dialog.open("ajax/user.php?logout");
				$("#usuario").load("index.php #usuario");
		});
	});
	$(document).on("click", ".entrar", function(e){
		e.preventDefault();
		dialog.open("ajax/login.php");
	});
	$(document).on("click", ".registrarse", function(e){
		e.preventDefault();
		dialog.open("ajax/registro.php");
	});
	$("#usuario").on("click", "li.usuarioNombre", function(e){
		$.address.value("#!/usuario/miPerfil");
	});
	$(".usuario-dropMenu").dropMenu();
	$(document).on("click", "a.misMensajes", function(e){
		e.preventDefault();
		dialog.open("ajax/misMensajes.php");
	});
	$(document).on("click", "a.misInvitaciones", function(e){
		e.preventDefault();
		dialog.open("ajax/misInvitaciones.php");
	});
	$(document).on("click", "a.misCompras", function(e){
		e.preventDefault();
		dialog.open("ajax/misCompras.php");
	});
});
//var usuarioDropMenu = "<ul class='usuario-dropMenu'><li class='userMenu-verPerfil'>Ver Perfil</li><li class='userMenu-editarPerfil'>Editar Perfil</li><hr /><li class='userMenu-misMensajes'>Mis Mensajes</li><li>Mis Invitaciones</li><li class='misCompras'>Mis Compras</li></ul>";