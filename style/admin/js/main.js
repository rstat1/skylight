$(document).ready(function() {
	$('.home').mouseup(function() {
		loadMainDashPage();	
	});
	$(".server-item").mousedown(function(){
		showServerMenu()
	});
	$("#settings").bind("mousedown", function() {
		showSettingsMenu()
	});
	
});

function loadMainDashPage()
{
	alert("This where a call to skylight's AJAX APIs would be made");
}
function showServerMenu()
{
	$('.server-item').toggleClass('active');
	if ($('.server-menu').css('visibility') == "visible"){$('.server-menu').css('visibility', 'hidden');}
	else {$('.server-menu').css('visibility', 'visible');}
	if ($('.server-menu').css('display') == "block"){$('.server-menu').css('display', 'none');}
	else {$('.server-menu').css('display', 'block');}	
}