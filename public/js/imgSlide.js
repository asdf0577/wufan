$(function(){
	$("#BrandTab li a").click(function(){
		$(this).parent().addClass("chos").siblings().removeClass("chos");
		var idx = $("#BrandTab li a").index(this);
		showBrandList(idx);
		return false;
	}).eq(0).click();
});
	function showBrandList(idx){
		var $rollobj = $("#BrandList");
		var rollwidth = $rollobj.find("li").outerWidth();
		//outerWidth() ��������Ԫ�صĿ�ȣ������ڱ߾�ͱ߿򣩡�
		rollwidth = rollwidth*4;//һ������Ŀ��
		$rollobj.stop(true,false).animate({
			left:-rollwidth*idx},1000);
		}
		
