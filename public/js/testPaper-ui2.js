$(function(){
		
	
	

//    $.fn.clickToggle = function(func1, func2) {
//        var funcs = [func1, func2];
//        this.data('toggleclicked', 0);
//        this.click(function() {
//            var data = $(this).data();
//            var tc = data.toggleclicked;
//            $.proxy(funcs[tc], this)();
//            data.toggleclicked = (tc + 1) % 2;
//        });
//        return this;
//    };
//
//	
//	$("tr[class^= questionTr]").clickToggle(function() {   
//	    $(this).next().slideDown(2000);
//	    $.ajax({
//		       url:'../../grammar/add',
//		       async:false, 
//		       success: function(html) {
//		    	 var htm = "<div class= 'afterTrDiv'>"+html+"</div";
//		    	 
//		          $('.afterTrTd').append(htm);
//		       }
//		    });
//	},
//	function() {
//		$(this).next().hide();
//		$(".afterTrDiv").remove();
//	});
	
	
	// 单击Tr 弹出 下拉列表
//	$("tr[class^= questionTr]").click(function(){
//		
//		$(this).next().toggle(300);
//		})
	$("td.showType").click(function(){
		
		$(this).parent().next().toggle(300);
		})
		
		
		$('select.knowledgeSelect').click(function(){
			$('select#knowledgeSelect2nd').empty();
			$('select#knowledgeSelect3rd').empty();
			var fid = $('select.knowledgeSelect').find("option:selected").val();
			$.ajax({
				type:"post",
				url:"../getKnowledgeType",
				data:{fid:fid},
				success:function(data){
					var opts = $.parseJSON(data);
					$.each(opts,function(i,d){
						$('select#knowledgeSelect2nd').append('<option value ="'+d.id+'">'+d.name+d.cn_name+'</option>');
					})
				}
			}) 
		});	
//	
		$('select#knowledgeSelect2nd').click(function(){
			$('select#knowledgeSelect3rd').empty();
			var fid = $('select#knowledgeSelect2nd').find("option:selected").val();
			$.ajax({
				type:"post",
				url:"../getKnowledgeType",
				data:{fid:fid},
				success:function(data){
					var opts = $.parseJSON(data);
					$.each(opts,function(i,d){
						$('select#knowledgeSelect3rd').append('<option value ="'+d.id+'">'+d.name+d.cn_name+'</option>');
					})
				}
			}) 
		});	
		
		
		$('select#knowledgeSelect3rd').dblclick(function(){
//			$(this).parents().prev().find('td.showType').empty();
			var fid = $('select#knowledgeSelect3rd').find("option:selected").val();
			var text = $('select#knowledgeSelect3rd').find("option:selected").text();
//			var html = "<input type='hidden' name='knowledge_id' value='"+fid+"'>"; 
			$(this).parents().prev().find('td.showType').css("background-color","rgb(161, 159, 159)").text(text);
			$(this).parents().prev().find( "input[name='knowledge_id']" ).val(fid);
		
		})
		
		
		 $("ul.rating li a").click(function(){
		     var title = $(this).attr("title");
			 var cl = $(this).parent().attr("class");
			 $(this).parent().parent().removeAttr('class').addClass("rating "+cl);
			 $(this).parent().parent().next().val(title);
			 $(this).blur();//去掉超链接的虚线框
			 return false;
		})
		
		
		$('button#questionSubmit').click(function(){
			var questionNum = $(this).parent().parent().find( "input[name='questionNum2']" ).val();
			var id = $(this).parent().parent().find( "input[name='id']" ).val();
			var kId = $(this).parent().parent().find( "input[name='knowledge_id']" ).val();
			var grade = $(this).parent().parent().find( "input[name='grade']" ).val();
			data = "id="+id+"&questionNum2="+questionNum+"&knowledge_id="+kId+"&grade="+grade;
			var that = this; // ajax 后 this 会变化
			
			 $.ajax({
		           type: "POST",
		           url: "../editprocess",
		           data: data,
		           success:function(data){
		        	  var opts = $.parseJSON(data);
//		        	  alert(opts);
//		        	  alert(data);
		        	  $(that).parent().parent().find( "td.editTime" ).text(opts.edit_time);	
					  $(that).parent().parent().find( "td.editCount" ).text(opts.edit_count);	
					  $(that).parent().parent().css("border-bottom","2px groove red");
		           },
		           error:function(data){
		        	   alert("error");
		           }
		         });
			
			 return false;
			
		})
		
	
})