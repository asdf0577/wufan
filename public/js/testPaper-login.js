$(function(){
	
	
	$("a[title='register']").click(function(){
		$(this).parent().hide();
		$(this).parent().parent().find("#registerTable").show();
		
	})
	
	$("a[title='login']").click(function(){
		
		$(this).parent().hide();
		$(this).parent().parent().find("#loginTable").show();
		
	})
	
	$("a[title='teacher']").click(function(){
		
		var url = "/album/logining/teacherLogin"
		
		$("form[name='student']").prop('action',url);
		$("input[name='Login']").prop('value','教师登陆');
		console.log($("form[name='student']").attr('action'));
	})
	$("a[title='student']").click(function(){
		
		var url = "/album/logining/process"
			
			$("form[name='student']").prop('action',url);
		$("input[name='Login']").prop('value','学生登陆');
		console.log($("form[name='student']").attr('action'));
	})

	
	
	
	
	
})












