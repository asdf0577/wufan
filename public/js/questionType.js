$(function(){
	$('form#QuestionType').submit(function(){
		var data = $('form').serialize();
		 $.ajax({
	           type: "POST",
	           url: "../testpaper/createType",
	           data: data,
	           success: function(data){
	               alert(data);//only for testing purposes
	           },
	          /* error: function(resp) {
	                alert(resp);
	            },*/
	         });
		return false;
	})
	
	$('#testPaperType').change(function(){
		
		alert('333');
	});
})


