 function newPostOpen()
 {
	$.post("admin/newpost/", function(data){                                   
									makeWindow(data.html);
									setupPSClickHandlers();
                               },"json");
}
function hideOtherVisibleBlocks($whichBlockToKeep)
{	
	if ($whichBlockToKeep == "metadata")
	{
		$('#images').css("display", "none");
		$('#files').css("display", "none");
		$('#post-content').css("display", "none");		
	}
	if ($whichBlockToKeep == "images")
	{
		$('#metadata').css("display", "none");
		$('#files').css("display", "none");
		$('#post-content').css("display", "none");		
	}
	if ($whichBlockToKeep == "files")
	{
		$('#images').css("display", "none");
		$('#metadata').css("display", "none");
		$('#post-content').css("display", "none");		
	}
	if ($whichBlockToKeep == "post-content")
	{
		$('#images').css("display", "none");
		$('#files').css("display", "none");
		$('#metadata').css("display", "none");		
	}
}
function setupPSClickHandlers()
{
	var contentArea = document.getElementById("content-area");
	$('#meta').click(function() {
		$('#meta').addClass('selected-window-command');
		$('#attach').removeClass('selected-window-command');
		$('#image').removeClass('selected-window-command');
		$('#content').removeClass('selected-window-command');
		if (!$('#metadata').html())
		{
			$.post("admin/dialog/", {dialog: "newpost", section: "metadata"}, function(data){
				hideOtherVisibleBlocks("metadata");
				$('#content-area').append(data.html);
				if (checkForPHSupport() == false)
				{
					$('label').css("display", "block");
				}
			}, "json");
		}
		else
		{
			hideOtherVisibleBlocks("metadata");
			$('#metadata').css('display', 'block');
		}
	});
	$('#image').click(function() {
		$('#meta').removeClass('selected-window-command');
		$('#image').addClass('selected-window-command');
		$('#content').removeClass('selected-window-command');
		$('#attach').removeClass('selected-window-command');		
	});
	$('#attach').click(function() {
		$('#meta').removeClass('selected-window-command');
		$('#image').removeClass('selected-window-command');
		$('#attach').addClass('selected-window-command');
		$('#content').removeClass('selected-window-command');			
	});
	$('#content').click(function() {
		$('#meta').removeClass('selected-window-command');
		$('#image').removeClass('selected-window-command');
		$('#attach').removeClass('selected-window-command');
		$('#content').addClass('selected-window-command');		
	});
}