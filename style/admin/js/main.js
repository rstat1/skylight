$(document).ready(function() {
	$("#write").bind("click", function() {
		getNewPostWindow()
	});
	
});

function loadMainDashPage()
{
	alert("This where a call to skylight's AJAX APIs would be made");
}
function getNewPostWindow()
{
    $.post("admin/newWindow/", {title: "New Post", 
                                content: '<p style="color:black;">Hello World!</p>', 
                                width: "250px",
                                height:"250px",
                                'position[]': ['0', '0', '0','0']
                               }, 
                               function(data){                                   
									$("#window-container").append(data.html);  									
									$(".window").resizable({containment: "#window-container"}).draggable({handles: "n, e, s, w", stack: "#window-container", containment: "#window-container", scroll: false});
									//$(".window" + data.windowID).css("margin", "0");									
                               },"json");
							   
}