$(document).on("ready", function(){
	var prev;
	$("#navegacion li img").hover(function(){
		if(!$(this).hasClass("active")){
			prev = $(this).attr("src");
			var img = prev.substr(0, prev.length-4);
			$(this).attr("src", img+"h.png");
		}
	}, function(){
		if(!$(this).hasClass("active")){
			$(this).attr("src", prev);
		}
	});
});