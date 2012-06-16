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
	i: 0,
	fxIn: 	300,
	delay:	2500,
	fxOut: 	300,
	running: true,
	init: function(i)
	{
		this.running = true;
		this.doIt();
		this.image();
	},
	doIt: function()
	{
		var $element = $(".destacado");
		var self = this;
		var element = $(".destacado_descripcion").eq(self.i);
		if (this.running == false){
			return false;
		}else{
			element.slideDown(self.fxIn).delay(self.delay).slideUp(self.fxOut, function(){
				if (self.i+1 == $(".destacado_descripcion").length)
					self.i = 0;
				else
					self.i = self.i+1;
				self.doIt();
				self.image();
			});
		}
	},
	kill: function()
	{
		this.running = false;
		var self = this;
		$(".destacado_descripcion").eq(self.i).stop().slideUp(250);
		$(".destacado_imagen").eq(self.i).stop();
	},
	image: function()
	{
		var self = this;
		var element = $(".destacado_imagen").eq(self.i);
		if (this.running == false){
			return false;
		}else{
			element.fadeIn(self.fxIn).delay(self.delay).fadeOut(self.fxOut);
		}
	}
};
$(document).on("ready", function(){
	$(".destacado_imagen").hide();
	$(".destacado_descripcion").hide();
	destacados.init();
});