$(document).ready(function() {
	
});
function makeWindow(html)
{
	$("#window-container").append(html);
	$(".window").resizable({containment: "#window-container"});
	$(".window").draggable({
		handles: "n, e, s, w", 
		stack: '#window-container',
		containment: "#window-container",
		scroll: false
	});
	$(".close").click(function() {
		var windowName = $('.close').attr('id');
		var windowSelector = "div." + windowName;
		$(windowSelector).remove()
	});
}