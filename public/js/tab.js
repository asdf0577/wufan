/*$(function(){
		var $div_li =$("div.tab_menu ul li");
		$div_li.click(function(){
			$(this).addclass("selected")
				   .siblings().removeClass("selected");
		var index =	$div_li.index(this);
		$("div.tab_box>div")
				   .eq(index).show()
				   .siblings().hide();
		}).hover(function(){
			$(this).addClass("hover");
			},function(){
			$(this).removeClass("hover");
		})
	
})*/

/*Tab ѡ� ��ǩ*/
$(function(){
	    var $div_li =$("div.tab_menu ul li");
	    $div_li.hover(function(){
			$(this).addClass("selected")            //��ǰ<li>Ԫ�ظ���
				   .siblings().removeClass("selected");  //ȥ������ͬ��<li>Ԫ�صĸ���
            var index =  $div_li.index(this);  // ��ȡ��ǰ�����<li>Ԫ�� �� ȫ��liԪ���е�������
			$("div.tab_box > div")   	//ѡȡ�ӽڵ㡣��ѡȡ�ӽڵ�Ļ������������������滹��div 
					.eq(index).show()   //��ʾ <li>Ԫ�ض�Ӧ��<div>Ԫ��
					.siblings().hide(); //������������ͬ����<div>Ԫ��
		}).hover(function(){
			$(this).addClass("hover");
		}/*,function(){
			$(this).removeClass("hover");
		}*/)
})