$(function(){
		
	//选题框可选
	$( "#selectable" ).selectable({
	      stop: function() {
	    	  
	        var result = $( "#select-result" ).empty();
	        var  hidden= $( "#select-hidden" ).empty();
	        $( ".ui-selected", this ).each(function() {
	          var index = $( "#selectable li" ).index( this );
	          result.append( " 第" + ( index + 1 )+"题" ); 
	          hidden.append( ( index + 1 )+"," ); 
	        });
	      }
	    });
	//人名框可选
	$("#nameSelect").selectable({
        selecting: function(event, ui){
            if( $("#nameSelect .ui-selected").length > 1){
                  $(ui.selecting).removeClass("ui-selecting");
            }
        }
    });
	
	//提交答案
	$('form#InputQuestionForm').submit(function(){
		var sid = $( "li.studentSquare.ui-selected" ).attr("title");
		var inputQuestions = $( "span#select-hidden" ).text();
		var data = $('form').serialize();
		data += "&sid="+sid+"&inputQuestions="+inputQuestions;
//		alert(data);
		
		 $.ajax({
	           type: "POST",
	           url: "../addProcess",
	           data: data,
	           success:function(data){
	        	   alert(data);
	           },
	           error:function(data){
	        	   alert("error");
	           }
	         });
		
		 return false;	
		
	})

})
