jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px");
    this.css("left", (($(window).width() - this.outerWidth()) / 2) +  $(window).scrollLeft() + "px");
    return this;
};
var dialog = {
	init: function(){
		var self = this;
		this.create();
		$(".overlayer").mouseup(function(e){
			if ($(e.target).attr("class") !== "overlayer")
				return false;
			self.destroy();
		});
		$(document).on("keydown", function(e){
			if (e.which == 27){
				self.destroy();
			}
		});
	},
	create: function(){
		var docHeight = $('body').css('height');
		var div = "<div class='overlayer' style='height: "+docHeight+"; display: none;'></div>";
		$('body').append(div);
		$(".overlayer").fadeIn(350);
	},
	destroy: function(callback){
		$(".overlayer").fadeOut(350, function(){
			$(".overlayer").remove();
			if (typeof(callback) === "function"){
				callback.call();
			}
			$("html").css("overflow", "auto");
		});
	},
	window: function(html){
		$(".overlayer").append("<div class='dialog-content'>"+html+"</div>");
		$(".dialog-content").center();
	},
	open: function(url, callback){
		var self = this;
		self.init();
		$.get(url, function(content){
			self.window(content);
			if (callback != undefined)
				callback(content);
		});
	},
	show: function(html){
		var self = this;
		self.init();
		self.window(html);
	}
};