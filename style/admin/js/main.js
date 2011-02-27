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
                                'position[]': ['42', '42', '42','42']
                               }, 
                               function(data){
                                    $("#window-container").append(data); 
                               },"html");
    $.post("admin/draggableCode/", function(data) {
        eval(data); 
    }, "script");
}