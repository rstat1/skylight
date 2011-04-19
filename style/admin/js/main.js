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
	var newJSElement = document.createElement('script');
	var idOfHead = document.getElementsByTagName('head')[0];
	newJSElement.setAttribute("type","text/javascript")
	newJSElement.setAttribute("src", "admin/draggableCode/");
	newJSElement.setAttribute("id", Math.floor(Math.random()*100));
	idOfHead.appendChild(newJSElement);
    $.post("admin/newWindow/", {title: "New Post", 
                                content: '<p style="color:black;">Hello World!</p>', 
                                width: "250px",
                                height:"250px",
                                'position[]': ['0', '0', '5','5']
                               }, 
                               function(data){                                   
									$("#window-container").append(data);  
									idOfHead.appendChild(newJSElement);
                               },"html");   
}