$(document).ready(function(){$("#tags").bind("click", show_tags);});
$(document).ready(function(){$("#user").bind("click", show_usermenu);});
$(document).ready(function(){$("#login").bind("click", getThemeLogin);});
$(document).ready(function(){$(".article").bind("click", function() {
	var id = $(this).attr("id");
	getArticleContent(id);
});});
function getThemeLogin()
{
	$("#loading").css('visibility', 'visible');
	$("#loading").css('display', 'inherit');
	$.get("ajax/displayLogin/", function(data) {render_login(data);});
}
function getTagContent(tag)
{	
	$("#loading").css('visibility', 'visible');
	$("#loading").css('display', 'inherit');
	$.get("ajax/tag/" + tag, function(data) {render_tag(data);});		
}
function getArticleContent(id)
{
	$("#loading").css('visibility', 'visible');
	$("#loading").css('display', 'inherit');
	$.get("ajax/article/" + id, function(data) {render_article(data);});	
}
function getArticleComments(id)
{
	alert(id);
}
function render_login(data)
{	
	$("#loading").css('visibility', 'hidden');
	$("#loading").css('display', 'none');
	$('.news').fadeOut('slow', function() {
		$(this).html(data);		
		$(this).fadeIn('slow');		
	});
}
function render_article(data)
{
	$("#loading").css('visibility', 'hidden');
	$("#loading").css('display', 'none');
	$('.news').fadeOut('slow', function() {
		$(this).html(data);		
		$(this).fadeIn('slow');
		$(".show-comments").bind("click", function() {var id = $(this).attr("id");getArticleComments(id);});
	});
}
function render_tag(data)
{		
	$("#loading").css('visibility', 'hidden');
	$("#loading").css('display', 'none');
	$('.news').fadeOut('slow', function() {		
		$(".article").unbind("click", function() {});
		$(this).html(data);		
		$(this).fadeIn('slow'); 
		$(".article").bind("click", function() {var id = $(this).attr("id");getArticleContent(id);});
	});		
}
function show_tags()
{
	var category = document.getElementById("category");
	var usermenu = document.getElementById("usermenu");
	if (category.style.visibility == "visible")
	{
		$('#category').fadeOut('fast', function() { category.style.visibility = "hidden"; });		
	}	
	else 
	{
		$('#usermenu').fadeOut('fast', function() 
		{ 
			usermenu.style.visibility = "hidden"; 
			category.style.visibility = "visible";
			$('#category').fadeIn('fast', function() { });
		});
	}	
}
function show_usermenu()
{
	var usermenu = document.getElementById("usermenu");
	var category = document.getElementById("category");		
	if (usermenu.style.visibility == "visible")
	{
		$('#usermenu').fadeOut('fast', function() 
		{
			usermenu.style.visibility = "hidden";
		});		
	}	
	else 
	{
		$('#category').fadeOut('fast', function()
		{ 
			category.style.visibility = "hidden"; 
			usermenu.style.visibility = "visible";
			$('#usermenu').fadeIn('fast', function() { });
		});		
	}
}