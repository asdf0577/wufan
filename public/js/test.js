
$(function(){
	$('a.modalShow').click(
    function() {
        
        var htm = "<div class='modal'>" +
        		"<span class='close'>X</span><p>wo le ge qu</p></div>";
        $('body').append(htm);
        $('.modal').fadeIn(1000);
        $('.close').fadeIn(3000);
    })
	$('body').on('click', 'span.close', function(){
   	 $ ('div.modal').fadeOut();    
    });


;})



$(function(){
	$("#button").click(function(){
		var htm = '<div class="box"><div class="close_box">X</div><h2>Box title</h2><p>Merol muspi rolod tis tema...</p></div>';
	$('body').append(htm);	
	})
	$('body').on('click','.close_box',function(){
	    $('.box').remove();
	});
	
})

