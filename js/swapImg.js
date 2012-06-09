$(document).on("ready", function(){
	var prev;
	$("#navegacion li img").hover(function(){
		//var self = this;
		prev = $(this).attr("src");
		var img = prev.substr(0, prev.length-4);
		$(this).attr("src", img+"h.png");
	}, function(){
		$(this).attr("src", prev);
	});
});