 function newPostOpen()
 {
	$.post("admin/newpost/", {title: "New Post", 
								content: '<p style="color:black;">Hello World!</p>', 
                                width: "250px",
                                height:"250px",
                                'position[]': ['0', '0', '0','0']
                               }, 
                               function(data){                                   
									$("#window-container").append(data.html);  									
									$(".window").resizable({containment: "#window-container"});
									$(".window").draggable({
												 handles: "n, e, s, w", 
												 stack: '#window-container', 
												 containment: "#window-container", 
												 scroll: false
												 });
									//$(".window" + data.windowID).css("margin", "0");									
                               },"json");
 }