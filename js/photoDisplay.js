(function ($) {
	$.fn.vAlign = function() {
		return this.each(function(i){
		var ah = $(this).height();
		var ph = $(this).parent().height();
		var mh = Math.ceil((ph-ah) / 2);
		$(this).css('margin-top', mh);
		});
	};
})(jQuery);
var photoDisplay = {
	destroy: function(callback){
		$(".overlayer").fadeOut(350, function(){
			$(".overlayer").remove();
			if (typeof(callback) === "function"){
				callback.call();
			}	
		});

	},
	destroyThis: function(eq, callback){
		//$(".overlayer").eq(eq).remove();
		$(".photoDisplay-content").fadeOut(250, function(){
			$(this).remove();
			if (typeof(callback) === "function"){
				callback.call();
			}
		});
		
	},
	open: function(url, callback){
		var self = this;
		var docHeight = $('body').css('height');
		var div = "<div class='overlayer' style='height: "+docHeight+"; display: none;'></div>";
		$('body').append(div);
		$(".overlayer").fadeIn(450);
		$(".overlayer").mouseup(function(e){
			if ($(e.target).attr("class") !== "overlayer")
				return false;
			self.destroy(function(){
				var albumID = $.address.value().split("/");
				$.address.value("album/"+albumID[2]);
			});
		});
		$(document).on("keydown", function(e){
			if (e.which == 27){
				self.destroy(function(){
					var albumID = $.address.value().split("/");
					$.address.value("album/"+albumID[2]);
				});
			}
		});
		$.get(url, function(content){
			$(".overlayer").append("<div class='photoDisplay-content'>"+content+"</div>");
			photoDisplay.setStyle();
			if (callback != undefined)
				callback(content);
		});
	},
	openInPlace: function(url, callback){
		var self = this;
		$.get(url, function(content){
			$(".overlayer").append("<div style='display: none;' class='photoDisplay-content'>"+content+"</div>");
			photoDisplay.setStyle();
			$(".photoDisplay-content").fadeIn(250, function(){
				if (callback != undefined)
					callback(content);
			});
		});
	},
	setStyle: function(){
		var wWidth = $(window).width();
		var wHeight = $(window).height();

		$(".photoDisplay-content")
			.width(wWidth - Math.round(wWidth/8))
			.height(wHeight - Math.round(wHeight/8))
			.center()
			.append("<div class='photoDialog-close' rel='Pulsa ESC para salir'></div>");
		$(".photoDialog-close").click(function(e){
			photoDisplay.destroy(function(){
				var albumID = $.address.value().split("/");
				$.address.value("album/"+albumID[2]);
			});
		});
		//$(".photoDialog-close").qtip();
	},
	change: function(url, callback){
		var lastIndex = $(".photoDisplay-content").length;
		photoDisplay.destroyThis(lastIndex, function(){
			photoDisplay.openInPlace(url, callback);
		});
	}
};