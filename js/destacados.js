var destacados = {
	i: 0,
	fxIn: 	250,
	delay:	2500,
	fxOut: 	250,
	running: true,
	init: function(i)
	{
		this.running = true;
		this.doIt();
		this.image();
	},
	doIt: function()
	{
		var self = this;
		var element = $(".destacado_descripcion").eq(self.i);
		if (this.running == false){
			return false;
		}else{
			element.slideDown(self.fxIn).delay(self.delay).slideUp(self.fxOut, function(){
				if (self.i>2)
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