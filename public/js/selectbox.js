$(function(){
		//selectbox排列任意组合
	$('#selectBoxTiny2').sortable();
		//点击selectbox内的delete元素，删除当前的div
	$('#selectBoxTiny2').on('click','.delete',function(){
		$(this).parent().remove();
		return false;
	});
	
	
	//获取总数
	function setTotal(){
			var total = 0;
			var num;
			$('.draglistbox>input').each(function(){
				num = parseInt($(this).val());
				total += num;
				
			})
			$('#total').text(total);
		
	}
	//初始化总数值 0
	setTotal();
	
	//获取单项对象变化后的总数变化值
	$("#selectBoxTiny2").on('change','.draglist',function(){
		setTotal();
	  });
	  
	
		//移到左边
		$('#remove').click(function() {
			$('#selectType2 option:selected').remove();
		});
		//全部移到右边
		$('#add_all').click(function() {
			//获取全部的选项,删除并追加给对方
			$('.draglistbox').remove()
			$('#total').text(0);
		});
		//全部移到左边
		$('#remove_all').click(function() {
			$('#selectType2 option').remove();
			
		});
		//双击选项
		$('#selectType1').dblclick(function(){ //绑定双击事件
			//获取全部的选项,删除并追加给对方
			$val = $('#selectType1 option:selected').val();
			$text =  $('#selectType1 option:selected').text();
			var input = "<div class='draglistbox'id='listbox'><span>"+$text+"</span><input class='draglist' type='text'name = '"+$val+"[]' id="+$val+" ><span class='delete'>x</span></div>"
			$('#selectBoxTiny2').append(input);
		});

		//双击选项
		$('#selectType2').dblclick(function(){
		   $("option:selected",this).remove();
		});
		
		$('#next').click(function(){
			$('#selectbox').fadeIn(1000);
			$('#next').css('display','none')
		})

})

