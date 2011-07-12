 function newPostOpen()
 {
	$.post("admin/newpost/", function(data){                                   
									makeWindow(data.html);
									setupPSClickHandlers();
                               },"json");
	$('textarea').click(function() {
									alert("hi");
								});
}
function hideOtherVisibleBlocks(whichBlockToKeep)
{
	if (whichBlockToKeep == "metadata")
	{
		$('#images').css("display", "none");
		$('#files').css("display", "none");
		$('#post-content').css("display", "none");		
	}
	if (whichBlockToKeep == "images")
	{
		$('#metadata').css("display", "none");
		$('#files').css("display", "none");
		$('#post-content').css("display", "none");		
	}
	if (whichBlockToKeep == "files")
	{
		$('#images').css("display", "none");
		$('#metadata').css("display", "none");
		$('#post-content').css("display", "none");		
	}
	if (whichBlockToKeep == "post-content")
	{
		$('#images').css("display", "none");
		$('#files').css("display", "none");
		$('#metadata').css("display", "none");		
	}
}
function getDialogSection(dialog, part)
{
	$.post("admin/dialog/", {dialog: dialog, section: part}, function(data){
			hideOtherVisibleBlocks(part);
			$('#content-area').append(data.html);
			if (checkForPHSupport() == false)
			{
				$('label').css("display", "block");
			}
		}, "json");
		//alert(windowName);
}
function makeTinyMCETextArea(textAreaToChange)
{
	var height = $("#window-inner-block").height() - 13 + "px";
	var width = $("#window-inner-block").width() - 202 + "px";
	tinymce.init({
				mode : "exact",
				elements : "postcontent",
				script_url : '/modules/news/js/editor/tiny_mce.js',
				theme : "advanced",				
				theme_advanced_toolbar_location : "top",
				theme_advanced_resizing : false,
				width: width,
				height: height,
				theme_advanced_statusbar_location : "bottom"
	});
	//tinyMCE.execCommand('mceAddControl', false, 'postcontent');
}
function contentClickHandler()
{
	var contentArea = document.getElementById("content-area");
	var windowId = $('.close').attr('id');
	var windowSelector = "." + windowId
	if (!$('#post-content').html())
	{			
		$(contentArea).append('<span id="post-content"></span>');
		$('#post-content').html('<textarea id="postcontent" name="postcontent" rows="19" cols="50" style="width:581px;height:492px;"></textarea>');
		makeTinyMCETextArea("#postcontent");	
		$(windowSelector).bind( "resize", function(event, ui) {
			var height = $("#window-inner-block").height() - 103;
			var width = $("#window-inner-block").width() - 202;
			$("#postcontent_ifr").css("height", height + "px");//, function(index) {return index - 30;}));				
			$("#postcontent_ifr").css("width", width + "px");//, function(index) {return index - 30;}));
		});			
		hideOtherVisibleBlocks("post-content");
	}
	else
	{
		hideOtherVisibleBlocks("post-content");
		$('#post-content').css("display", "block");
	}
}
function setupPSClickHandlers()
{	
	$('#meta').click(function() {
		$('#meta').addClass('selected-window-command');
		$('#attach').removeClass('selected-window-command');
		$('#image').removeClass('selected-window-command');
		$('#content').removeClass('selected-window-command');
		if (!$('#metadata').html()){getDialogSection('newPost','metadata');}
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
		if (!$('#images').html()){getDialogSection('newPost','images');}
		else
		{
			hideOtherVisibleBlocks("images");
			$('#images').css('display', 'block');
		}
	});
	$('#attach').click(function() {
		$('#meta').removeClass('selected-window-command');
		$('#image').removeClass('selected-window-command');
		$('#attach').addClass('selected-window-command');
		$('#content').removeClass('selected-window-command');
		if (!$('#attachments').html()){getDialogSection('newPost','attachments');}
		else
		{
			hideOtherVisibleBlocks("attachments");
			$('#attachments').css('display', 'block');
		}
	});
	$('#content').click(function() {
		$('#meta').removeClass('selected-window-command');
		$('#image').removeClass('selected-window-command');
		$('#attach').removeClass('selected-window-command');
		$('#content').addClass('selected-window-command');
		contentClickHandler();
	});	
}
function beginSavePost()
{
	var editor = tinyMCE.get('postcontent');
	var content = editor.getContent();
	var title = $('#title').val();
	var tags = $('#tags').val();
	var postdate = $('#date').val();
	$.post("admin/save_post/", {content: content, title: title, tags: tags, date: postdate}, function(data){
		$("#window-footer").html("Post saved and published sucessfully!");
	});
}