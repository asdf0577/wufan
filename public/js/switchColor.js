/*衣服颜色切换*/var alt = $(this).attr("alt");
$(function(){
	$(".color_change ul li img").click(function(){    
		  $(this).addClass("hover").parent().siblings().find("img").removeClass("hover");
		  var imgSrc = $(this).attr("src");//获取当前图片地址
		  var i = imgSrc.lastIndexOf(".");//找到当前图片地址最后一个点的位置
		  var unit = imgSrc.substring(i);//获取当前地址内的后缀名
		  imgSrc = imgSrc.substring(0,i);//获取当前地址内文件名（不包括后缀名）
		  var imgSrc_small = imgSrc + "_one_small"+ unit;//生成新的small格式图片
		  var imgSrc_big = imgSrc + "_one_large"+ unit;//生成新的big格式图片
		  $("#bigImg").attr({"src": imgSrc_small });//将bigimg标签的src设置为新的small的图片
		  $("#thickImg").attr("href", imgSrc_big);//将thickimg标签的src设置为新的large的图片
		  //获取当前点击图片的alt内容
		  var alt =$(this).attr("alt");
		  $(".color_change strong").text(alt);//将color_change 的strong内的文字替换为当前点击的图片的alt
		  var newImgSrc = imgSrc.replace("/images/pro_img/","");//生成一个变量保存去掉”/image/images/pro_img/“后的文件名
		  $("#proitem .imgList li").hide();//将imglist内的 li隐藏
		  $("#proitem .imgList").find(".imgList_"+newImgSrc).show();//将imglist内找到newImagsrc 显示
		  //解决问题：切换颜色后，放大图片还是显示原来的图片。
		  $("#proitem .imgList").find(".imgList_"+newImgSrc).eq(0).find("a").click();
	});
});