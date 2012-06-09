function setPos(){
	var leftPosition = $("#left").position().left;
	var rightPosition= $(window).width()-($("#right").position().left + $("#right").width());
	$("#logoTM, #logo").css('margin-left', leftPosition);
	$("#usuario").css('margin-right', rightPosition);
	$(".destacado_descripcion").css('right', rightPosition);
	if ($("#evento").is(":visible")){
		$(".evento-rightSide").width($("#evento").width() - 275);
		//$(".moduloRight").css("height", 684);
	}
}
var contentResize = function (){
	setPos();
};
var firstResize = function(){
	$("#hMenu").slideDown(250, function(){
		$("#logoTM").slideDown(500);
	});
	setPos();
};

$(document).on("ready", firstResize);
$(window).on("resize", contentResize);