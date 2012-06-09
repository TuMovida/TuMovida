var identips = {
	position: 
	{
		at: 'middle right',
		my: 'middle left'
	},
	style: 'ui-tooltip-shadow ui-tooltip-light',
	show: {
		effect: function(){
			// $(this).animate({
			// 	left: 50,
			// 	opacity: 1
			// }, 250);
			$(this).fadeIn(500);
		}
	},
	hide: {
		delay: 100,
		event: 'unfocus mouseleave',
		effect: function(){
			// $(this).animate({
			// 	width: 50,
			// 	opacity: 0
			// }, 250);
			$(this).fadeOut(500);
		},
		fixed: true
	}
};
var tipsAbajo = {
	position: 
	{
		at: "bottom center",
		my: "top center",
		adjust: 
		{
			y: -4
		}
	},
	style: 'ui-tooltip-shadow ui-tooltip-light'
};
var tipsSettings = {
	position:
	{
		my: "middle left",
		at: "middle right"
	},
	style: 'ui-tooltip-shadow ui-tooltip-light',
	show: 
	{
		event: 'focus mouseover',
		effect: function(offset){
			$(this).animate({height: 'toggle', opacity: 1}, 250);
		}
	},
	hide: 
	{
		event: 'blur mouseleave',
		effect: function(offset){
			$(this).animate({height: 'toggle', opacity: 0}, 250);
		}
	}	
};
$(document).on("ready", function(){
	$(document).on("mouseover", ".dialog-content .formulario", function(){
		$("form input[title], form select[title]").each(function(index){
			$(this).qtip( $.extend({}, tipsSettings, {
				adjust: { screen: true}
			}));
		});
	});
	$("a[title]").qtip({
		position: 
			{
			at: "bottom center",
			my: "top center",
			adjust: 
				{
					y: -4
				}
			},
		style: 'ui-tooltip-shadow ui-tooltip-light'
	});
	$("#navegacion li").qtip({
		position: {
			at: 'right middle',
			my: 'left middle',
			adjust: {
				y: -4
			}
		},
		style: 'ui-tooltip-shadow ui-tooltip-red',
		show: {
			ready: true
		},
		hide: {
			delay: 100,
			effect: function(){
				$(this).fadeOut();
			}
		}
	});
	$(".lqsv_entrada").qtip({
		content: 
		{
			text: function(){
				var img = "<img src='"+$(this).children().children().attr("src")+"' style='border: solid grey 1px; max-height: 150px; max-width: 255px;'/>";
				return img;
			}
		},
		position: {
			target: 'mouse',
			adjust: {
				y: 10,
				x: 10
			}
		},
		style: 'ui-tooltip-shadow ui-tooltip-light'
	});
});
/*
(function( $ ) {
	$.fn.dropMenu = function (contenido, callback){
		if (arguments[2] != undefined && arguments[2] != '')
			var classes = arguments[2];
		else var classes = 'ui-tooltip-light';
		this.on("mouseover", function(e){
			e.preventDefault();
			var self = $(this);
			self.qtip({
				content: {
					text: contenido
				},
				show: {
					event: e.type,
					ready: true,
					effect: function(){
						$(this).animate({height: 'toggle', opacity: 1}, 250);
					}
				},
				hide: {
					delay: 100,
					event: 'unfocus mouseleave',
					fixed: true
				},
				style: {
					classes: classes
					//tip: false
				},
				position: {
					at: "bottom center",
					my: "top center"
				},
				events: {
					render: function(event, api){
						$(this).click(function(e){
							e.preventDefault();
							callback(e.delegateTarget);
						});
					}
				}
			});
		});
		return this;
	}
})(jQuery);*/
(function( $ ) {
	$.fn.dropMenu = function(callback){
		var $menu = this;
		//var hoverItem = "$('."+this.attr("rel")+"')";
		var hoverItem = $("."+this.attr("rel"));
		$(document).on("mouseover", function(){
			hoverItem.on("mouseover", function(e){
				e.preventDefault();
				$menu.slideDown();
				return false;
			});
			$menu.on("mouseover", function(){
				hoverItem.focus();
				return false;
			});
			$menu.slideUp();
		});
		return this;
	}
})(jQuery);
$(document).on("ready", function(){
	$('.day-number').each( function(){
		$(this).qtip($.extend({}, identips, {
			content: {
				text: 'Cargando...',
				ajax: {
					url: "ajax/listaEventos.php?dia=" + $(this).attr('rel')
				},
				title: {
					text: function(){
						var text = 'Eventos para el ' + $(this).attr('rel');
						var date = new Date();
						var month = ((date.getMonth()+1) < 10) ? "0" + (date.getMonth()+1) : (date.getMonth()+1);
						var day = ((date.getDate()+1) < 10) ? "0" + (date.getDate()) : (date.getDate());
						var d = date.getFullYear() + "-" + month + "-" + day;
						if ($(this).attr('rel') == d)
							text  = 'Eventos para hoy';
						return text;
						},
					button: true
				}
			}
		}))
	}).click(function(event) { 
		event.preventDefault(); 
	});
});