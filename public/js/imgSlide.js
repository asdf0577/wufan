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
		//outerWidth() 方法返回元素的宽度（包括内边距和边框）。
		rollwidth = rollwidth*4;//一个版面的宽度
		$rollobj.stop(true,false).animate({
			left:-rollwidth*idx},1000);
		}
		
