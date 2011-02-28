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
                                content: "<p>Hello World! From skylight's first self-generated window", 
                                width: "450px",
                                height:"450px",
                                'position[]': ['0', '0', '142','142']
                               }, 
                               function(data){
                                    $("#window-container").append(data); 
                               },"html");
    var newJSElement = document.createElement('script');
    var idOfHead = document.getElementsByTagName('head')[0];
    newJSElement.setAttribute("type","text/javascript")
    newJSElement.setAttribute("src", "admin/draggableCode/");
    idOfHead.appendChild(newJSElement);
}