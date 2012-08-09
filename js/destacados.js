;(function($, window, undefined){
	var Slide = function (elemento, opciones){
		this.elemento = element;
		this.opciones = opciones;
		if (this.init){
			this.init(opciones);
		}
	}
	Slide.prototype = {
		defecto: {
			anterior: ".anterior",
			posterior: ".posterior"
		},
		init: function(opciones){
			this.config = $.extend({}, this.defecto, this.opciones);
		}
	}
	$.fn.slide = function(opciones){
		if (typeof opciones == "string"){
			metodo = opciones; 
			args = Array.prototype.slide.call(arguments, 1);
				var slider = this.data('slide') ? 
					this.data('slide') :
					new Slide(this);
		}
	}
	window.Slide = Slide;
})(jQuery, window)

var destacados = {
	index: 	0,
	fxIn: 	300,
	delay:	5000,
	fxOut: 	300,
	running:false,
	length: 0,
	motion: false,
	init: function(){
		this.length = $("div.destacado").length;
		var $descripcion = $("div.destacado").eq(this.index).children("div.destacado_descripcion");
		var $imagen = $("div.destacado").eq(this.index).children("div.destacado_imagen");
		$descripcion.show();
		$imagen.show();
		if($descripcion.is(":visible") && $imagen.is(":visible")){
			if(this.running === false)
				this.run();
		}
	},
	run: function(){
		if(this.running === true){
			setTimeout(function(){
				destacados.next(function(){
					if(destacados.running === true)
						destacados.run();
				});
			}, destacados.delay);
		}else{
			this.running = true;
			this.run();
		}
	},
	pause: function(){
		if(this.running === true)
			this.running = false;
	},
	next: function(callback){
		if(this.length-1 > this.index){
			this.goTo(this.index+1);
		}else{
			this.goTo(0);
		}
		if(typeof callback == "function")
			callback.call();
	},
	prev: function(){
		if(this.length-1 > this.index && this.index-1>=0){
			this.goTo(this.index-1);
		}else{
			this.goTo(0);
		}
		callback.call();
	},
	goTo: function(i){
		var self = this;
		var $descripcion = $("div.destacado").eq(this.index).children("div.destacado_descripcion");
		var $imagen = $("div.destacado").eq(this.index).children("div.destacado_imagen");
		var $nextDescripcion = $("div.destacado").eq(i).children("div.destacado_descripcion");
		var $nextImagen = $("div.destacado").eq(i).children("div.destacado_imagen");
		$descripcion.slideUp(self.fxOut, function(){
			$imagen.fadeOut(self.fxOut, function(){
				
			});
			$nextImagen.fadeIn(self.fxIn,function(){
					$nextDescripcion.slideDown(self.fxIn, function(){
						self.index = i;
						return true;
					});
				});
		});
	},
	kill: function(){
		this.pause();
	}
}

// var destacados = {
// 	i: 0,
// 	fxIn: 	300,
// 	delay:	2500,
// 	fxOut: 	300,
// 	running: true,
// 	init: function(i)
// 	{
// 		this.running = true;
// 		this.doIt();
// 		this.image();
// 	},
// 	doIt: function()
// 	{
// 		var $element = $(".destacado");
// 		var self = this;
// 		var element = $(".destacado_descripcion").eq(self.i);
// 		if (this.running == false){
// 			return false;
// 		}else{
// 			element.slideDown(self.fxIn).delay(self.delay).slideUp(self.fxOut, function(){
// 				if (self.i+1 == $(".destacado_descripcion").length)
// 					self.i = 0;
// 				else
// 					self.i = self.i+1;
// 				self.doIt();
// 				self.image();
// 			});
// 		}
// 	},
// 	kill: function()
// 	{
// 		this.running = false;
// 		var self = this;
// 		$(".destacado_descripcion").eq(self.i).stop().slideUp(250);
// 		$(".destacado_imagen").eq(self.i).stop();
// 	},
// 	image: function()
// 	{
// 		var self = this;
// 		var element = $(".destacado_imagen").eq(self.i);
// 		if (this.running == false){
// 			return false;
// 		}else{
// 			element.fadeIn(self.fxIn).delay(self.delay).fadeOut(self.fxOut);
// 		}
// 	}
// };
$(document).on("ready", function(){
	$(".destacado_imagen").hide();
	$(".destacado_descripcion").hide();
	destacados.init();
});