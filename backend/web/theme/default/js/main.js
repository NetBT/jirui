$(function(){

    var bodyH = document.documentElement.clientHeight;
    var bodyW = document.documentElement.clientWidth;
	
	
	$(".main_left").css("height",bodyH-126);
	$(".main_nav").css("height",bodyH-126);
	$(".main_right").css("height",bodyH-126);
	$(".main_right").css("width",bodyW-264);
	$(".main_right iframe").css("height",bodyH-126);
	$(".main_right iframe").css("width",bodyW-247);
	$(".main_right p.tab").css("width",bodyW-284);
	if(bodyW<960){
	  $(".main_right").css("width",bodyW-60);
      $(".main_right p.tab").css("width",bodyW-80);
	  $(".main_right iframe").css("width",bodyW-43);
	}
	
	
	
	$(".main_left_box a").click(function(){
		var alink = $(this).attr("href");
		$(".main_right p.tab a").removeClass("hover");
		var tablink = "<a href='"+$(this).attr("href")+"' class='hover'>"+$(this).attr("title")+"<i>&nbsp;</i></a>";
		$(".main_right p.tab").append(tablink)
		$(".main_right iframe").attr("src",alink);
		return false;
	});
	$(".main_nav a").click(function(){
		var alink = $(this).attr("href");
		$(".main_right p.tab a").removeClass("hover");
		var tablink = "<a href='"+$(this).attr("href")+"' class='hover'>"+$(this).attr("title")+"<i>&nbsp;</i></a>";
		$(".main_right p.tab").append(tablink)
		$(".main_right iframe").attr("src",alink);
		return false;
	});
	$(".main_top a.logo").click(function(){
		$(".main_right iframe").attr("src",$(this).attr("href"));
		return false;
	});
	// $(".main_right p.tab a i").on("click",function(){
	// 	$(this).parent().remove();
	// 	if($(this).parent().hasClass("hover")){
	//     	$(".main_right p.tab a").eq(0).click()
	// 	}
	// 	return false;
	// });
	// $(".main_right p.tab a").live("click",function(){
	// 	$(".main_right p.tab a").removeClass("hover");
	// 	$(this).addClass("hover")
	// 	$(".main_right iframe").attr("src",$(this).attr("href"));
	// 	return false;
	// });
	
	
	
	
	
	$(".main_left_box a.btn span").click(function(){
		var btn = $(".main_left_box a.btn span").index(this);
		$(".main_left_box ul").stop().slideUp(300).eq(btn).stop().slideDown(300);
		$(".main_left_box a.btn").removeClass("over").eq(btn).addClass("over");
		$(".main_left_box ul").eq(btn).find("li .box").eq(0).click();
	});
	$(".main_left_box ul li .box").click(function(){
		$(".main_left_box ul li").removeClass("hover")
		$(".main_left_box ul li p").stop().slideUp(300)
		$(this).parent().addClass("hover");
		$(this).parent().find("p").stop().slideDown(300);
		$(this).parent().find("p").addClass("hover")
	});
	$(".main_left_box ul li p a").click(function(){
		$(".main_left_box ul li p a").removeClass("hover")
		$(this).addClass("hover");
	});
});


$(window).resize(function(){
    var bodyH = document.documentElement.clientHeight;
    var bodyW = document.documentElement.clientWidth;
	
	
	$(".main_left").css("height",bodyH-126);
	$(".main_nav").css("height",bodyH-126);
	$(".main_right").css("height",bodyH-126);
	$(".main_right").css("width",bodyW-264);
	$(".main_right iframe").css("height",bodyH-126);
	$(".main_right iframe").css("width",bodyW-247);
	$(".main_right p.tab").css("width",bodyW-284);
	
	if(bodyW<960){
	  $(".main_right").css("width",bodyW-60);
      $(".main_right p.tab").css("width",bodyW-80);
	  $(".main_right iframe").css("width",bodyW-43);
	}
	
});