$(function(){
	$("a#createClass").click(function(e){
		 $.ajax({
		       url:'../album/class-manager/add',
		       async:false, 
		       success: function(html) {
		    	   var htm = "<div id='tooltips2'><div class='close'>X</div>"+html+"</div>";
		    	 
		          $('body').append(htm);
		          $("#tooltips2").fadeIn(1000);
		          $('.close').fadeIn(3000);
		       }
		    });
		
		return false;
	});
	//编辑框
	function addTR(cid,hidden,gender,studentNum,name,role,sid,csv){
		var genderSelect;
		if(gender == undefined){
			gender="";
			genderSelect ="<option value = '1' >男</option>"+
		      			"<option value = '2'>女</option></select>";
		}
		if(gender == 1){
			genderSelect ="<option value = '1' selected >男</option>"+
					      "<option value = '2'>女</option></select>";
		}
		if(gender == 2){
			genderSelect ="<option value = '1' >男</option>"+
			"<option value = '2' selected>女</option></select>";
		}
		
		
		
		if(studentNum == undefined){
			studentNum = "";
		}
		if(name == undefined){
			name = "";
		}
		var roleSelect;
		if(role == undefined){
			role = "";
			roleSelect = "<option value = 'student'>student</option>"+
					     "<option value = 'manager'>manager</option></select>";
		}
		if(role == "student"){
			roleSelect = "<option value = 'student' selected>student</option>"+
					     "<option value = 'manager'>manager</option></select>";
		}
		if(role == "manager"){
			roleSelect = "<option value = 'student' selected>student</option>"+
					     "<option value = 'manager' selected >manager</option></select>";
		}
		
		if(sid == undefined){
			sid = "";
		}	
		if(cid == undefined){
			cid = "";
		}
		console.log(sid);
		var editButton;
		editButton = "<input type ='submit'name='saveEditCSV' value ='保存'>";
		
		if(csv == undefined){
			editButton ="<input type ='submit'name='saveEditStudent' value ='更新'>";
		}
		
		if(csv == undefined && sid == ""){
			editButton = "<input type ='submit'name='saveEditStudent' value ='添加'>";
		}
		
		var addTR = hidden+"<td ='afterTrTd' colspan=7>" +
		"性别 <select class ='addStudentName'name = 'gender' value='"+gender+"'>" +genderSelect+
		"学号<input type ='text'  class ='addStudentName'name = 'studentNum' value ='"+studentNum+"'>" +
		"姓名 <input type ='text'  class='addStudentName' name = 'name' value ='"+name+"'>" +
		"权限 <select class ='addStudentName'name = 'role' value ='"+role+"'>" +roleSelect+
		"<input type = 'hidden' name ='cid' value ='"+cid+"'>"+
		"<input type = 'hidden' name ='sid' value ='"+sid+"'>"+editButton+
		"<input type ='submit'name='cancel' value ='取消'>"+
		"</td></tr>";
		return addTR;
	}
	
	
	//显示学生列表
	$("a[title='open']").click(function(){
			
		var that = $(this).parent().parent();
		that.next().toggle(500);
		that.next().find('table#append').empty();
		that.next().find('div#appendCSV').remove();
		var cid = $(this).parent().parent().find("input[name='cid']").val();
		var TH = 	
		"<th width='7%'>性别</th>"+
		"<th width='10%'>学号</th>"+
		"<th width='15%'>姓名</th>"+
		"<th width='5%'>登记次数</th>"+
		"<th width='5%'>错题总数</th>"+
		"<th width='10%'>角色</th>"+
		"<th width='10%'></th>"+
		"<th name='appendButton'  width='25%'><span class='tdButton'>设置</span></th>";
		
		that.next().find('table#append').append(TH);
		var hidden="<tr class='subTr'>";
		var TR = addTR(cid,hidden);
		
		that.next().find('table#append').append(TR);
		var uid = that.find("input[name='uid']").val();
		var cid = that.find("input[name='cid']").val();
		var data = "cid="+cid+"&uid="+uid;
		$.ajax({
			type:"post",
			url:"../album/class-manager/getStudents",
			data:data,
			success:function(list){
				var opt = $.parseJSON(list);
				var TD ="";
				var gender;
				$.each(opt,function(i,d){
					if(d.gender==1){gender="男";}else{
						gender="女";
					}
					   TD = "<td>"+gender+"</td>"+
							"<td>"+d.studentNum+"</td>"+
							"<td>"+d.name+"<input type='hidden' name ='sid' value = '"+d.id+"'</td>"+
							"<td></td>"+
							"<td></td>"+
							"<td>"+d.role+"</td>"+
							"<td><a herf='#' title ='editStudent' class='appendStudent'>编辑</a>&nbsp&nbsp"+	
							 "<a herf='#' title ='deleteStudent' class='appendStudent'>删除</a></td>";
					var TR="<tr>"+TD+"</tr>";
					that.next().find('table#append').append(TR);
					TR="";
					});
				},    
			error:function(data){
				console.log(data);
				alert("error");
			}
		}); 
		return false;
});
	//新增学生切换
	$('body').on("mouseenter","th[name='appendButton']",function(){
		var htm = "<a href ='#' title ='addSingleStudent'>添加学生</a>&nbsp"+
					"<a href ='#' title ='addCSV'>批量导入</a>&nbsp"+
					"<a href ='#' title='safeMode'>解除安全状态</d>";
		$(this).empty().append(htm);
	}).on("mouseleave","th[name='appendButton']",function(){
		$(this).empty().append("<span class='tdButton'>设置</span>");
	})
	//显示添加单个学生表格
	$('body').on("click","a[title='addSingleStudent']",function(){
		console.log($(this).parent().next().find('tr.subTr'));
		$(this).parent().next().find("tr.subTr").show();
		});
	//添加/更新单个学生
	$('body').on("click","input[name='saveEditStudent']",function(){
		var that = $(this).parent();
		var prev = that.parent().prev();
		var cid = that.find("input[name='cid']").val();
		var	sid = that.find("input[name='sid']").val();
		console.log(sid);
		var gender= that.find("select[name='gender']").val();
		var genderText= that.find("select[name='gender']").find("option:selected").text();
		var studentNum= that.find("input[name='studentNum']").val();
		var name= that.find("input[name='name']").val();
		var role= that.find("select[name='role']").val();
		//设置返回操作，1是添加，0是更新 默认为更新
		var callbackType = 0;
		data = "cid="+cid+"&sid="+sid+"&gender="+gender+"&studentNum="+studentNum+"&name="+name+"&role="+role;
		//如果有sid，说明是更新，没有则是添加
		if(sid == ""){
			data = "cid="+cid+"&gender="+gender+"&studentNum="+studentNum+"&name="+name+"&role="+role;
			callbackType = 1;
		}
		
		console.log(callbackType);
		$.ajax({
       	  url: "../album/Student/addProcess",
       	  data: data,
            type: "POST",
            success:function(data){
            	
            	//如果是更新操作，则删除当前添加窗口，显示被隐藏的信息栏并填充添加的信息
            	if(callbackType == 0){
            		alert("更新成功");
            		prev.find("td:eq(0)").text(genderText);
            		prev.find("td:eq(2)").text(name);
            		prev.find("td:eq(1)").text(studentNum);
            		prev.find("td:eq(5)").text(role);
            		prev.show();
            		that.remove();
            	}else{
            	//如果是新增操作，则删除当前添加窗口，并添加一行 	
            		alert("添加成功");
            		window.location.href="http://www.wufan.com/album/class-manager";
            	}
//            	
//        		
            },
        });
	});
	// 保存编辑CSV
	$('body').on("click","input[name='saveEditCSV']",function(){
		var that = $(this).parent();
		var prev = that.parent().prev();
		var gender= that.find("select[name='gender']").val();
		var studentNum= that.find("input[name='studentNum']").val();
		var name= that.find("input[name='name']").val();
		var role= that.find("select[name='role']").val();
		prev.find("td:eq(0)").text(gender);
		prev.find("td:eq(1)").text(name);
		prev.find("td:eq(2)").text(studentNum);
		prev.find("td:eq(3)").text(role);
		prev.show()
		that.remove();
	});
	//ajax显示批量添加
	$('body').on("click","a[title='addCSV']",function(){
		var that = $(this).parent().next().find("tr.subTr");
		$(this).parent().parent().find('tr.appendCSV').remove();
		$.ajax({
			url:'../album/Student/csv',
			async:false, 
			success: function(html) {
				var htm = "<tr class='appendCSV'><td  colspan=8>"+html+"</td></tr>";
				that.after(htm);
			}
	});
	})
	//8.1编辑已经存在的student或csv文件中的student
	$('body').on("click","a[title='editStudent']",function(){
	 	var that =$(this).parent().parent();
	 	var gender = that.find("td:eq(0)").text();
	 	
	 	
	 	if(gender =="男"){
	 		gender =1;
	 	}else{
	 		gender =2;
	 	}
	 	var name = that.find("td:eq(2)").text();
	 	var studentNum = that.find("td:eq(1)").text();
	 	var role = that.find("td:eq(5)").text();
	 	
	 	//如果没有sid，说明是编辑导入的列表，更改最后的连接为保存当前编辑框
	 	var sid = that.find("input[name='sid']").val();
	 	
	 	var cid = that.parent().parent().parent().parent().prev().find("input[name='cid']").val();
	 	var hidden="<tr>",TR;
	 	if(sid == undefined){
	 		var csv = 1;
		 	 TR = addTR(cid,hidden,gender,studentNum,name,role,sid,csv);
	 	} else {
		 	 TR = addTR(cid,hidden,gender,studentNum,name,role,sid)
	 	}
	 	
		$(this).parent().parent().after(TR);
		$(this).parent().parent().hide();
	});
	
	
	//8.2取消编辑已经存在的student或csv文件中的student
	$('body').on("click","input[name='cancel']",function(){
		$(this).parent().parent().prev().show();
		$(this).parent().parent().remove();
})

	
	
	
	//所有的删除工作均弹出密码框
	function confirmBox(tips,uid,cid,buttonID,location,sid){
		var htm1 = "<div id='tooltips3'>" +
		"<div class='close'>X</div>" +tips+
				"<input type ='text' name ='password'>" +
				"<input type ='hidden' name ='uid' value='"+uid+"'>" +
				"<input type ='hidden' name ='cid' value='"+cid+"'>";
		var htm2 = "";
		if(sid){
			htm2 = "<input type ='hidden' name ='sid' value='"+sid+"'>" ;
		}
		var htm3 = "<button id = '"+buttonID+"'>确认</button></div>";
		var htm = htm1+htm2+htm3;
		$(location).append(htm);
		$("#tooltips3").fadeIn(1000);
		$('.close').fadeIn(3000);
			}
	
	//解除安全模式
	$('body').on("click","a[title='safeMode']",function(){
		$("#tooltips3").remove();
		var that = $(this).parent().parent().parent().parent().prev();
		var uid = that.find("input[name='uid']").val();
		var cid = that.find("input[name='cid']").val();
		var name = that.find("td:eq(2)").text();
		var year = that.find("td:eq(1)").text();
		var tips = "<span>您要解除<bold>"+year+"-"+name+"</bold>的【安全模式】吗？这样删除学生就不需要再次输入密码了，确定请输入您的用户密码</span></br>";
		var buttonID = "safeModeOff";
		var location = $(this).parent().parent();
		confirmBox(tips,uid,cid,buttonID,location);
		return false;
	});
	//解除安全模式确认
	$('body').on("click","button#safeModeOff",function(){
		var that = $(this).parent();
		var password = that.find("input[name='password']").val();
		var uid =that.find("input[name='uid']").val();
		var cid =that.find("input[name='cid']").val();
		data = "password="+password+"&uid="+uid;
		$.ajax({
			type:"POST",
			url:'../album/class-manager/safeMode',
			data:data,
			success: function(list) {
				if(list == "true"){
					//Jquery 没有提供MD5转换，需要额外插件，目前先用明文密码
					var y = that.parent().find("a[title='deleteStudent']");
					y.each(function(i,d){           
						var htm = "<input type= 'hidden' name ='password' value = '"+password+"'>";
						$(this).parent().append(htm);
						$(this).attr('title','deleteWithPassword').addClass("tdButton");
					})
					that.hide(300).remove();
				}else{
					alert("密码错误");
				}
			}
		});
		return false;
	}
	)
	//带密码框删除学生按钮
	$('body').on("click","a[title='deleteStudent']",function(){
		$("#tooltips3").remove();
		var that = $(this).parent().parent().parent().parent().parent().parent().prev();
		var uid = that.find("input[name='uid']").val();
		var cid = that.find("input[name='cid']").val();
		var sid = $(this).parent().parent().find("input[name='sid']").val();
		var name= $(this).parent().parent().find("td:eq(2)").text();
		var tips = "<span>如果您要删除<bold>"+name+"</bold>的所有资料，请输入密码</span></br>";
		var buttonID = "deleteStudentConfirm";
		var location = $(this).parent().parent();
		confirmBox(tips,uid,cid,buttonID,location,sid);
		return false;
	});
	
	

	//带密码框和不带密码框删除学生确认
		$('body').on("click","button#deleteStudentConfirm,a[title='deleteWithPassword']",function(){
		var that = $(this);
		var prevTr =  $(this).parent().parent().parent().parent().parent().parent().prev();
		var uid = prevTr.find("input[name='uid']").val();
		var num = prevTr.find("td:eq(3)").text();
		var sid =$(this).parent().find("input[name='sid']").val();
		if(sid == undefined){
			sid = $(this).parent().parent().find("input[name='sid']").val();
		}
		var password = $(this).parent().find("input[name='password']").val();
		var cid =$(this).parent().find("input[name='cid']").val();
		var callbackType = 1;
		data = "password="+password+"&uid="+uid+"&cid="+cid+"&sid="+sid;
		if(cid== undefined){
			data = "password="+password+"&uid="+uid+"&sid="+sid;
			callbackType = 0;
		}
		$.ajax({
			type:"POST",
			url:'../album/Student/delete',
			data:data,
			success: function(data) {
				console.log(data)
				//注意这里删除dom元素有先后的顺序，否则会出错。
				that.parent().parent().hide(300).remove();
				if(callbackType == 1){
					that.parent().hide(300).remove();
				}
				prevTr.find("td:eq(3)").text(num-1)
			}
		});
		return false;
	});
		
		//删除带密码框班级
		$("a[title='delete']").click(function(){
			$("#tooltips3").remove();
			var that = $(this).parent().parent()
			var uid = that.find("input[name='uid']").val();
			var cid = that.find("input[name='cid']").val();
			var td = that.find("td");
			var name = td.eq(2).text();
			var year = td.eq(1).text();
			var tips = "<span>如果您要删除<bold>"+year+"-"+name+"</bold>及该班所有学生资料，请输入密码</span></br>";
			var buttonID = 'deleteConfirm';
			var location = $(this).parent();
			confirmBox(tips,uid,cid,buttonID,location);
			return false;
		});
		//删除带密码框班级确认
		$('body').on("click","button#deleteConfirm",function(){
			var that = $(this).parent();
			var password = that.find("input[name='password']").val();
			var uid =that.find("input[name='uid']").val();
			var cid =that.find("input[name='cid']").val();
			data = "password="+password+"&uid="+uid+"&cid="+cid;
			console.log(data);
			$.ajax({
				type:"POST",
				url:'../album/class-manager/delete',
				data:data,
				success: function(list) {
//					console.log(list);
					alert(list);
					window.location.href="http://www.test.com/album/class-manager";
				}
			});
			return false;
		}
		)	
		
		//批量导入：提交CSV文件获取学生信息表		
	$('body').on('change',"input[name='CSVUpload[]']",function(e){
		//一旦选择文件栏发生改变，首先移除该文件栏下方的旧的学生表记录
		$("tr.csvTr").remove();
		var input = $("input[name='CSVUpload[]']");
		var file = this.files[0];
		//输入文件的格式验证 @todo大小 内容验证
		if(file.type!== "text/csv"){
			alert("文件格式错误，请选择CSV文件");
			input.val(""); 
			return false;
		}
		$("table#CSVtable").hide().show();
		$("table#CSVtable").css("background-color","white");
		
		data = new FormData();
		data.append("CSVUpload",file);
		$.ajax({
       	  url: "../album/Student/csv",
       	  data: data,
            contentType:false,
            processData:false,
            type: "POST",
           
            success:function(list){
            	var opts = $.parseJSON(list);
				$.each(opts,function(i,d){
					var TD ="<td width ='7%' name='gender' >"+d[2]+"</td>"+
							"<td width ='10%' name='studentNum' >"+d[3]+"</td>" +
							"<td width ='15%' name='name'>"+d[1]+"</td>"+
							"<td width ='10%'></td>" +
							"<td width ='10%'></td>"+
							"<td width ='10%'name='role'>"+d[0]+"</td >"+
							"<td><a herf='#' title ='deleteCSV' class='appendStudent'>删除</a>" +
							 "<a herf='#' title ='editStudent' class='appendStudent'>编辑</a>" +
								"</td>";
					
					var TR ="<tr class='csvTr'>"+TD+"</tr>";
					$("table#CSVtable").append(TR);
       			    TR ="";
					
				})          	  
          	  
          	  
          	  
            }
        });
		
	})
	//批量上传
		$('body').on('click',"input#CSVsubmit",function(){
		var tr  = $(this).parent().next().find('tr.csvTr');
		var cid = $(this).parent().parent().parent().parent().parent().parent().parent().prev().find("input[name='cid']").val();
		var data ="cid="+cid;
		var gender,studentNum,name,role;
		//循环学生表的数据
		tr.each(function(i){
			//循环每一行内的数据,形成要提交的data数据
			gender = $(this).find("td:eq(0)").text();
			studentNum = $(this).find("td:eq(1)").text();
			name = $(this).find("td:eq(2)").text();
			role = $(this).find("td:eq(5)").text();
			data = data+"&gender="+gender+"&studentNum="+studentNum+"&name="+name+"&role="+role;
			$(this).dealy(100).append("<td>√</td>");
			console.log(data);
			$.ajax({
	         	  url: "../album/Student/addProcess",
	         	  data: data,
	              type: "POST",
	              success:function(data){
	            	  console.log(data);
	              },
			})
//		重置data
		data ="cid="+cid;;
		}).promise().done(function(){
			alert("提交成功");
//			window.location.href="http://www.test.com/album/class-manager";
		})     
		 return false;
	});
		//批量导入的CSV文件删除行
		$('body').on('click',"a[title='deleteCSV']",function(){
			$(this).parent().parent().remove();
		})
		
});

