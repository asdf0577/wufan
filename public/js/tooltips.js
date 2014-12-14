$(function(){
	var x = 10;
	var y = 20;
	$("a.tooltips").mouseover(function(e){
		this.myTitle = this.title;
		this.title = "";
		var tooltip = "<div id='tooltips'>"+this.myTitle+"</div>";
		$("body").append(tooltip);
		$("#tooltips").css({"top":(e.pageY+y)+"px","left":(e.pageX+x)+"px"}).show("fast");
	}).mouseout(function(e){
		this.title = this.myTitle;
		$("#tooltips").remove();
		
	}).mousemove(function(e){
		$("#tooltips").css({
			"top":(e.pageY+y)+"px",
			"left":(e.pageX+x)+"px"
		});
	});
})