$(function(){
	$(".pro_size li").click(function(){
		$(this).addClass("hover").siblings().removeClass("hover");
		$(this).parents("ul").siblings("strong").text($(this).text());
		var alt = $(this).attr("alt");
		$(".pro_price strong").text(alt);
	})
	
	
})

$(function(){
	var $span =$(".pro_price strong");
	var price= $span.text();
	$(".qty,#qty").change(function(){
		var num =$(this).val();
		var amount = price*num;
		$span.text(amount);
	}).change();
	
})


