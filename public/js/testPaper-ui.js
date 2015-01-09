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
//		alert("1");
		
		//班级
//		var cid = $("select[name='cid']").find("option:selected").val();
		var sid = $( "li.studentSquare.ui-selected" ).attr("title");
		alert(sid);
		var inputQuestions = $( "span#select-hidden" ).text();
		var data = $('form').serialize();
		data += "&sid="+sid+"&inputQuestions="+inputQuestions;
		alert(data);
//		var that = this; // ajax 后 this 会变化
		
//		 $.ajax({
//	           type: "POST",
//	           url: "../addprocess",
//	           data: data,
//	           success:function(data){
//	        	  var opts = $.parseJSON(data);
////	        	  alert($(that).text());
////	        	  $(that).text(opts.edit_time);	
//	        	  $(that).parent().parent().find( "td.editTime" ).text(opts.edit_time);	
//				  $(that).parent().parent().find( "td.editCount" ).text(opts.edit_count);	
//				  $(that).parent().parent().css("border-bottom","2px groove red");
//	           },
//	           error:function(data){
//	        	   alert("error");
//	           }
//	         });
		
		 return false;
		
	})

})
