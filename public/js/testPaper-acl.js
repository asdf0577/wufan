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
	
	//删除试卷按钮
	
	$("a[title='delete']").click(function(){
		var that = $(this).parent();
		var td =  $(this).parent().parent()
		var tid = that.find("input[name='tid']").val();
		var uid = that.find("input[name='uid']").val();
		$("#tooltips3").remove();
		var unit = td.find("td:eq(2)").text();
		var year = td.find("td:eq(0)").text();
		var term = td.find("td:eq(1)").text();
		var tips = "<span>您要删除<bold>"+year+"学年-"+term+"学期-"+unit+"单元</bold>的【试卷】吗？这样将删除该试卷所关联的班级记录、学生记录，确定请输入您的用户密码</span></br>";
		var buttonID = "deleteTestPaper";
		var location =that;
		confirmBox(tips,uid,tid,buttonID,location);
		return false;
		
		
	})
	
	
	//所有的删除工作均弹出密码框
	function confirmBox(tips,uid,tid,buttonID,location){
		var htm1 = "<div id='tooltips3'>" +
		"<div class='close'>X</div>" +tips+
				"<input type ='text' name ='password'>" +
				"<input type ='hidden' name ='tid' value='"+tid+"'>" +
				"<input type ='hidden' name ='uid' value='"+uid+"'>" ;
		var htm3 = "<button id = '"+buttonID+"'>确认</button></div>";
		var htm = htm1+htm3;
		$(location).append(htm);
		$("#tooltips3").fadeIn(1000);
		$('.close').fadeIn(3000);
			}
	
	$('body').on("click","button#deleteTestPaper",function(){
		var that = $(this).parent();
		var password = that.find("input[name='password']").val();
		var uid =that.find("input[name='uid']").val();
		var tid =that.find("input[name='tid']").val();
		data = "password="+password+"&uid="+uid+"&tid="+tid;
		$.ajax({
			type:"POST",
			url:'../album/testpaper/delete',
			data:data,
			success: function(list) {
				console.log(list);
				if(list == "false"){
					alert("密码错误");
				}else{
					that.parent().parent().hide(300).remove();
					that.hide(300).remove();
					
				}
			}
		});
		return false;
	}
	)
	
	
	
})