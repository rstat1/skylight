 function newPostOpen()
 {
	$.post("admin/newpost/", function(data){                                   
									makeWindow(data.html);
									setupPSClickHandlers();
                               },"json");
}

function setupPSClickHandlers()
{
	var contentArea = document.getElementById("content-area");
	$('#meta').click(function() {
		$('#meta').addClass('selected-window-command');
		$('#attach').removeClass('selected-window-command');
		$('#image').removeClass('selected-window-command');
		if ($('#metadata') == [])
		{
			$.get("admin/metadialog/", function(data){			
				contentArea.innerHTML = data.html;
				if (checkForPHSupport() == false)
				{
					$('label').css("display", "block");
				}
			}, "json");
		}
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