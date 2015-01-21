$(function(){
	var $form = $('form');
	
		$form.submit(function(e){
			data = new FormData();
			data.append("CSVUpload",$("input[type='file']").files[0]);
//			alert(data);
//			return false;
		    $.ajax({
	         	  url: "../Student/csv",
	         	  data: data,
	              contentType:false,
	              processData:false,
	              type: "POST",
	             
	              success:function(data){
	            	  alert(data);
	              }
	          });
			       
		 return false;
	});
		
	$("input").change(function(e){
		 var form = ('#uploadFiles');
		var input = $(":file");
		var file = this.files[0];
		console.log(file);
		console.log(input);
		console.log(form);
//		return false;
		var type = file.type;
		if(type !== "text/csv"){
			alert("文件格式错误，请选择CSV文件");
			input.val(""); 
			return false;
		}
		 $(":file").css("background-color","#B2E0FF");
		$("tr:gt(0)").remove();
		$(".table").hide().show();
		data = new FormData();
		data.append("CSVUpload",file);
//		data.append("CSVUpload",$("input[type='file']").files[0]);
		$.ajax({
       	  url: "../Student/csv",
       	  data: data,
            contentType:false,
            processData:false,
            type: "POST",
           
            success:function(list){
//            	alert(list);
            	
            	var opts = $.parseJSON(list);
				$.each(opts,function(i,d){
					var TD ="";
					for (var x=0;x<d.length;x++){
						TD = TD+"<td>"+d[x]+"</td>";
					}
					var TR ="<tr>"+TD+"</tr>";
					$(".table").append(TR);
       			    TR ="";
					
				})          	  
          	  
          	  
          	  
            }
        });
		
	})
	
	
		
		
	
})