$(function(){
	//开启答题点击
	
	$("a[title='showStudents']").click(function(){
		var that = $(this).parent().parent().next();
		that.toggle(300);
		var totalUser = that.find("input[name='totalUser']").val();
		var data = "totalUser="+totalUser;
		$.ajax({
			type:"post",
			url:"../Student/getStudentsByTestPaper",
			data:data,
			success:function(data){
				var students ="";
//				console.log(data);
				var opts = $.parseJSON(data);
				$.each(opts,function(i,d){
					students = students+
					"<a href = '#' name = 'studentInfo' title ='"+d.sid+"'>"+d.name+"</a>&nbsp&nbsp&nbsp";
//					console.log(d.name);
				})
				that.find("#studentList").empty().append(students);
			},
			error:function(data){
				console.log(data);
		},
		});
		return false;
});
	
	

	
	
	
})