$(function(){
	//开启答题点击
	
	$("a[title='open']").click(function(){
		
		$(this).parent().parent().next().toggle(300);
		var that = $(this).parent().parent().next().find('form#classAcl');
		var oldCid =[];
		var statusForm =  $(this).parent().parent().next().find('form#classAclStatus');
		var uid = that.find("input[name='uid']").val();
		var tid = that.find("input[name='tid']").val();
		$.ajax({
			type:"post",
			url:"../album/testpaper/getClassAcl",
			data:{tid:tid,
					uid:uid},
			success:function(data){
				var opts = $.parseJSON(data);
				$.each(opts,function(i,d){
					oldCid.push(d.cid);
					that.find("input:checkbox[value='"+d.cid+"']").prop("checked",true);
					if(d.status == 1 ){
					statusForm.find("input:checkbox[value='"+d.cid+"']").prop("checked",true);
					}
				})
				var statusCheckBox = statusForm.find("input[type='checkbox']");
				$.each(statusCheckBox,function(i,d){
					if($.inArray(d.value,oldCid)==-1){
						statusForm.find("input:checkbox[value='"+d.value+"']").prop("disabled",true);
					}
				})
			},
			error:function(data){
				alert(data);
			}
		}) 
		return false;
});
	
	

	//提交答题ACL结果
	$("input#classSelectSubmit").click(function(){
	var that = $(this).parent().parent().next();
	var newChecked = $(this).parent().find($("input:checkbox:checked")).map(function(){
	      return $(this).val();
	    }).get();
	var deleteArray=[],deleteName=[],addArray=[],oldCid=[];
	var tid =$(this).parent().find("input[name='tid']").val();
	var uid =$(this).parent().find("input[name='uid']").val();
	var inputdata = "tid="+tid+"&uid="+uid;
	
	$.ajax({
		type:"post",
		url:"../album/testpaper/getClassAcl",
		data:inputdata,
		success:function(data){
			var opts = $.parseJSON(data);
			$.each(opts,function(i,d){
				if($.inArray(d.cid,newChecked)==-1){
					deleteArray.push(d.cid);
					deleteName.push(d.class_name);
				}
				oldCid.push(d.cid);
			})
			$.each(newChecked,function(i,d){
				if($.inArray(d,oldCid)==-1){
					addArray.push(d);
				}
			})
			if(deleteArray!= "" ){

				if(confirm("所选班级将被取消该试卷答题并删除名下所有资料："+deleteName)){
					inputdata = inputdata+"&cid="+deleteArray;
					$.ajax({
						type:"post",
						url:"../album/question/deleteAcl",
						data:inputdata,
						success:function(data){
							alert(data);
							$.each(deleteArray,function(i,d){
								that.find("input:checkbox[value='"+d+"']").prop('checked',false);
								that.find("input:checkbox[value='"+d+"']").prop('disabled',true);
								})
						},
						error:function(data){
							alert(data);
						},
						}
						)
				}
			}
			if(addArray!= "" ){
				inputdata = inputdata+"&cid="+addArray;
				alert(inputdata);
				$.ajax({
					type:"post",
					url:"../album/question/createAcl",
					data:inputdata ,
					success:function(data){
						alert(data);
						$.each(addArray,function(i,d){
							that.find("input:checkbox[value='"+d+"']").prop('checked',true);
							that.find("input:checkbox[value='"+d+"']").prop('disabled',false);
							})
					},
					error:function(data){
						alert(data);
					},
					}
					)
			}
			
		},
		error:function(data){
			alert(data);
		}
		})
		return false;
		
	});
	
	//班级是否可见按钮
	$("input#classAclStatus").click(function(){
		
		data = $(this).parent().serialize();
		alert(data);
		$.ajax({
			type:"post",
			url:"../album/question/changeAclStatus",
			data:data,
			success:function(data){
				alert(data);
			}})
		return false;
	})
})