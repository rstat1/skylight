 function newPostOpen()
 {
	$.post("admin/newpost/", function(data){                                   
									$("#window-container").append(data.html);
									$(".window").resizable({containment: "#window-container"});
									$(".window").draggable({
												 handles: "n, e, s, w", 
												 stack: '#window-container',
												 containment: "#window-container",
												 scroll: false
												 });
									setupPSClickHandlers();
                               },"json");
}

function setupPSClickHandlers()
{
	$('#meta').click(function() {
		$('#meta').addClass('selected-window-command');
		$('#attach').removeClass('selected-window-command');
		$('#image').removeClass('selected-window-command');
		$.get("admin/metadialog/", function(data){			
			document.getElementById("content-area").innerHTML = data.html;
			if (checkForPHSupport() == false)
			{
				$('label').css("display", "block");
			}
		}, "json");
	});
	$('#image').click(function() {
		$('#meta').removeClass('selected-window-command');
		$('#image').addClass('selected-window-command');
		$('#attach').removeClass('selected-window-command');		
	});
	$('#attach').click(function() {
		$('#meta').removeClass('selected-window-command');
		$('#image').removeClass('selected-window-command');
		$('#attach').addClass('selected-window-command');		
	});
}