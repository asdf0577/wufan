$(function(){
		
	
	$( "#selectable" ).selectable({
	      stop: function() {
	        var result = $( "#select-result" ).empty();
	        $( ".ui-selected", this ).each(function() {
	          var index = $( "#selectable li" ).index( this );
	          result.append( " 第" + ( index + 1 )+"题" );
	        });
	      }
	    });
//	$(document).mousemove(function(e){
//		$("#sidebar").css("top",e.pageY);
//		$("#mousemove").text(e.pageY-100);
//	})
//	var count = 0;
	
})
