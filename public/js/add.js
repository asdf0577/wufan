$(function(){
	var $imgrolls = $("#Imageroll div a");
	$imgrolls.css("opacity","0.7");
    var len  = $imgrolls.length;
	var index = 0;
	var adTimer = null;
	$imgrolls.mouseover(function(){
		index = $imgrolls.index(this);
		showImg(index);
	}).eq(0).mouseover();	
	//滑入 停止动画，滑出开始动画.
	$('#Imageroll').hover(function(){
			if(adTimer){ 
				clearInterval(adTimer);
			}
		 },function(){
			adTimer = setInterval(function(){
			    showImg(index);
				index++;
				if(index==len){index=0;}
			} , 5000);
	}).trigger("mouseleave");
})
//显示不同的幻灯片
function showImg(index){
	var $rollobj = $("#Imageroll");
	var $rolllist = $rollobj.find("div a");  
	var newhref = $rolllist.eq(index).attr("href");
	$("#JS_imgWrap").attr("href",newhref)
			 .find("img").eq(index).stop(true,true).fadeIn().siblings().fadeOut();
}