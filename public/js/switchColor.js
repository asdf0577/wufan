/*�·���ɫ�л�*/var alt = $(this).attr("alt");
$(function(){
	$(".color_change ul li img").click(function(){    
		  $(this).addClass("hover").parent().siblings().find("img").removeClass("hover");
		  var imgSrc = $(this).attr("src");//��ȡ��ǰͼƬ��ַ
		  var i = imgSrc.lastIndexOf(".");//�ҵ���ǰͼƬ��ַ���һ�����λ��
		  var unit = imgSrc.substring(i);//��ȡ��ǰ��ַ�ڵĺ�׺��
		  imgSrc = imgSrc.substring(0,i);//��ȡ��ǰ��ַ���ļ�������������׺����
		  var imgSrc_small = imgSrc + "_one_small"+ unit;//�����µ�small��ʽͼƬ
		  var imgSrc_big = imgSrc + "_one_large"+ unit;//�����µ�big��ʽͼƬ
		  $("#bigImg").attr({"src": imgSrc_small });//��bigimg��ǩ��src����Ϊ�µ�small��ͼƬ
		  $("#thickImg").attr("href", imgSrc_big);//��thickimg��ǩ��src����Ϊ�µ�large��ͼƬ
		  //��ȡ��ǰ���ͼƬ��alt����
		  var alt =$(this).attr("alt");
		  $(".color_change strong").text(alt);//��color_change ��strong�ڵ������滻Ϊ��ǰ�����ͼƬ��alt
		  var newImgSrc = imgSrc.replace("/images/pro_img/","");//����һ����������ȥ����/image/images/pro_img/������ļ���
		  $("#proitem .imgList li").hide();//��imglist�ڵ� li����
		  $("#proitem .imgList").find(".imgList_"+newImgSrc).show();//��imglist���ҵ�newImagsrc ��ʾ
		  //������⣺�л���ɫ�󣬷Ŵ�ͼƬ������ʾԭ����ͼƬ��
		  $("#proitem .imgList").find(".imgList_"+newImgSrc).eq(0).find("a").click();
	});
});