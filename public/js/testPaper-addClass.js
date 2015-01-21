$(function(){
	$("a.loginWindow").click(function(e){
		 $.ajax({
		       url:'../album/testpaper/add',
		       async:false, 
		       success: function(html) {
		    	   var htm = "<div id='tooltips2'><div class='close'>X</div>"+html+"</div>";
		    	 
		          $('body').append(htm);
		          $("#tooltips2").fadeIn(1000);
		          $('.close').fadeIn(3000);
		       }
		    });
		
		return false;
	});

})

