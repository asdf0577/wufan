$(function(){
	/*创建试卷页面 选择题型用
	1.填写第一部分表单内容按next弹出第二部分表单
	2.第二部分表单中的选择试卷科目（英语、数学...）之后ajax弹出题型
	3.选择题型到备选框，在备选框内可以上下移动调整题型在试卷中的排位
	4.计算题型总数
	5.提交表单 
	 *
	 */	
	
	
	
	//填写第一部分表单内容按next弹出第二部分表单
	$('#next').click(function(){
		$('#selectbox').fadeIn(1000);
		$('#next').css('display','none')
	})
	
	

	//考试类别列表下拉 ajax
	$('#testPaperType').change(function(){
		$('#selectType1').empty();
		var fid = $('#testPaperType').val();
		
		$.ajax({
			type:"post",
			url:"../album/testpaper/getTypes",
			data:{fid:fid},
			success:function(data){
				var opts = $.parseJSON(data);
				$.each(opts,function(i,d){
					$('#selectType1').append('<option value ="'+d.id+'">'+d.name+'</option>');
				})
			}
		}) 
		
	});
	
	//试题类型框可以移动调整
	$('#selectBoxTiny2,#testContent').sortable();//selectbox排列任意组合
	//删除试题类型
	$('#selectBoxTiny2').on('click','.delete',function(){//点击selectbox内的delete元素，删除当前的div
		$(this).parent().remove();
		return false;
	});
	
	//计算所填的试题总数
	function setTotal(){
			var total = 0;
			var num;
			$('.draglistbox>input').each(function(){
				if(!$(this).val()){
					num = 0;
				}else{
				num = parseInt($(this).val());}
				total += num;
				
			})
			$('#total').text(total);
			
	}
	//初始化总数值 0
	setTotal();
	
	//获取试题类型对象变化后的总数变化值
	$("#selectBoxTiny2").on('change','.draglist',function(){
		setTotal();
	  });
	  
	
		//双击添加到备选框 
		$('#selectType1').dblclick(function(){ //绑定双击事件
			//获取全部的选项,删除并追加给对方
			$val = $('#selectType1 option:selected').val();
			$text =  $('#selectType1 option:selected').text();
			var input = "<div class='draglistbox'id='listbox'>" +
						"<span>"+$text+"</span>" +
						"<input class='draglist' type='text'name = '"+$val+"' id="+$val+" >" +
								"<div class='delete'>x</div></div>";
			$('#selectBoxTiny2').append(input);
		});
	
		//清空
		$('#add_all').click(function() {
			//获取全部的选项,删除并追加给对方
			$('.draglistbox').remove();
			$('#total').text(0);
			$('#selectType1').prop("disabled", false);
		});
		
	

	
		
	
	//提交
	$('form#TestPaper').submit(function(){
		var type = "",name="";				//Type（name：startNum-endNum...）,题型名称
		var num =0,startNum =0 ,endNum =0 ;	//input框内数值，题号起始数,题号终止数
		$('.draglistbox>input').each(function(index,value){
				//计算endNum
				$('.draglistbox>input').each(function(index2){
					num = parseInt($(this).val());
					endNum += num;
					num = 0;
					if(index2 == index){	
						return false;
					}
				
				});
				//計算startNum
				$('.draglistbox>input').each(function(index3){
					if(index == 0){ 
						startNum=0;
					}
					else{ 	
					num = parseInt($(this).val());
					startNum +=num;
					if(index3 == index-1){	
						return false;
					}
					}
				});
				
				startNum = startNum+1;
				
				name =$(this).attr('name'); //获取这个input的name
				type += name+':'+startNum+'-'+endNum+',';
				endNum = 0;
				startNum = 0;
				
		})
		$('#selectType1,.draglist').attr("disabled","true");//让部分表单元素无法提交
		$('.draglist').attr("disabled","true");//让部分表单元素无法提交
		var data = $('form').serialize();
		var total= $('span#total').text();
		var uid = $('body').find("input[name = 'uid']").val();
//		console.log(uid);
//		return false;
		data += "&QuestionTypeInput="+type+"&questionAmount="+total+"&uid="+uid;
		 $.ajax({
	           type: "POST",
	           url: "../album/testpaper/add",
	           data: data,
	           success: function(res) {
//	        	   console.log(res);
	        	        if (! res.url) { //如果未返回目标地址
	        	            if (location.href!='/user/login') { //判断当前页面是不是登录页面（如果你的登录可能是弹窗方式，也可能是单独页面的时候才需要）
	        	                location.reload();
	        	                return true;
	        	            } else {
	        	                res.url = '../album/testpaper/index';//设置一个默认地址
	        	            }
	        	        }
	        	        location = res.url;
	        	    
	        	}
	         });
		 return false;
	})	


})

