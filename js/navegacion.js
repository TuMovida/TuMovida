var ir = {
	ubicacion: "index",
	a: function(){
		var localizacion = arguments[0];
		if (arguments[1] !== undefined)
			var id = arguments[1];
		else
			var id = undefined;
		ir.ubicacion = localizacion;
		if(localizacion === "index"){
			$.address.title("¡TuMovida!");
			this.cargar("promociones");
			this.mostrar("destacados");
		}
		if(localizacion === "eventos"){
			$.address.title("¡TuMovida! - Eventos");
			this.cargar("eventos");
			this.ocultar("destacados");
		}
		if(localizacion === "promociones"){
			$.address.title("¡TuMovida! - Promos");
			this.cargar("promociones");
			this.ocultar("destacados");
		}
		if(localizacion === "editarPerfil"){
			$.address.title("¡TuMovida! - Editar Perfil");
			this.cargar("editarPerfil");
			this.ocultar("destacados");
		}
		if(localizacion === "evento"){
			this.cargar("evento", id);
			this.ocultar("destacados");
		}
		if(localizacion === "local"){
			this.cargar("local", id);
			this.ocultar("destacados");
		}
		if(localizacion === "promo"){
			this.cargar("promo", id);
			this.ocultar("destacados");
		}
		if(localizacion === "usuario"){
			this.cargar("usuario", id);
			this.ocultar("destacados");
		}
		if(localizacion === "dj"){
			this.cargar("dj", id);
			this.ocultar("destacados");
		}
		if(localizacion === "fotos"){
			this.cargar("fotos");
			this.ocultar("destacados");
		}
		if(localizacion === "album"){
			this.cargar("album", id);
			this.ocultar("destacados");
		}
		if(localizacion === "foto"){
			//this.cargar("album", arguments[2]);
			this.ocultar("destacados");
			if($(".photoDisplay").is(":visible")){
				photoDisplay.change("ajax/photoDisplay.php?id="+id);
			}else{
				photoDisplay.open("ajax/photoDisplay.php?id="+id);
			}
		}else{
			$(window).scrollTop(0);
		}
	},
	cargar: function(objeto){
		ir.cargando();
		var piwikTrackAJAXresponse = function(){
			try{
				piwikTracler.setCustomUrl(document.location.href);
				piwikTracker.setDocumentTitle(document.title);
				piwikTracker.trackPageView();
			}catch(err){}
			ir.ocultarCargando();
		}
		var id = arguments[1];
		if (id !== undefined){
			$("#main").load("ajax/" + objeto + ".php?id=" + id, function(res){
				try{
					piwikTracler.setCustomUrl(document.location.href);
					piwikTracker.setDocumentTitle(document.title);
					piwikTracker.trackPageView();
				}catch(err){}	
				ir.ocultarCargando();
			});
		}else{
			$("#main").load("ajax/" + objeto + ".php", piwikTrackAJAXresponse);
		}
		// ir.icono();
	},
	mostrar: function(objeto){
		if ($("#"+objeto).is(":hidden")){
			$("#"+objeto).slideDown(300);
			if (objeto == "destacados")
				destacados.init();
		}
	},
	ocultar: function(objeto){
		if ($("#"+objeto).is(":visible")){
			$("#"+objeto).slideUp(300);
			if (objeto == "destacados")
				destacados.kill();
		}
	},
	cargando: function(){
		var mainWidth = $("#main").width();
		var mainHeight = $("#main").height();
		var mainPos = $("#main").position();
		$("#main").animate({
			opacity: 0.4
		}, 100);
	},
	ocultarCargando: function(){
		$("#main").animate({
			opacity: 1
		}, 500);
	},
	icono: function(){
		console.log(ir.ubicacion);
		function setActive(name){
			$("#navegacion li a.navBtn").each(function(index){
				var classes = $(this).attr("class");
				classes = classes.split(" ");
				for (var i = 0; i<classes.length; i++){
					var isActive = classes[i].substr(classes[i].length-6);
					if(isActive === "Active"){
						$(this).removeClass(classes[i]);
					}
				}
			});
			var $icon = $("#navegacion li a.navBtn"+name);
			if(!$icon.hasClass("navBtn"+name+"Active"))
				$icon.addClass("navBtn"+name+"Active");
		}
		
		if(ir.ubicacion == "index"){
			setActive("Inicio");
		}else if(ir.ubicacion == "eventos"){
			setActive("Eventos");
		}
		if(ir.ubicacion == "promociones"){
			setActive("Promos");
		}
		if(ir.ubicacion == "fotos"){
			setActive("Fotos");
		}
	}
};
$.address.crawlable(true);
$.address.change(function (event){
	switch ($.address.value()){
	case "/":
		nav = ir.a("index");
		break;
	case "/eventos": 
		nav = ir.a("eventos");
		break;
	case "/promos":
		nav = ir.a("promociones");
		break;
	case "/editarPerfil":
		nav = ir.a("editarPerfil");
		break;
	case "/fotos":
		nav = ir.a("fotos");
		break;
	}
	//Eventos:
	if(/^\/evento\/[0-9]+/.test($.address.value()) === true){
		var id = $.address.value().replace("/evento/", "");
		nav = ir.a("evento", id);
	}
	//Locales:
	if(/^\/local\/[0-9]+/.test($.address.value()) === true){
		var id = $.address.value().replace("/local/", "");
		nav = ir.a("local", id);
	}
	//Promos:
	if(/^\/promo\/[0-9]+/.test($.address.value()) === true){
		var id = $.address.value().replace("/promo/", "");
		nav = ir.a("promo", id);
	}
	//Usuarios:
	if(/^\/usuario\/[0-9]+/.test($.address.value()) === true){
		var id = $.address.value().replace("/usuario/", "");
		nav = ir.a("usuario", id);
	}
	//DJ:
	if(/^\/dj\/[0-9]+/.test($.address.value()) === true){
		var id = $.address.value().replace("/dj/", "");
		nav = ir.a("dj", id);
	}
	//Albums: 
	if(/^\/album\/[0-9]+$/.test($.address.value()) === true){
		var id = $.address.value().replace("/album/", "");
		nav = ir.a("album", id);
	}
	//Fotos:
	if(/^\/album\/[0-9]+\/foto\/[0-9]+/.test($.address.value()) === true){
		var id = $.address.value().split("/");
		var idAlbum = id[2];
		var idFoto = id[4];
		var parse = idAlbum + "&foto=" + idFoto;
		nav = ir.a("foto", parse, idAlbum);
	}
});
var logoCheck = function(){
	if($("#logoTM img").is(":visible")){
		var offset = $("#logoTM img").offset();
		var total = (offset.top - 40) + $("#logoTM img").height();
		if (total < $(document).scrollTop() ){
			if ($("#logo img").is(":hidden"))
				$("#logo img").fadeIn();
		}else{
			if ($("#logo img").is(":visible"))
				$("#logo img").fadeOut();
		}
	}else{
		if ($("#logo img").is(":hidden"))
				$("#logo img").fadeIn();
	}
};
$(document).scroll(logoCheck);
$(document).ajaxSuccess(function(){
	logoCheck();
	ir.icono();
});