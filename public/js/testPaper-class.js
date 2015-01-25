$(function(){
	$("select[name='classType']").change(function(){
		var id = $(this).val();
		var yearSelect = $("select[name='year']");
		yearSelect.empty();
		var year = new Date() ;
		var Y = year.getFullYear();
		var html ="";
		switch(id){
			case "1":
			{
				for(var x = 0;x<6;x++){
					html=html+"<option value ="+Y+">"+Y+"</option>";
					Y=Y-1;
				}
				yearSelect.append(html);
				break;
			}
			case "2":
			{
				for(var x = 0;x<3;x++){
					html=html+"<option value ="+Y+">"+Y+"</option>";
					Y=Y-1;
				}
				yearSelect.append(html);
				break;
			}
			case "3":
			{
				for(var x = 0;x<3;x++){
					html=html+"<option value ="+Y+">"+Y+"</option>";
					Y=Y-1;
				}
				yearSelect.append(html);
				break;
			}
			case "4":
			{
				for(var x = 0;x<4;x++){
					html=html+"<option value ="+Y+">"+Y+"</option>";
					Y=Y-1;
				}
				yearSelect.append(html);
				break;
			}
			case "5":
			{
				for(var x = 0;x<6;x++){
					html=html+"<option value ="+Y+">"+Y+"</option>";
					Y=Y-1;
				}
				yearSelect.append(html);
				break;
			}
		
		} 
		
	

});
});
