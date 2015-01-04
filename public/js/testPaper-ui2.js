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
		
		$('select#knowledgeSelect3rd').click(function(){
//			var fid = $('select#knowledgeSelect3rd').find("option:selected").val();
//			var text = $('select#knowledgeSelect3rd').find("option:selected").text();
//			$text2 =  $('select#knowledgeSelect3rd option:selected').text();//不适用于multiple
//			alert(text);
		})
		$('select#knowledgeSelect3rd').dblclick(function(){
			$(this).parents().prev().find('td.showType').empty();
			var fid = $('select#knowledgeSelect3rd').find("option:selected").val();
			var text = $('select#knowledgeSelect3rd').find("option:selected").text();
			$(this).parents().prev().find('td.showType').css("background-color","red").text(text);
		})
		
		
//		
//		var x = 10;
//		var y = 20;
//		$('select#knowledgeSelect3rd:option').mouseover(function(e){
//			alert("heiyo");
//			return false;
//			this.myTitle = this.title;
//			this.title = "";
//			var tooltip = "<div id='tooltips'>"+this.myTitle+"</div>";
//			$("body").append(tooltip);
//			$("#tooltips").css({"top":(e.pageY+y)+"px","left":(e.pageX+x)+"px"}).show("fast");
//		}).mouseout(function(e){
//			this.title = this.myTitle;
//			$("#tooltips").remove();
//			
//		}).mousemove(function(e){
//			$("#tooltips").css({
//				"top":(e.pageY+y)+"px",
//				"left":(e.pageX+x)+"px"
//			});
//		});
////		
		 $("ul.rating li a").click(function(){
		     var title = $(this).attr("title");
			 var cl = $(this).parent().attr("class");
			 $(this).parent().parent().removeClass().addClass("rating "+cl+"star");
			 $(this).blur();//去掉超链接的虚线框
			 return false;
		})
	
})