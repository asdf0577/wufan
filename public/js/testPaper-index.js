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
	$('body').on('click','.close',function(){
		$('#next').show();
		
	    $("#tooltips2").fadeOut(1000).remove();
	    $("#tooltips3").remove();
	    
	});
	$("div.item.fore2").hover(function(){
		$(this).find('.testPaperSubitem').slideDown(200);
	},function(){$(this).find('.testPaperSubitem').slideUp(200);})
	
	$("div.item.fore1").click(function(){
		$('.testPaperSubitem').slideToggle("400");
	}
	/*,function(){
		$('.testPaperSubitem').slideUp(200);
	}*/
	);
	
	
	/*$(".item fore2").hover(function(){
		$(this).find('.testPaperSubitem').slideDown(200);
	},function(){$(this).find('.testPaperSubitem').slideUown(200);});*/
	
	
}
)
