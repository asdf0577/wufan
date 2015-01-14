$(function(){
	$( "input[type=submit],button").button()
	
	$('select').change(function(){
		$('ol#nameSelect').empty();
		var cid = $('select').val();
		var tid = 39;
//		alert(cid);
	
		$.ajax({
			type:"post",
			url:"../getStudents",
			data:{cid:cid,
					tid:tid,},
			success:function(data){
				var opts = $.parseJSON(data);
				$.each(opts,function(i,d){
					if(d.submit == 1){
						$('ol#nameSelect').append("<li class ='studentSquareSubmit' title = '"+d.id+"'>"+d.name+"</li>");
//						$('ol#nameSelect').append(d.name);
					}else{
						$('ol#nameSelect').append("<li class ='studentSquare' title = '"+d.id+"'>"+d.name+"</li>");
//						$('ol#nameSelect').append(d.name);
					}		
					
				})
			}
		}) 
		
	});
	
	
	
	
	
	
	
	
	
	
	
	
	
	
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
		filter:"li.studentSquare",
		selecting: function(event, ui){
			if( $("#nameSelect .ui-selected").length > 1){
				$(ui.selecting).removeClass("ui-selecting");
			}
		}
	});
	//切换
	var flag = 1
	$("#submitSwitch").click(function(){
		if(flag ==1){
		$(this).text("开启修改");
		$("li.studentSquare").removeClass("ui-selected");
		$("#nameSelect").selectable( "destroy" );
		$("#nameSelect").selectable(
				{
			filter:"li.studentSquareSubmit",
			selecting: function(event, ui){
				if( $("#nameSelect .ui-selected").length > 1){
					$(ui.selecting).removeClass("ui-selecting");
				}
				}
				}
		);
		$("li.studentSquareSubmit").css("color","red");
//		$("li.studentSquareSubmit").removeClass("studentSquareSubmit").addClass("studentSquareSubmitSwitch");
//		$("li.studentSquare").removeClass("studentSquare").addClass("studentSquareSwitch");
		flag = 0;
		}else{
			$(this).text("关闭修改");
			$("li.studentSquareSubmit").removeClass("ui-selected");
			$("#nameSelect").selectable( "destroy" );
			$("#nameSelect").selectable(
					{
				filter:"li.studentSquare",
				selecting: function(event, ui){
					if( $("#nameSelect .ui-selected").length > 1){
						$(ui.selecting).removeClass("ui-selecting");
					}
					}
					}
			);
			$("li.studentSquareSubmit").css("color","white");
//			$("li.studentSquareSubmitSwitch").removeClass("studentSquareSubmitSwitch").addClass("studentSquareSubmit");
//			$("li.studentSquareSwitch").removeClass("studentSquareSwitch").addClass("studentSquare");
			flag =1; 
		}
		
	}
	)
	
	//已经提交人名框不可选
//	$("#studentSquareSubmit").toggleClass("selectable").removeClass("ui-selected");
	$('form#InputQuestionForm').submit(function(){
		var sid = $( "li.studentSquare.ui-selected" ).attr("title");
		var inputQuestions = $( "span#select-hidden" ).text();
		var data = $('form').serialize();
		data += "&sid="+sid+"&inputQuestions="+inputQuestions;
		alert(data);
		
		 $.ajax({
	           type: "POST",
	           url: "../addProcess",
	           data: data,
	           success:function(data){
	        	   alert(data);
	           },
	           error:function(data){
	        	   alert(data);
	           }
	         });
//		 $( "li.studentSquare.ui-selected" ).toggleClass("selectable")
//		 .removeClass("studentSquare").removeClass("ui-selected").removeClass("ui-selectee")
//		 .addClass("studentSquareSubmit");
		 return false;	
		
	})

})
