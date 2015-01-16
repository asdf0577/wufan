$(function(){
	//提交答案页面中，选择班级切换名单
	$('select#classChange').change(function(){
		$('ol#nameSelect').empty();
		var cid = $('select').val();
		var tid = $("input[name='tid']").val();//这里是硬编码
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
					}else{
						$('ol#nameSelect').append("<li class ='studentSquare' title = '"+d.id+"'>"+d.name+"</li>");
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
	
	
	//人名框切换可选及不可选
	var flag = 1;
	$("#submitSwitch").click(function(){
		
		if(flag==1){
		$(this).text("开启修改");
		$(this).next().find("input[type='submit']").prop('name','update').prop('value','更新');
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
		flag = 0;
		}else{
			$(this).text("关闭修改");
			$(this).next().find("input[type='submit']").prop('name','submit').prop('value','保存');
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
			flag =1; 
		}
		
	}
	)
	
	//更新设置
	$("input[name='update']").click(function(){
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
	        	//设置已经提交人名不可选
	        	   $( "li.studentSquare.ui-selected" )
	        	   .removeClass("studentSquare ui-selectee ui-selected")
	        	   .addClass("studentSquareSubmit");
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
	           },
	           error:function(data){
	        	   alert(data);
	           }
	         });
		 return false;	
		
	})
	
	//保存设置
	$("input[name='submit']").click(function(){
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
				//设置已经提交人名不可选
				$( "li.studentSquare.ui-selected" )
				.removeClass("studentSquare ui-selectee ui-selected")
				.addClass("studentSquareSubmit");
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
			},
			error:function(data){
				alert(data);
			}
		});
		return false;	
		
	})
	
	
	
//以提交记录更新，还不能添加新追加的
//	  $('li.studentSquareSubmit').on('click', function () {
//		  var sid = $(this).attr("title");
//		  var tid = $("input[name='tid']").val();
//		  $.ajax({
//				type:"post",
//				url:"../getQuestionData",
//				data:{sid:sid,
//					  tid:tid,},
//				success:function(data){
//					$( "#selectable li" ).removeClass('ui-selected');
//					
//					$( "#select-result" ).empty();
//					$( "#select-hidden" ).empty();
//					var opts = $.parseJSON(data);
//					var i = 0;
//					$.each(opts,function(i,d){
//						$( "#selectable li" ).eq(d-1).addClass('ui-selected');
//						$( "#select-result" ).append( " 第" + ( d )+"题" ); 
//						$( "#select-hidden" ).append( ( d )+"," ); 
//					})
//				}
//			}) 
//      });
	  $('li.studentSquareSubmit.ui-selectee').on('click',function () {
		  alert("ok");
      });
	
})
